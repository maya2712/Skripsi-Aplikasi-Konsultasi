@foreach($pesan as $p)
<div class="card mb-2 message-card {{ strtolower($p->prioritas) }}" onclick="window.location.href='{{ route('dosen.pesan.show', $p->id) }}';" style="cursor: pointer;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center">
                <div class="profile-image-placeholder me-3">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <span class="badge bg-primary mb-1">{{ $p->subjek }}</span>
                    
                    @if($p->nip_pengirim == Auth::user()->nip)
                        <!-- Jika dosen adalah pengirim, tampilkan nama mahasiswa penerima -->
                        <h6 class="mb-1" style="font-size: 14px;">
                            <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                            @php
                                // Ambil langsung data mahasiswa
                                $mahasiswa = App\Models\Mahasiswa::where('nim', $p->nim_penerima)->first();
                                $nama_penerima = $mahasiswa ? $mahasiswa->nama : 'Mahasiswa';
                            @endphp
                            {{ $nama_penerima }}
                        </h6>
                        <small class="text-muted">{{ $p->nim_penerima }}</small>
                    @else
                        <!-- Jika dosen adalah penerima, tampilkan nama mahasiswa pengirim -->
                        <h6 class="mb-1" style="font-size: 14px;">
                            <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                            @php
                                // Ambil langsung data mahasiswa
                                $mahasiswa = App\Models\Mahasiswa::where('nim', $p->nim_pengirim)->first();
                                $nama_pengirim = $mahasiswa ? $mahasiswa->nama : 'Pengirim';
                            @endphp
                            {{ $nama_pengirim }}
                        </h6>
                        <small class="text-muted">{{ $p->nim_pengirim }}</small>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                @php
                    // Hitung jumlah balasan yang belum dibaca dengan Query Builder yang benar
                    $unreadReplies = App\Models\BalasanPesan::where('id_pesan', $p->id)
                        ->where('dibaca', false) // Pastikan model BalasanPesan menangani ini sebagai boolean
                        ->where('tipe_pengirim', 'mahasiswa') // Hanya balasan dari mahasiswa
                        ->count();
                    
                    // Tentukan status badge - Gunakan pengecekan yang lebih ketat
                    $badgeClass = 'bg-success';
                    $badgeText = 'Sudah dibaca';
                    
                    if ($p->nip_penerima == Auth::user()->nip && $p->dibaca == false) {
                        // Pesan utama belum dibaca oleh dosen (sebagai penerima)
                        $badgeClass = 'bg-danger';
                        $badgeText = 'Belum dibaca';
                    } 
                    elseif ($unreadReplies > 0) {
                        // Ada balasan baru dari mahasiswa yang belum dibaca
                        $badgeClass = 'bg-danger';
                        $badgeText = $unreadReplies . ' balasan baru';
                    }
                @endphp
                
                <!-- Status dibaca/balasan baru dalam satu badge -->
                <span class="badge {{ $badgeClass }} me-1">
                    {{ $badgeText }}
                </span>
                
                <span class="badge {{ $p->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                    {{ $p->prioritas }}
                </span>
                
                <small class="d-block text-muted my-1">
                    {{ \Carbon\Carbon::parse($p->created_at)->timezone('Asia/Jakarta')->diffForHumans() }}
                </small>
                
                <div class="action-buttons" onclick="event.stopPropagation();">
                    @if(isset($p->bookmarked))
                    <form action="{{ route('dosen.pesan.bookmark', $p->id) }}" method="POST" class="d-inline me-2">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 bookmark-btn" title="{{ $p->bookmarked ? 'Hapus Bookmark' : 'Bookmark Pesan' }}">
                            <i class="fas fa-bookmark bookmark-icon {{ $p->bookmarked ? 'active' : '' }}"></i>
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('dosen.pesan.show', $p->id) }}" class="btn btn-custom-primary btn-sm view-btn" style="font-size: 10px;">
                        <i class="fas fa-eye me-1"></i>Lihat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach