<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Pesan;
use App\Models\BalasanPesan;
use App\Models\PesanSematan;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Carbon\Carbon;

class PesanDosenController extends Controller
{
    /**
     * Menampilkan dashboard pesan dosen
     */
    public function index(Request $request)
    {
        $dosen = Auth::user();
        
        // Cek langsung dari data user
        $isKaprodi = (stripos($dosen->jabatan_fungsional, 'kaprodi') !== false || 
                    stripos($dosen->jabatan_fungsional, 'ketua program') !== false ||
                    stripos($dosen->jabatan_fungsional, 'kepala program') !== false);
        
        // Tentukan role aktif berdasarkan session dengan fallback ke 'dosen'
        $activeRole = session('active_role', 'dosen');
        
        // Validasi role - jika meminta role 'kaprodi' tapi bukan kaprodi, kembalikan ke 'dosen'
        if ($activeRole === 'kaprodi' && !$isKaprodi) {
            $activeRole = 'dosen';
            session(['active_role' => 'dosen']);
        }
        
        // Simpan ke session untuk konsistensi
        session(['is_kaprodi' => $isKaprodi]);
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        // Mengambil pesan berdasarkan peran aktif dengan eager loading untuk mahasiswa
        $query = Pesan::with(['mahasiswaPengirim', 'mahasiswaPenerima'])
            ->where(function($query) use ($dosen, $activeRole) {
                // Sebagai penerima dengan peran yang aktif
                $query->where(function($q) use ($dosen, $activeRole) {
                    $q->where('nip_penerima', $dosen->nip)
                    ->where('penerima_role', $activeRole);
                })
                ->orWhere(function($q) use ($dosen, $activeRole) {
                    // Sebagai pengirim dengan peran yang aktif
                    $q->where('nip_pengirim', $dosen->nip)
                    ->where('pengirim_role', $activeRole);
                });
            })
            ->where('status', 'Aktif'); // Hanya tampilkan pesan aktif di dashboard
        
        // Gabungkan dengan subquery balasan terakhir    
        $pesan = $query->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                $join->on('pesan.id', '=', 'latest_replies.id_pesan');
            })
            ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
            ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
            ->get();
        
        // Hitung pesan utama yang belum dibaca
        $belumDibacaUtama = Pesan::where('nip_penerima', $dosen->nip)
                        ->where('penerima_role', $activeRole)
                        ->where('dibaca', false)
                        ->count();
        
        // Hitung balasan dari mahasiswa yang belum dibaca
        $belumDibacaBalasan = BalasanPesan::whereIn('id_pesan', function($query) use ($dosen, $activeRole) {
                        $query->select('id')
                              ->from('pesan')
                              ->where(function($q) use ($dosen, $activeRole) {
                                  $q->where(function($innerQ) use ($dosen, $activeRole) {
                                      $innerQ->where('nip_penerima', $dosen->nip)
                                             ->where('penerima_role', $activeRole);
                                  })
                                  ->orWhere(function($innerQ) use ($dosen, $activeRole) {
                                      $innerQ->where('nip_pengirim', $dosen->nip)
                                             ->where('pengirim_role', $activeRole);
                                  });
                              })
                              ->where('status', 'Aktif');
                    })
                    ->where('tipe_pengirim', 'mahasiswa') // Khusus balasan dari mahasiswa
                    ->where('dibaca', false)
                    ->count();
        
        // Total pesan belum dibaca (pesan utama + balasan)
        $belumDibaca = $belumDibacaUtama + $belumDibacaBalasan;
        
        // Menghitung jumlah pesan aktif (baik yang diterima dengan role sesuai maupun dikirim)
        $pesanAktif = Pesan::where(function($query) use ($dosen, $activeRole) {
                    $query->where(function($q) use ($dosen, $activeRole) {
                        $q->where('nip_penerima', $dosen->nip)
                        ->where('penerima_role', $activeRole);
                    })
                    ->orWhere(function($q) use ($dosen, $activeRole) {
                        $q->where('nip_pengirim', $dosen->nip)
                         ->where('pengirim_role', $activeRole);
                    });
                })
                ->where('status', 'Aktif')
                ->count();
        
        // Menghitung total pesan (termasuk aktif dan berakhir, dengan role yang sesuai)
        $totalPesan = Pesan::where(function($query) use ($dosen, $activeRole) {
                    $query->where(function($q) use ($dosen, $activeRole) {
                        $q->where('nip_penerima', $dosen->nip)
                        ->where('penerima_role', $activeRole);
                    })
                    ->orWhere(function($q) use ($dosen, $activeRole) {
                        $q->where('nip_pengirim', $dosen->nip)
                         ->where('pengirim_role', $activeRole);
                    });
                })
                ->count();
        
        // Saat rendering view, tambahkan informasi peran aktif dan status kaprodi
        return view('pesan.dosen.dashboardpesandosen', compact('pesan', 'belumDibaca', 'pesanAktif', 'totalPesan', 'activeRole', 'isKaprodi'));
    }
    
    /**
     * Menampilkan form buat pesan baru (dosen ke mahasiswa)
     */
    public function create()
    {
        // Ambil daftar mahasiswa untuk dipilih sebagai penerima
        $mahasiswa = Mahasiswa::all();
        
        return view('pesan.dosen.buatpesandosen', compact('mahasiswa'));
    }
    
    /**
     * Menyimpan pesan baru dari dosen ke mahasiswa (bisa lebih dari satu)
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'subjek' => 'required',
            'anggota' => 'required|array',
            'anggota.*' => 'exists:mahasiswas,nim',
            'prioritas' => 'required|in:Penting,Umum',
            'pesanText' => 'required'
        ]);
        
        try {
            // Ambil dosen yang sedang login
            $dosen = Auth::user();
            $activeRole = session('active_role', 'dosen'); // Ambil peran aktif
            
            // Tambahkan log untuk debugging
            Log::info('Mengirim pesan dari dosen', [
                'nip_dosen' => $dosen->nip,
                'nama_dosen' => $dosen->nama,
                'peran_aktif' => $activeRole, // Log peran aktif
                'jumlah_penerima' => count($request->anggota)
            ]);
            
            // Array untuk menyimpan ID pesan yang dibuat
            $pesanIds = [];
            
            // Kirim pesan ke setiap mahasiswa yang dipilih
            foreach($request->anggota as $nim) {
                $pesan = new Pesan();
                $pesan->subjek = $request->subjek;
                $pesan->nip_pengirim = $dosen->nip;
                $pesan->pengirim_role = $activeRole; // Simpan peran pengirim
                $pesan->nim_penerima = $nim;
                $pesan->isi_pesan = $request->pesanText;
                $pesan->prioritas = $request->prioritas;
                $pesan->lampiran = $request->has('lampiran') ? $request->lampiran : null;
                $pesan->status = 'Aktif';
                $pesan->dibaca = false;
                $pesan->bookmarked = false; // Pastikan nilai default untuk bookmark
                
                $pesan->save();
                
                // Log pesan yang berhasil disimpan
                Log::info('Pesan berhasil dibuat', [
                    'id' => $pesan->id,
                    'nip_pengirim' => $pesan->nip_pengirim,
                    'pengirim_role' => $pesan->pengirim_role, // Log peran pengirim
                    'nim_penerima' => $pesan->nim_penerima
                ]);
                
                $pesanIds[] = $pesan->id;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim ke ' . count($request->anggota) . ' mahasiswa',
                'pesan_ids' => $pesanIds
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengirim pesan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail pesan - PERBAIKAN
     */
    public function show($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $dosen = Auth::user();
            $activeRole = session('active_role', 'dosen');
            
            // Pastikan dosen yang melihat adalah penerima ATAU pengirim pesan
            if (($pesan->nip_penerima == $dosen->nip && $pesan->penerima_role == $activeRole) || 
                ($pesan->nip_pengirim == $dosen->nip && $pesan->pengirim_role == $activeRole)) {
                // Lanjutkan ke proses berikutnya
            } else {
                return redirect()->route('dosen.dashboard.pesan')
                    ->with('error', 'Anda tidak memiliki akses ke pesan ini');
            }
            
            // PERBAIKAN: Load relasi balasan dengan status is_pinned dari database
            $balasan = BalasanPesan::where('id_pesan', $pesan->id)
                ->select(['id', 'id_pesan', 'pengirim_id', 'tipe_pengirim', 'isi_balasan', 'lampiran', 'dibaca', 'is_pinned', 'created_at', 'updated_at'])
                ->get();
            
            $pesan->setRelation('balasan', $balasan);
            
            // PERBAIKAN: Refresh pesan untuk memastikan data terbaru dari database
            $pesan->refresh();
            
            // Log untuk debugging
            Log::info('Pesan ID: ' . $pesan->id . ', is_pinned from DB: ' . ($pesan->is_pinned ? 'true' : 'false'));
            
            // Cek balasan yang belum dibaca
            $unreadBalasan = $balasan->filter(function($item) {
                return !$item->dibaca && $item->tipe_pengirim == 'mahasiswa';
            })->count();
            
            // Load semua pengirim balasan (dosen dan mahasiswa) secara manual
            foreach ($pesan->balasan as $balasan) {
                // Log status is_pinned untuk setiap balasan
                Log::info('Balasan ID: ' . $balasan->id . ', is_pinned from DB: ' . ($balasan->is_pinned ? 'true' : 'false'));
                
                if ($balasan->tipe_pengirim == 'dosen') {
                    $dosenPengirim = Dosen::find($balasan->pengirim_id);
                    $balasan->pengirimData = $dosenPengirim;
                } else {
                    $mahasiswaPengirim = Mahasiswa::find($balasan->pengirim_id);
                    $balasan->pengirimData = $mahasiswaPengirim;
                }
            }
            
            // Kelompokkan balasan berdasarkan tanggal
            $balasanByDate = [];
            
            // Tambahkan pesan awal ke grup balasan berdasarkan tanggal
            $dateAwal = Carbon::parse($pesan->created_at)->format('Y-m-d');
            $balasanByDate[$dateAwal][] = $pesan;
            
            // Tambahkan semua balasan ke grup berdasarkan tanggal
            foreach ($pesan->balasan as $balasan) {
                $date = Carbon::parse($balasan->created_at)->format('Y-m-d');
                if (!isset($balasanByDate[$date])) {
                    $balasanByDate[$date] = [];
                }
                $balasanByDate[$date][] = $balasan;
            }
            
            // Urutkan tanggal (dari paling lama)
            ksort($balasanByDate);
            
            // Update status menjadi dibaca jika belum dibaca dan dosen adalah penerima
            if (!$pesan->dibaca && $pesan->nip_penerima == $dosen->nip) {
                $pesan->dibaca = true;
                $pesan->save();
                
                Log::info('Marked message as read: ' . $pesan->id);
            }
            
            // Update status balasan yang belum dibaca (hanya balasan dari mahasiswa)
            $updatedCount = BalasanPesan::where('id_pesan', $pesan->id)
                ->where('tipe_pengirim', 'mahasiswa')
                ->where('dibaca', false)
                ->update(['dibaca' => true]);
                
            Log::info('Updated ' . $updatedCount . ' unread replies to read');
            
            // PERBAIKAN: Pastikan status is_pinned sudah benar dari database (tidak perlu query lagi)
            // Cast ke boolean untuk memastikan konsistensi
            $pesan->is_pinned = (bool) $pesan->is_pinned;
            
            foreach ($pesan->balasan as $balasan) {
                $balasan->is_pinned = (bool) $balasan->is_pinned;
            }
            
            return view('pesan.dosen.isipesandosen', compact('pesan', 'balasanByDate'));
        } catch (\Exception $e) {
            Log::error('Error menampilkan detail pesan: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            
            return redirect()->route('dosen.dashboard.pesan')
                ->with('error', 'Terjadi kesalahan saat menampilkan detail pesan');
        }
    }
    
    
    /**
     * Mengirim balasan pesan dengan dukungan lampiran
     */
    public function reply(Request $request, $id)
    {
        // PERBAIKAN: Validasi - balasan tidak wajib jika ada lampiran
        $request->validate([
            'balasan' => 'required_without:lampiran|string', // Wajib jika tidak ada lampiran
            'lampiran' => 'nullable|url'
        ]);
        
        $pesan = Pesan::findOrFail($id);
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen');
        
        // Validasi akses...
        if (($pesan->nip_penerima == $dosen->nip && $pesan->penerima_role == $activeRole) || $pesan->nip_pengirim == $dosen->nip) {
            // Lanjutkan proses
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pesan ini'
            ], 403);
        }
        
        if ($pesan->status == 'Berakhir') {
            return response()->json([
                'success' => false,
                'message' => 'Pesan telah diakhiri'
            ], 400);
        }
        
        try {
            $balasan = new BalasanPesan();
            $balasan->id_pesan = $id;
            $balasan->pengirim_id = $dosen->nip;
            $balasan->tipe_pengirim = 'dosen';
            
            // PERBAIKAN: Set balasan default jika kosong tapi ada lampiran
            $balasan->isi_balasan = $request->balasan ?: '[Lampiran]';
            $balasan->lampiran = $request->lampiran;
            $balasan->dibaca = false;

            $balasan->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dikirim',
                'data' => [
                    'id' => $balasan->id,
                    'isi_balasan' => $balasan->isi_balasan,
                    'lampiran' => $balasan->lampiran,
                    'created_at' => $balasan->formattedCreatedAt()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengirim balasan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim balasan: ' . $e->getMessage()
            ], 500);
        }
    }
        
    /**
     * Mengakhiri pesan (dosen juga dapat mengakhiri pesan jika dia adalah pengirim)
     */
    public function endChat($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $dosen = Auth::user();
            $activeRole = session('active_role', 'dosen');
            
            // PERUBAHAN: Pastikan dosen yang mengakhiri adalah PENGIRIM pesan
            if ($pesan->nip_pengirim != $dosen->nip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengakhiri pesan ini. Hanya pengirim yang dapat mengakhiri pesan.'
                ], 403);
            }
            
            // Pastikan pesan masih aktif
            if ($pesan->status == 'Berakhir') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesan sudah diakhiri sebelumnya'
                ], 400);
            }
            
            // Update status pesan
            $pesan->status = 'Berakhir';
            $pesan->save();
            
            // Log informasi
            Log::info('Pesan diakhiri oleh dosen', [
                'id_pesan' => $pesan->id,
                'nip_dosen' => $dosen->nip
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil diakhiri'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengakhiri pesan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengakhiri pesan: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Fungsi untuk membookmark pesan
     */
    public function bookmark(Request $request, $id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $dosen = Auth::user();
            $activeRole = session('active_role', 'dosen');
            
            // Pastikan dosen yang membookmark adalah penerima ATAU pengirim pesan
            // Dan jika sebagai penerima, hanya dapat membookmark pesan untuk role yang aktif
            if (($pesan->nip_penerima == $dosen->nip && $pesan->penerima_role == $activeRole) || $pesan->nip_pengirim == $dosen->nip) {
                // Lanjutkan proses
            } else {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesan ini');
            }
            
            // Toggle status bookmark
            $pesan->bookmarked = !$pesan->bookmarked;
            $pesan->save();
            
            return redirect()->back()->with('success', 
                $pesan->bookmarked ? 'Pesan berhasil ditandai' : 'Tanda pada pesan berhasil dihapus');
            
        } catch (\Exception $e) {
            Log::error('Error saat bookmark pesan: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Gagal menandai pesan');
        }
    }
    
    /**
     * Menampilkan riwayat pesan
     */
    public function history()
    {
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen');
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        // Mengambil semua pesan dengan status 'Berakhir' yang diterima ATAU dikirim oleh dosen
        // dengan peran yang aktif
        $riwayatPesan = Pesan::with(['mahasiswaPengirim', 'mahasiswaPenerima'])
                        ->where(function($query) use ($dosen, $activeRole) {
                            $query->where(function($q) use ($dosen, $activeRole) {
                                $q->where('nip_penerima', $dosen->nip)
                                    ->where('penerima_role', $activeRole);
                            })
                            ->orWhere(function($q) use ($dosen, $activeRole) {
                                // PERUBAHAN: Sebagai pengirim, hanya tampilkan pesan dengan peran yang aktif
                                $q->where('nip_pengirim', $dosen->nip)
                                ->where('pengirim_role', $activeRole);
                            });
                        })
                        ->where('status', 'Berakhir')
                        ->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                                $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                        })
                        ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.updated_at) as last_activity'))
                        ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
                        ->get();
                            
        return view('pesan.dosen.riwayatpesandosen', compact('riwayatPesan'));
    }
    
    /**
     * Filter pesan berdasarkan prioritas
     */
    public function filter(Request $request)
    {
        $dosen = Auth::user();
        $filter = $request->filter;
        $activeRole = session('active_role', 'dosen');
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        $query = Pesan::where(function($query) use ($dosen, $activeRole) {
                    $query->where(function($q) use ($dosen, $activeRole) {
                        $q->where('nip_penerima', $dosen->nip)
                          ->where('penerima_role', $activeRole);
                    })
                    ->orWhere(function($q) use ($dosen, $activeRole) {
                        // PERUBAHAN: Sebagai pengirim, hanya tampilkan pesan dengan peran yang aktif
                        $q->where('nip_pengirim', $dosen->nip)
                          ->where('pengirim_role', $activeRole);
                    });
                });
        
        // Filter berdasarkan prioritas
        if ($filter == 'penting') {
            $query->where('prioritas', 'Penting');
        } elseif ($filter == 'umum') {
            $query->where('prioritas', 'Umum');
        }
        
        // Filter hanya pesan aktif untuk dashboard utama
        if (!$request->has('include_ended') || !$request->include_ended) {
            $query->where('status', 'Aktif');
        }
        
        // Gabungkan dengan subquery balasan terakhir
        $pesan = $query->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                    $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                })
                ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
                ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
                ->get();
        
        return response()->json([
            'success' => true,
            'html' => view('pesan.dosen.partials.pesan_list', compact('pesan'))->render()
        ]);
    }
    
    /**
     * Pencarian pesan
     */
    public function search(Request $request)
    {
        $dosen = Auth::user();
        $keyword = $request->keyword;
        $activeRole = session('active_role', 'dosen');
        
        // Membuat subquery untuk mendapatkan waktu balasan terakhir
        $latestReplies = BalasanPesan::selectRaw('id_pesan, MAX(created_at) as latest_reply_at')
            ->groupBy('id_pesan');
        
        $query = Pesan::where(function($query) use ($dosen, $activeRole) {
                    $query->where(function($q) use ($dosen, $activeRole) {
                        $q->where('nip_penerima', $dosen->nip)
                          ->where('penerima_role', $activeRole);
                    })
                    ->orWhere(function($q) use ($dosen, $activeRole) {
                        // PERUBAHAN: Sebagai pengirim, hanya tampilkan pesan dengan peran yang aktif
                        $q->where('nip_pengirim', $dosen->nip)
                          ->where('pengirim_role', $activeRole);
                    });
                })
                ->where(function($query) use ($keyword) {
                    $query->where('subjek', 'like', "%{$keyword}%")
                          ->orWhere('isi_pesan', 'like', "%{$keyword}%")
                          ->orWhereHas('pengirim', function($q) use ($keyword) {
                              $q->where('nama', 'like', "%{$keyword}%");
                          })
                          ->orWhereHas('penerima', function($q) use ($keyword) {
                              $q->where('nama', 'like', "%{$keyword}%");
                          });
                });
                
        // Filter berdasarkan status jika parameter diberikan
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Default hanya tampilkan pesan aktif
            $query->where('status', 'Aktif');
        }
        
        // Gabungkan dengan subquery balasan terakhir
        $pesan = $query->leftJoinSub($latestReplies, 'latest_replies', function ($join) {
                    $join->on('pesan.id', '=', 'latest_replies.id_pesan');
                })
                ->select('pesan.*', DB::raw('IFNULL(latest_replies.latest_reply_at, pesan.created_at) as last_activity'))
                ->orderBy('last_activity', 'desc') // Urutkan berdasarkan aktivitas terakhir
                ->get();
        
        return response()->json([
            'success' => true,
            'html' => view('pesan.dosen.partials.pesan_list', compact('pesan'))->render()
        ]);
    }
    
    /**
     * Halaman FAQ untuk dosen
     */
    public function faq()
    {
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen');
        
        Log::info('Dosen role in faq: ' . $activeRole);
        
        // Ambil sematan yang masih aktif dengan eager loading relasi dosen
        $sematan = PesanSematan::with('dosen')
                        ->where('aktif', true)
                        ->where('durasi_sematan', '>', now())
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // Debug info
        foreach ($sematan as $item) {
            Log::info('Sematan ID: ' . $item->id . ', NIP: ' . $item->nip_dosen . ', Role: ' . $item->dosen_role);
        }
        
        // Kelompokkan sematan berdasarkan kategori
        $sematanByKategori = [
            'krs' => [],
            'kp' => [],
            'skripsi' => [],
            'mbkm' => []
        ];
        
        foreach ($sematan as $item) {
            $sematanByKategori[$item->kategori][] = $item;
            
            // Set flag dapat dibatalkan hanya jika dosen sama dan role aktif sama
            $canCancel = ($item->nip_dosen == $dosen->nip && $item->dosen_role == $activeRole);
            $item->can_cancel = $canCancel;
            
            Log::info('Sematan ID: ' . $item->id . ', can_cancel: ' . ($canCancel ? 'true' : 'false'));
        }
        
        // Ekstrak daftar dosen dari sematan dan hilangkan duplikat
        $dosenList = $sematan->map(function($item) {
            return $item->dosen;
        })->unique('nip')->sortBy('nama')->values();
        
        return view('pesan.dosen.faq_dosen', [
            'sematan' => $sematan,
            'sematanByKategori' => $sematanByKategori,
            'dosenList' => $dosenList
        ]);
    }
    
    /**
     * Menyematkan pesan atau balasan ke FAQ
     */
    public function sematkan(Request $request, $id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $dosen = Auth::guard('dosen')->user();
            $activeRole = session('active_role', 'dosen');
            
            // Validasi akses dosen terhadap pesan
            if (!$this->validateDosenAccess($pesan, $dosen, $activeRole)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke pesan ini'
                ], 403);
            }
            
            // Validasi input request
            $validatedData = $request->validate([
                'message_ids' => 'required|array|min:1',
                'message_ids.*' => 'required|string',
                'durasi' => 'required|integer|min:1|max:1000'
            ]);
            
            $messageIds = $validatedData['message_ids'];
            $durasi = (int) $validatedData['durasi'];
            
            // PERBAIKAN: Proses dan validasi pesan yang dipilih
            $processedData = $this->processMessageIds($messageIds, $pesan, $dosen);
            
            // PERBAIKAN: Validasi harus ada pesan dari mahasiswa untuk judul
            if (empty($processedData['pesanMahasiswa'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimal pilih satu pesan atau balasan dari mahasiswa untuk dijadikan judul FAQ'
                ], 400);
            }
            
            // PERBAIKAN: Validasi harus ada pesan dari dosen untuk isinya
            if (empty($processedData['pesanDosen'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimal pilih satu pesan atau balasan dari dosen untuk dijadikan isi sematan'
                ], 400);
            }
            
            // PERBAIKAN: Generate data sematan dengan logic yang diperbaiki
            $sematanData = $this->generateSematanDataFixed(
                $processedData['pesanMahasiswa'],
                $processedData['pesanDosen'],
                $dosen,
                $activeRole,
                $durasi
            );
            
            // Simpan sematan
            $sematan = $this->saveSematan($sematanData);
            
            // PERBAIKAN: Update status pesan yang disematkan di database
            $this->updatePesanStatusSematanFixed($messageIds, $pesan->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil disematkan dengan judul: "' . $sematanData['judul'] . '"',
                'data' => [
                    'id' => $sematan->id,
                    'judul' => $sematan->judul,
                    'kategori' => $sematan->kategori,
                    'durasi_jam' => $durasi,
                    'pinned_messages' => $messageIds // TAMBAHAN: untuk update UI
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saat menyematkan pesan: ' . $e->getMessage(), [
                'pesan_id' => $id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyematkan pesan'
            ], 500);
        }
    }

    /**
     * PERBAIKAN: Generate data untuk sematan dengan logic yang diperbaiki
     */
    private function generateSematanDataFixed($pesanMahasiswa, $pesanDosen, $dosen, $activeRole, $durasi)
    {
        // Tentukan kategori berdasarkan subjek pesan utama
        $pesan = Pesan::find(request()->route('id'));
        $kategori = $this->determineKategoriFromSubjek($pesan->subjek);
        
        // PERBAIKAN: Generate judul dari pesan mahasiswa pertama
        $judulPesan = $pesanMahasiswa[0];
        if ($judulPesan instanceof \App\Models\Pesan) {
            $judul = $this->generateJudulFromPesan($judulPesan->isi_pesan);
        } else {
            // Ini adalah BalasanPesan
            $judul = $this->generateJudulFromPesan($judulPesan->isi_balasan);
        }
        
        // PERBAIKAN: Gabungkan semua pesan dari dosen untuk isi
        $isiSematan = $this->gabungkanPesanDosen($pesanDosen);
        
        // Cari lampiran dari pesan dosen
        $lampiran = $this->getFirstAttachmentFromPesanDosen($pesanDosen);
        
        return [
            'nip_dosen' => $dosen->nip,
            'dosen_role' => $activeRole,
            'jenis_pesan' => 'pesan',
            'pesan_id' => $pesan->id,
            'isi_sematan' => $isiSematan,
            'kategori' => $kategori,
            'judul' => $judul,
            'aktif' => true,
            'durasi_sematan' => now()->addHours($durasi),
            'lampiran' => $lampiran
        ];
    }

    /**
     * PERBAIKAN: Method helper untuk menggabungkan pesan dosen
     */
    private function gabungkanPesanDosen($pesanDosen)
    {
        if (empty($pesanDosen)) {
            return 'Tidak ada pesan dosen yang dipilih';
        }
        
        $gabunganPesan = [];
        
        // Urutkan pesan berdasarkan waktu
        usort($pesanDosen, function($a, $b) {
            return $a->created_at <=> $b->created_at;
        });
        
        foreach ($pesanDosen as $index => $pesan) {
            $prefix = '';
            
            // Tambahkan prefix jika ada lebih dari satu pesan
            if (count($pesanDosen) > 1) {
                $prefix = "Jawaban " . ($index + 1) . ":\n";
            }
            
            // Ambil isi pesan tergantung tipe
            if ($pesan instanceof \App\Models\Pesan) {
                $isiPesan = trim($pesan->isi_pesan);
            } else {
                // Ini adalah BalasanPesan
                $isiPesan = trim($pesan->isi_balasan);
            }
            
            $gabunganPesan[] = $prefix . $isiPesan;
        }
        
        return implode("\n\n" . str_repeat('-', 50) . "\n\n", $gabunganPesan);
    }

    /**
     * PERBAIKAN: Method helper untuk mendapatkan lampiran dari pesan dosen
     */
    private function getFirstAttachmentFromPesanDosen($pesanDosen)
    {
        foreach ($pesanDosen as $pesan) {
            $lampiran = null;
            
            if ($pesan instanceof \App\Models\Pesan) {
                $lampiran = $pesan->lampiran;
            } else {
                // Ini adalah BalasanPesan
                $lampiran = $pesan->lampiran;
            }
            
            if (!empty($lampiran) && filter_var($lampiran, FILTER_VALIDATE_URL)) {
                return $lampiran;
            }
        }
        return null;
    }

    /**
     * PERBAIKAN: Method helper untuk update status sematan di database
     */
    private function updatePesanStatusSematanFixed($messageIds, $pesanId)
    {
        DB::transaction(function() use ($messageIds, $pesanId) {
            foreach ($messageIds as $messageId) {
                if (str_starts_with($messageId, 'message-')) {
                    $actualId = str_replace('message-', '', $messageId);
                    if ($actualId == $pesanId) {
                        // Update pesan utama
                        DB::table('pesan')
                            ->where('id', $pesanId)
                            ->update([
                                'is_pinned' => true,
                                'updated_at' => now()
                            ]);
                        
                        Log::info('Updated pesan is_pinned status', ['pesan_id' => $pesanId]);
                    }
                } elseif (str_starts_with($messageId, 'reply-')) {
                    $replyId = str_replace('reply-', '', $messageId);
                    
                    // Update balasan
                    DB::table('balasan_pesan')
                        ->where('id', $replyId)
                        ->update([
                            'is_pinned' => true,
                            'updated_at' => now()
                        ]);
                    
                    Log::info('Updated balasan is_pinned status', ['balasan_id' => $replyId]);
                }
            }
        });
    }

    /**
     * Validasi akses dosen terhadap pesan
     */
    private function validateDosenAccess($pesan, $dosen, $activeRole)
    {
        return ($pesan->nip_penerima == $dosen->nip && $pesan->penerima_role == $activeRole) || 
            ($pesan->nip_pengirim == $dosen->nip && $pesan->pengirim_role == $activeRole);
    }

    /**
     * Proses message IDs untuk memisahkan pesan mahasiswa dan balasan dosen
     */
    private function processMessageIds($messageIds, $pesan, $dosen)
    {
        $pesanMahasiswa = []; // Ubah jadi array untuk menampung multiple pesan
        $pesanDosen = [];     // Array untuk pesan dari dosen
        
        foreach ($messageIds as $messageId) {
            if (str_starts_with($messageId, 'message-')) {
                // Proses pesan utama
                $actualId = str_replace('message-', '', $messageId);
                if ($actualId == $pesan->id) {
                    // PERBAIKAN: Cek apakah pesan dari mahasiswa atau dosen
                    if (!empty($pesan->nim_pengirim)) {
                        // Pesan dari mahasiswa
                        $pesanMahasiswa[] = $pesan;
                    } elseif (!empty($pesan->nip_pengirim)) {
                        // Pesan dari dosen
                        $pesanDosen[] = $pesan;
                    }
                }
            } elseif (str_starts_with($messageId, 'reply-')) {
                // Proses balasan
                $replyId = str_replace('reply-', '', $messageId);
                $balasan = BalasanPesan::find($replyId);
                
                if ($balasan) {
                    // PERBAIKAN: Cek apakah balasan dari mahasiswa atau dosen
                    if ($balasan->tipe_pengirim == 'mahasiswa') {
                        $pesanMahasiswa[] = $balasan;
                    } elseif ($balasan->tipe_pengirim == 'dosen') {
                        $pesanDosen[] = $balasan;
                    }
                }
            }
        }
        
        return [
            'pesanMahasiswa' => $pesanMahasiswa,
            'pesanDosen' => $pesanDosen  // Ubah nama dari 'balasanDosen' ke 'pesanDosen'
        ];
    }

    /**
     * Validasi balasan untuk sematan
     */
    private function validateBalasanForSematan($balasan, $dosen)
    {
        // Pastikan balasan dari dosen dan dari dosen yang sedang login
        return $balasan->tipe_pengirim == 'dosen' && $balasan->pengirim_id == $dosen->nip;
    }

    /**
     * Generate data untuk sematan
     */
    private function generateSematanData($pesanMahasiswa, $balasanDosen, $dosen, $activeRole, $durasi)
    {
        // Tentukan kategori berdasarkan subjek pesan
        $kategori = $this->determineKategoriFromSubjek($pesanMahasiswa->subjek);
        
        // Generate judul dari pesan mahasiswa
        $judul = $this->generateJudulFromPesan($pesanMahasiswa->isi_pesan);
        
        // Gabungkan semua balasan dosen
        $isiSematan = $this->gabungkanBalasanDosen($balasanDosen);
        
        // Cari lampiran dari balasan dosen
        $lampiran = $this->getFirstAttachmentFromBalasan($balasanDosen);
        
        return [
            'nip_dosen' => $dosen->nip,
            'dosen_role' => $activeRole,
            'jenis_pesan' => 'pesan',
            'pesan_id' => $pesanMahasiswa->id,
            'isi_sematan' => $isiSematan,
            'kategori' => $kategori,
            'judul' => $judul,
            'aktif' => true,
            'durasi_sematan' => now()->addHours($durasi),
            'lampiran' => $lampiran
        ];
    }

    /**
     * Simpan sematan ke database
     */
    private function saveSematan($sematanData)
    {
        return PesanSematan::create($sematanData);
    }

    // Method helper untuk menentukan kategori otomatis (diperbaiki)
    private function determineKategoriFromSubjek($subjek)
    {
        $subjekLower = strtolower(trim($subjek));
        
        // Mapping kata kunci ke kategori
        $kategoriMapping = [
            'krs' => ['krs', 'kartu rencana studi', 'pengisian krs', 'mata kuliah'],
            'kp' => ['kp', 'kerja praktik', 'praktek kerja', 'magang'],
            'skripsi' => ['skripsi', 'tugas akhir', 'thesis', 'penelitian'],
            'mbkm' => ['mbkm', 'merdeka belajar', 'kampus merdeka', 'pertukaran mahasiswa']
        ];
        
        foreach ($kategoriMapping as $kategori => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($subjekLower, $keyword) !== false) {
                    return $kategori;
                }
            }
        }
        
        // Default kategori jika tidak ditemukan match
        return 'krs';
    }

    // Method helper untuk generate judul dari pesan mahasiswa (diperbaiki)
    private function generateJudulFromPesan($isiPesan)
    {
        $isiPesan = trim($isiPesan);
        
        if (empty($isiPesan)) {
            return 'Pesan Kosong';
        }
        
        // Bersihkan dari karakter yang tidak perlu
        $isiPesan = preg_replace('/\s+/', ' ', $isiPesan); // Normalize whitespace
        
        // Ambil maksimal 80 karakter untuk judul yang lebih informatif
        $maxLength = 80;
        $judul = substr($isiPesan, 0, $maxLength);
        
        // Cari tanda baca terakhir dalam batas karakter
        $punctuationMarks = ['.', '?', '!', ';'];
        $lastPunctuation = -1;
        
        foreach ($punctuationMarks as $mark) {
            $pos = strrpos($judul, $mark);
            if ($pos !== false && $pos > 30) { // Minimal 30 karakter sebelum tanda baca
                $lastPunctuation = max($lastPunctuation, $pos);
            }
        }
        
        if ($lastPunctuation > 0) {
            $judul = substr($judul, 0, $lastPunctuation + 1);
        } elseif (strlen($isiPesan) > $maxLength) {
            // Cari spasi terakhir untuk memotong di kata utuh
            $lastSpace = strrpos($judul, ' ');
            if ($lastSpace > 30) {
                $judul = substr($judul, 0, $lastSpace);
            }
            $judul .= '...';
        }
        
        return trim($judul);
    }

    // Method helper untuk menggabungkan balasan dosen (diperbaiki)
    private function gabungkanBalasanDosen($balasanDosen)
    {
        if (empty($balasanDosen)) {
            return 'Tidak ada balasan dosen yang dipilih';
        }
        
        $gabunganBalasan = [];
        
        // Urutkan balasan berdasarkan waktu
        usort($balasanDosen, function($a, $b) {
            return $a->created_at <=> $b->created_at;
        });
        
        foreach ($balasanDosen as $index => $balasan) {
            $prefix = '';
            
            // Tambahkan prefix jika ada lebih dari satu balasan
            if (count($balasanDosen) > 1) {
                $prefix = "Balasan " . ($index + 1) . ":\n";
            }
            
            $gabunganBalasan[] = $prefix . trim($balasan->isi_balasan);
        }
        
        return implode("\n\n" . str_repeat('-', 50) . "\n\n", $gabunganBalasan);
    }

    // Method helper untuk mendapatkan lampiran pertama dari balasan (diperbaiki)
    private function getFirstAttachmentFromBalasan($balasanDosen)
    {
        foreach ($balasanDosen as $balasan) {
            if (!empty($balasan->lampiran) && filter_var($balasan->lampiran, FILTER_VALIDATE_URL)) {
                return $balasan->lampiran;
            }
        }
        return null;
    }

    // Method helper untuk update status sematan (diperbaiki)
    private function updatePesanStatusSematan($messageIds, $pesanId)
    {
        DB::transaction(function() use ($messageIds, $pesanId) {
            foreach ($messageIds as $messageId) {
                if (str_starts_with($messageId, 'message-')) {
                    $actualId = str_replace('message-', '', $messageId);
                    if ($actualId == $pesanId) {
                        DB::table('pesan')
                            ->where('id', $pesanId)
                            ->update([
                                'is_pinned' => true,
                                'updated_at' => now()
                            ]);
                    }
                } elseif (str_starts_with($messageId, 'reply-')) {
                    $replyId = str_replace('reply-', '', $messageId);
                    DB::table('balasan_pesan')
                        ->where('id', $replyId)
                        ->update([
                            'is_pinned' => true,
                            'updated_at' => now()
                        ]);
                }
            }
        });
    }


    /**
     * Batalkan sematan pesan
     */
    public function batalkanSematan($id)
    {
        try {
            $dosen = Auth::user(); // Konsisten dengan method lain
            $activeRole = session('active_role', 'dosen');
            
            Log::info('Mencoba membatalkan sematan dengan ID: ' . $id);
            Log::info('Role aktif dosen: ' . $activeRole);
            
            // Cari sematan berdasarkan ID untuk debugging
            $sematanDebug = PesanSematan::find($id);
            if ($sematanDebug) {
                Log::info('Sematan ditemukan | ID: ' . $sematanDebug->id . 
                        ', NIP Dosen: ' . $sematanDebug->nip_dosen . 
                        ', Dosen Role: ' . $sematanDebug->dosen_role);
            } else {
                Log::error('Sematan dengan ID ' . $id . ' tidak ditemukan');
            }
            
            // Cari sematan yang sesuai dengan kondisi
            $sematan = PesanSematan::where('id', $id)
                            ->where('nip_dosen', $dosen->nip)
                            ->where('dosen_role', $activeRole)
                            ->first();
            
            if (!$sematan) {
                Log::error('Tidak ada hasil query untuk sematan dengan ID: ' . $id . 
                        ', NIP: ' . $dosen->nip . ', Role: ' . $activeRole);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat membatalkan sematan ini dengan peran yang aktif saat ini'
                ], 403);
            }
            
            DB::beginTransaction();
            
            try {
                // PERBAIKAN: Reset status is_pinned untuk pesan yang terkait
                if ($sematan->pesan_id) {
                    // Reset pesan utama jika ada
                    $pesan = Pesan::find($sematan->pesan_id);
                    if ($pesan && $pesan->is_pinned) {
                        $pesan->is_pinned = false;
                        $pesan->save();
                        Log::info('Reset status pinned untuk pesan utama ID: ' . $sematan->pesan_id);
                    }
                    
                    // Reset semua balasan yang di-pin untuk pesan ini
                    $updatedReplies = BalasanPesan::where('id_pesan', $sematan->pesan_id)
                        ->where('is_pinned', true)
                        ->update(['is_pinned' => false]);
                    
                    Log::info('Reset status pinned untuk ' . $updatedReplies . ' balasan');
                }
                
                // Nonaktifkan sematan (soft delete alternative)
                $sematan->aktif = false;
                $sematan->save();
                
                // Atau hapus permanen jika diinginkan
                // $sematan->delete();
                
                DB::commit();
                
                Log::info('Sematan berhasil dibatalkan: ' . $id);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sematan berhasil dibatalkan'
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error saat membatalkan sematan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan sematan'
            ], 500);
        }
    }

    /**
     * Mendapatkan daftar sematan untuk FAQ
     */
    public function getSematan(Request $request)
    {
        try {
            $dosen = Auth::user();
            
            // Filter berdasarkan kategori jika ada
            $kategori = $request->input('kategori');
            
            $query = PesanSematan::where('nip_dosen', $dosen->nip)
                                ->where('aktif', true)
                                ->where('durasi_sematan', '>', now());
            
            if ($kategori && $kategori !== 'all') {
                $query->where('kategori', $kategori);
            }
            
            $sematan = $query->orderBy('created_at', 'desc')
                             ->get();
            
            return response()->json([
                'success' => true,
                'data' => $sematan
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saat mengambil sematan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil sematan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Method untuk debugging
     * Gunakan method ini jika masih terjadi error pada pesan
     */
    public function debug($id)
    {
        $pesan = Pesan::findOrFail($id);
        $balasan = BalasanPesan::where('id_pesan', $id)->get();
        
        dd([
            'Pesan' => $pesan->toArray(),
            'Balasan' => $balasan->toArray(),
            'Penerima Exists' => $pesan->penerima ? true : false,
            'Penerima Role' => $pesan->penerima_role,
            'Balasan Count' => $balasan->count()
        ]);
    }
    
    /**
     * Method untuk debugging penghitungan pesan belum dibaca
     * Berguna untuk memecahkan masalah penghitungan notifikasi
     */
    public function debugUnreadCount()
    {
        $dosen = Auth::user();
        $activeRole = session('active_role', 'dosen');
        
        // Hitung pesan utama yang belum dibaca
        $belumDibacaUtama = Pesan::where('nip_penerima', $dosen->nip)
                        ->where('penerima_role', $activeRole)
                        ->where('dibaca', false)
                        ->count();
        
        // Ambil list pesan utama yang belum dibaca
        $pesanUtamaBelumDibaca = Pesan::where('nip_penerima', $dosen->nip)
                        ->where('penerima_role', $activeRole)
                        ->where('dibaca', false)
                        ->get(['id', 'subjek', 'nim_pengirim', 'nip_pengirim', 'created_at']);
        
        // Hitung balasan dari mahasiswa yang belum dibaca
        $belumDibacaBalasan = BalasanPesan::whereIn('id_pesan', function($query) use ($dosen, $activeRole) {
                        $query->select('id')
                              ->from('pesan')
                              ->where(function($q) use ($dosen, $activeRole) {
                                  $q->where(function($innerQ) use ($dosen, $activeRole) {
                                      $innerQ->where('nip_penerima', $dosen->nip)
                                             ->where('penerima_role', $activeRole);
                                  })
                                  ->orWhere(function($innerQ) use ($dosen, $activeRole) {
                                      $innerQ->where('nip_pengirim', $dosen->nip)
                                             ->where('pengirim_role', $activeRole);
                                  });
                              })
                              ->where('status', 'Aktif');
                    })
                    ->where('tipe_pengirim', 'mahasiswa') // Khusus balasan dari mahasiswa
                    ->where('dibaca', false)
                    ->count();
        
        // Ambil list balasan yang belum dibaca
        $balasanBelumDibaca = BalasanPesan::whereIn('id_pesan', function($query) use ($dosen, $activeRole) {
                        $query->select('id')
                              ->from('pesan')
                              ->where(function($q) use ($dosen, $activeRole) {
                                  $q->where(function($innerQ) use ($dosen, $activeRole) {
                                      $innerQ->where('nip_penerima', $dosen->nip)
                                             ->where('penerima_role', $activeRole);
                                  })
                                  ->orWhere(function($innerQ) use ($dosen, $activeRole) {
                                      $innerQ->where('nip_pengirim', $dosen->nip)
                                             ->where('pengirim_role', $activeRole);
                                  });
                              })
                              ->where('status', 'Aktif');
                    })
                    ->where('tipe_pengirim', 'mahasiswa') // Khusus balasan dari mahasiswa
                    ->where('dibaca', false)
                    ->get(['id', 'id_pesan', 'pengirim_id', 'created_at']);
        
        // Total pesan belum dibaca (pesan utama + balasan)
        $belumDibaca = $belumDibacaUtama + $belumDibacaBalasan;
        
        return response()->json([
            'active_role' => $activeRole,
            'total_belum_dibaca' => $belumDibaca,
            'pesan_utama_belum_dibaca' => $belumDibacaUtama,
            'list_pesan_utama' => $pesanUtamaBelumDibaca,
            'balasan_belum_dibaca' => $belumDibacaBalasan,
            'list_balasan' => $balasanBelumDibaca
        ]);
    }
}