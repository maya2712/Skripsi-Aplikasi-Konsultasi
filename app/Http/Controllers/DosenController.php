<?php

namespace App\Http\Controllers;

use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\JadwalBimbingan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    protected $googleCalendarController;

    public function __construct(GoogleCalendarController $googleCalendarController)
    {   
        $this->googleCalendarController = $googleCalendarController;
    }

    /**
     * Menampilkan halaman masukkan jadwal
     */
    public function index()
    {
        $dosen = Auth::guard('dosen')->user();
        $isConnected = $dosen->hasGoogleCalendarConnected() && !$dosen->isGoogleTokenExpired();
        
        return view('bimbingan.dosen.masukkanjadwal', [
            'isConnected' => $isConnected,
            'email' => $dosen->email
        ]);
    }

        /**
         * Menyimpan jadwal baru
         */
    public function store(Request $request)
    {
        try {
            if (!$this->googleCalendarController->validateAndRefreshToken()) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            Log::info('Incoming request data:', $request->all());

            // Validasi request
            $validated = $request->validate([
                'start' => 'required|date',
                'end' => 'required|date|after:start',
                'description' => 'nullable|string',
                'capacity' => 'required|integer|min:1|max:10'
            ]);

            // Parse dates with explicit timezone
            $start = Carbon::parse($request->start)->setTimezone('Asia/Jakarta');
            $end = Carbon::parse($request->end)->setTimezone('Asia/Jakarta');

            $dosen = Auth::guard('dosen')->user();
            
            // Buat event di Google Calendar
            $description = "Status: Tersedia\n" .
                    "Dosen: {$dosen->nama}\n" .
                    "NIP: {$dosen->nip}\n" .
                    "Email: {$dosen->email}\n" .
                    "Kapasitas: {$request->capacity} Mahasiswa\n\n" .
                    ($request->description ? "Catatan: {$request->description}" : "");

            $eventData = [
                'summary' => 'Jadwal Bimbingan',
                'description' => $description,
                'start' => $start,
                'end' => $end,
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
            $jadwal = JadwalBimbingan::create([
                'event_id' => $createdEvent->id,
                'nip' => $dosen->nip,
                'waktu_mulai' => $start,
                'waktu_selesai' => $end,
                'catatan' => $request->description,
                'status' => 'tersedia',
                'kapasitas' => $request->capacity,
                'sisa_kapasitas' => $request->capacity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil ditambahkan!',
                'data' => [
                    'id' => $createdEvent->id,
                    'title' => 'Jadwal Bimbingan',
                    'start' => $start->toIso8601String(),
                    'end' => $end->toIso8601String(),
                    'description' => $request->description,
                    'status' => 'Tersedia',
                    'capacity' => $request->capacity
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus jadwal
     */
    public function destroy($eventId)
    {
        try {
            if (!$this->googleCalendarController-> validateAndRefreshToken()) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            $jadwal = JadwalBimbingan::where('event_id', $eventId)->first();
            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            DB::beginTransaction();
            try {
                // Hapus dari Google Calendar dulu
                $this->googleCalendarController->deleteEvent($eventId);
                
                // Jika berhasil hapus dari Google Calendar, hapus dari database
                $jadwal->delete();

                // Commit transaction jika kedua operasi berhasil
                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Jadwal berhasil dihapus dari sistem dan Google Calendar!'
                    ]);

            } catch (\Exception $e) {
                // Rollback jika ada yang gagal
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error deleting schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}