<?php
namespace App\Http\Controllers;

use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PilihJadwalController extends Controller
{
    protected $googleCalendarController;

    public function __construct(GoogleCalendarController $googleCalendarController)
    {   
        $this->googleCalendarController = $googleCalendarController;
    }

    /**
     * Menampilkan halaman pilih jadwal
     */
    public function index()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $isConnected = false;
        if ($mahasiswa->hasGoogleCalendarConnected()) {
            $isConnected = app(GoogleCalendarController::class)->validateAndRefreshToken();
        }
        
        Log::info('Google Calendar Status:', [
            'has_tokens' => $mahasiswa->hasGoogleCalendarConnected(),
            'is_expired' => $mahasiswa->isGoogleTokenExpired(),
            'token_created' => $mahasiswa->google_token_created_at,
            'expires_in' => $mahasiswa->google_token_expires_in,
            'expiry_time' => $mahasiswa->getTokenExpiryTime()?->format('Y-m-d H:i:s'),
            'has_access_token' => !empty($mahasiswa->google_access_token),
            'has_refresh_token' => !empty($mahasiswa->google_refresh_token),
            'is_connected' => $isConnected
        ]);
        $dosenList = DB::table('dosens')
            ->select('nip', 'nama')
            ->get()
            ->map(function($dosen) {
                return [
                    'nip' => $dosen->nip,
                    'nama' => $dosen->nama
                ];
            })
            ->toArray();

        return view('bimbingan.mahasiswa.pilihjadwal', [
            'dosenList' => $dosenList,
            'isConnected' => $isConnected,
            'email' => $mahasiswa->email
        ]);
    }

    /**
     * Menyimpan jadwal bimbingan baru
     */
    public function store(Request $request)
    {
        try {
            if (!$this->googleCalendarController->validateAndRefreshToken()) {
                return response()->json(['error' => 'Belum terautentikasi dengan Google Calendar'], 401);
            }

            Log::info('Request pengajuan bimbingan:', $request->all());

            // Validasi request
            $request->validate([
                'nip' => 'required|exists:dosens,nip',
                'jenis_bimbingan' => 'required|in:skripsi,kp,akademik,konsultasi',
                'jadwal_id' => 'required|exists:jadwal_bimbingans,id',
                'deskripsi' => 'nullable|string'
            ]);

            DB::beginTransaction();

            // Cek jadwal dan dosen
            $jadwal = DB::table('jadwal_bimbingans as jb')
                ->join('dosens as d', 'jb.nip', '=', 'd.nip')
                ->where('jb.id', $request->jadwal_id)
                ->where('jb.status', 'tersedia')
                ->where('jb.sisa_kapasitas', '>', 0)
                ->where('jb.waktu_mulai', '>', now())
                ->select('jb.*', 'd.nama as dosen_nama', 'd.email as dosen_email')
                ->first();

            if (!$jadwal) {
                throw new \Exception('Jadwal tidak tersedia atau sudah penuh');
            }

            // Cek apakah mahasiswa sudah memiliki bimbingan yang sama
            $existingBimbingan = DB::table('usulan_bimbingans')
                ->where('nim', Auth::guard('mahasiswa')->user()->nim)
                ->where('jenis_bimbingan', $request->jenis_bimbingan)
                ->whereIn('status', ['USULAN', 'DITERIMA'])
                ->exists();

            if ($existingBimbingan) {
                throw new \Exception('Anda masih memiliki pengajuan bimbingan yang dalam proses');
            }

            // Cek bentrok jadwal
            $bentrok = DB::table('usulan_bimbingans')
                ->where('nim', Auth::guard('mahasiswa')->user()->nim)
                ->where('tanggal', Carbon::parse($jadwal->waktu_mulai)->toDateString())
                ->where(function($query) use ($jadwal) {
                    $query->whereBetween('waktu_mulai', [
                        Carbon::parse($jadwal->waktu_mulai)->format('H:i'),
                        Carbon::parse($jadwal->waktu_selesai)->format('H:i')
                    ])
                    ->orWhereBetween('waktu_selesai', [
                        Carbon::parse($jadwal->waktu_mulai)->format('H:i'),
                        Carbon::parse($jadwal->waktu_selesai)->format('H:i')
                    ]);
                })
                ->where('status', '!=', 'DITOLAK')
                ->exists();

            if ($bentrok) {
                throw new \Exception('Anda sudah memiliki jadwal bimbingan di waktu yang sama');
            }

            $mahasiswa = Auth::guard('mahasiswa')->user();
            
            // Buat event di Google Calendar
            $description = "Bimbingan {$request->jenis_bimbingan}\n" .
                    "Dosen: {$jadwal->dosen_nama}\n" .
                    "Lokasi: {$jadwal->lokasi}\n" .
                    "Status: Menunggu Persetujuan\n\n" .
                    ($request->deskripsi ? "Catatan: {$request->deskripsi}" : "");

            $eventData = [
                'summary' => "Bimbingan dengan {$jadwal->dosen_nama}",
                'description' => $description,
                'start' => Carbon::parse($jadwal->waktu_mulai),
                'end' => Carbon::parse($jadwal->waktu_selesai),
                'attendees' => [
                    ['email' => $jadwal->dosen_email]
                ],
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 24 * 60],
                        ['method' => 'popup', 'minutes' => 30],
                    ],
                ],
            ];

            $createdEvent = $this->googleCalendarController->createEvent($eventData);
            
            // Simpan ke database
            $bimbingan = DB::table('usulan_bimbingans')->insertGetId([
                'nim' => $mahasiswa->nim,
                'nip' => $request->nip,
                'dosen_nama' => $jadwal->dosen_nama,
                'mahasiswa_nama' => $mahasiswa->nama,
                'jenis_bimbingan' => $request->jenis_bimbingan,
                'tanggal' => Carbon::parse($jadwal->waktu_mulai)->toDateString(),
                'waktu_mulai' => Carbon::parse($jadwal->waktu_mulai)->format('H:i'),
                'waktu_selesai' => Carbon::parse($jadwal->waktu_selesai)->format('H:i'),
                'lokasi' => $jadwal->lokasi,
                'deskripsi' => $request->deskripsi,
                'status' => 'USULAN',
                'event_id' => $jadwal->event_id,
                'student_event_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update sisa kapasitas
            DB::table('jadwal_bimbingans')
                ->where('id', $request->jadwal_id)
                ->decrement('sisa_kapasitas');

            DB::commit();

            Log::info('Berhasil membuat bimbingan:', [
                'bimbingan_id' => $bimbingan,
                'event_id' => $createdEvent->id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal bimbingan berhasil diajukan',
                'data' => $bimbingan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error membuat jadwal bimbingan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getAvailableJadwal(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'nip' => 'required|exists:dosens,nip',
                'jenis_bimbingan' => 'required|in:skripsi,kp,akademik,konsultasi'
            ]);

            // Get jadwal yang tersedia
            $jadwal = DB::table('jadwal_bimbingans as jb')
                ->join('dosens as d', 'jb.nip', '=', 'd.nip')
                ->where('jb.nip', $request->nip)
                ->where('jb.status', 'tersedia')
                ->where('jb.sisa_kapasitas', '>', 0)
                ->where('jb.waktu_mulai', '>', now())
                ->select(
                    'jb.id',
                    'jb.event_id',
                    'jb.waktu_mulai',
                    'jb.waktu_selesai',
                    'jb.sisa_kapasitas',
                    'jb.catatan',
                    'jb.lokasi',
                    'd.nama as dosen_nama'
                )
                ->get()
                ->map(function ($item) {
                    $waktuMulai = Carbon::parse($item->waktu_mulai);
                    $waktuSelesai = Carbon::parse($item->waktu_selesai);
                    
                    // Cek apakah mahasiswa sudah memilih jadwal ini
                    $isSelected = DB::table('usulan_bimbingans')
                        ->where('nim', auth()->user()->nim)
                        ->where('event_id', $item->event_id)
                        ->where('status', '!=', 'DITOLAK')
                        ->exists();
                    
                    return [
                        'id' => $item->id,
                        'event_id' => $item->event_id,
                        'tanggal' => $waktuMulai->isoFormat('dddd, D MMMM Y'),
                        'waktu' => $waktuMulai->format('H:i') . ' - ' . $waktuSelesai->format('H:i'),
                        'sisa_kapasitas' => $item->sisa_kapasitas,
                        'lokasi' => $item->lokasi,
                        'catatan' => $item->catatan,
                        'dosen_nama' => $item->dosen_nama,
                        'is_selected' => $isSelected
                    ];
                })
                ->sortBy('waktu_mulai')
                ->values();

            return response()->json([
                'status' => 'success',
                'data' => $jadwal
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting available jadwal: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cek ketersediaan jadwal
     */
    public function checkAvailability(Request $request)
    {
        try {
            $request->validate([
                'jadwal_id' => 'required|exists:jadwal_bimbingans,id',
                'jenis_bimbingan' => 'required|in:skripsi,kp,akademik,konsultasi'
            ]);

            Log::info('Check Availability Request:', [
                'nim' => auth()->user()->nim,
                'jadwal_id' => $request->jadwal_id,
                'jenis_bimbingan' => $request->jenis_bimbingan
            ]);

            // Get event_id untuk logging
            $jadwal = DB::table('jadwal_bimbingans')
                ->where('id', $request->jadwal_id)
                ->first();
                
            if (!$jadwal) {
                return response()->json([
                    'available' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ]);
            }

            // Cek existing bimbingan
            $existingBimbingan = DB::table('usulan_bimbingans')
                ->where('nim', auth()->user()->nim)
                ->where('event_id', $jadwal->event_id)
                ->where('status', '!=', 'DITOLAK')
                ->exists();

            if ($existingBimbingan) {
                return response()->json([
                    'available' => false,
                    'message' => 'Anda sudah pernah mengajukan bimbingan untuk jadwal ini'
                ]);
            }

            // Cek pending bimbingan
            $pendingBimbingan = DB::table('usulan_bimbingans')
                ->where('nim', auth()->user()->nim)
                ->where('jenis_bimbingan', $request->jenis_bimbingan)
                ->whereIn('status', ['USULAN', 'DITERIMA'])
                ->exists();

            if ($pendingBimbingan) {
                return response()->json([
                    'available' => false,
                    'message' => 'Anda masih memiliki pengajuan bimbingan yang dalam proses'
                ]);
            }

            // Cek jadwal bentrok
            $bentrok = DB::table('usulan_bimbingans')
                ->where('nim', auth()->user()->nim)
                ->where('tanggal', Carbon::parse($jadwal->waktu_mulai)->toDateString())
                ->where(function($query) use ($jadwal) {
                    $query->whereBetween('waktu_mulai', [
                        Carbon::parse($jadwal->waktu_mulai)->format('H:i'),
                        Carbon::parse($jadwal->waktu_selesai)->format('H:i')
                    ])
                    ->orWhereBetween('waktu_selesai', [
                        Carbon::parse($jadwal->waktu_mulai)->format('H:i'),
                        Carbon::parse($jadwal->waktu_selesai)->format('H:i')
                    ]);
                })
                ->where('status', '!=', 'DITOLAK')
                ->exists();

            if ($bentrok) {
                return response()->json([
                    'available' => false,
                    'message' => 'Anda sudah memiliki jadwal bimbingan di waktu yang sama'
                ]);
            }

            return response()->json([
                'available' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Check Availability Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'available' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

        /**
     * Membuat event Google Calendar untuk bimbingan
     */
    public function createGoogleCalendarEvent(Request $request, $usulanId)
    {
        try {
            // Validate Google Calendar connection
            if (!$this->googleCalendarController->validateAndRefreshToken()) {
                Log::error('Google Calendar authentication failed for usulan ID: ' . $usulanId);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Belum terautentikasi dengan Google Calendar'
                ], 401);
            }

            // Get usulan data
            $usulan = DB::table('usulan_bimbingans as ub')
                ->join('dosens as d', 'ub.nip', '=', 'd.nip')
                ->where('ub.id', $usulanId)
                ->select('ub.*', 'd.email as dosen_email')
                ->first();

            if (!$usulan) {
                Log::error('Usulan not found: ' . $usulanId);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data bimbingan tidak ditemukan'
                ], 404);
            }

            // Prepare event data
            $description = "Bimbingan {$usulan->jenis_bimbingan}\n" .
                    "Dosen: {$usulan->dosen_nama}\n" .
                    "Lokasi: {$usulan->lokasi}\n" .
                    "Status: Menunggu Persetujuan\n\n" .
                    ($usulan->deskripsi ? "Catatan: {$usulan->deskripsi}" : "");

            $eventData = [
                'summary' => "Bimbingan dengan {$usulan->dosen_nama}",
                'description' => $description,
                'start' => Carbon::parse($usulan->tanggal . ' ' . $usulan->waktu_mulai),
                'end' => Carbon::parse($usulan->tanggal . ' ' . $usulan->waktu_selesai),
                'attendees' => [
                    ['email' => $usulan->dosen_email]
                ],
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 24 * 60],
                        ['method' => 'popup', 'minutes' => 30],
                    ],
                ],
            ];

            // Create Google Calendar event
            DB::beginTransaction();
            try {
                // Create event in Google Calendar
                $createdEvent = $this->googleCalendarController->createEvent($eventData);
                
                // Update student_event_id in database
                DB::table('usulan_bimbingans')
                    ->where('id', $usulanId)
                    ->update([
                        'student_event_id' => $createdEvent->id,
                        'updated_at' => now()
                    ]);

                DB::commit();
                Log::info('Successfully created Google Calendar event for usulan ID: ' . $usulanId, [
                    'event_id' => $createdEvent->id
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Event Google Calendar berhasil dibuat',
                    'data' => [
                        'event_id' => $createdEvent->id
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to create Google Calendar event: ' . $e->getMessage(), [
                    'usulan_id' => $usulanId
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error in createGoogleCalendarEvent: ' . $e->getMessage(), [
                'usulan_id' => $usulanId
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat event di Google Calendar'
            ], 500);
        }
    }
}