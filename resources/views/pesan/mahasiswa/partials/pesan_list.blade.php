<!-- File: views/pesan/mahasiswa/partials/pesan_list.blade.php -->

@if($pesan->count() > 0)
    @foreach($pesan as $p)
    <!-- Menghapus kelas kaprodi-card dan atribut data-role yang membuat tampilan berbeda -->
    <div class="card mb-2 message-card {{ strtolower($p->prioritas) }}" 
         onclick="window.location.href='{{ route('mahasiswa.pesan.show', $p->id) }}'">
        
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8 d-flex align-items-center">
                    @if($p->nim_pengirim == Auth::user()->nim)
                        <!-- Menampilkan foto dosen penerima -->
                        @php
                            $profilePhoto = $p->dosenPenerima && $p->dosenPenerima->profile_photo 
                                ? asset('storage/profile_photos/'.$p->dosenPenerima->profile_photo) 
                                : null;
                        @endphp
                        @if($profilePhoto)
                            <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image me-3">
                        @else
                            <div class="profile-image-placeholder me-3">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    @else
                        <!-- Menampilkan foto dosen pengirim -->
                        @php
                            $profilePhoto = $p->dosenPengirim && $p->dosenPengirim->profile_photo 
                                ? asset('storage/profile_photos/'.$p->dosenPengirim->profile_photo) 
                                : null;
                        @endphp
                        @if($profilePhoto)
                            <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image me-3">
                        @else
                            <div class="profile-image-placeholder me-3">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    @endif
                    <div>
                        <span class="badge bg-primary mb-1">{{ $p->subjek }}</span>
                        
                        @if($p->nim_pengirim == Auth::user()->nim)
                            <!-- Jika mahasiswa adalah pengirim, tampilkan nama dosen penerima -->
                            <h6 class="mb-1" style="font-size: 14px;">
                                <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                {{ $p->dosenPenerima ? $p->dosenPenerima->nama : 'Dosen' }}
                                
                                <!-- Badge untuk Kaprodi - tetap mempertahankan badge -->
                                @if($p->penerima_role == 'kaprodi')
                                    <span class="badge badge-kaprodi ms-1">KAPRODI</span>
                                @endif
                            </h6>
                            <small class="text-muted">NIP: {{ $p->nip_penerima }}</small><br>
                            <small class="text-muted">{{ $p->dosenPenerima ? $p->dosenPenerima->jabatan : 'Dosen' }}</small>
                        @else
                            <!-- Jika mahasiswa adalah penerima, tampilkan nama dosen pengirim -->
                            <h6 class="mb-1" style="font-size: 14px;">
                                <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                {{ $p->dosenPengirim ? $p->dosenPengirim->nama : 'Dosen' }}
                                
                                <!-- Badge untuk Kaprodi - tetap mempertahankan badge -->
                                @if($p->pengirim_role == 'kaprodi')
                                    <span class="badge badge-kaprodi ms-1">KAPRODI</span>
                                @endif
                            </h6>
                            <small class="text-muted">NIP: {{ $p->nip_pengirim }}</small><br>
                            <small class="text-muted">{{ $p->dosenPengirim ? $p->dosenPengirim->jabatan : 'Dosen' }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @php
                        // Hitung jumlah balasan yang belum dibaca
                        $unreadReplies = App\Models\BalasanPesan::where('id_pesan', $p->id)
                            ->where('dibaca', false)
                            ->where('tipe_pengirim', 'dosen') // Untuk mahasiswa, kita hanya menghitung balasan dari dosen
                            ->count();
                        
                        // Tentukan status badge
                        $badgeClass = 'bg-success';
                        $badgeText = 'Sudah dibaca';
                        
                        if (!$p->dibaca && $p->nim_penerima == Auth::user()->nim) {
                            // Pesan utama belum dibaca
                            $badgeClass = 'bg-danger';
                            $badgeText = 'Belum dibaca';
                        } else if ($unreadReplies > 0) {
                            // Ada balasan baru yang belum dibaca
                            $badgeClass = 'bg-danger';
                            $badgeText = $unreadReplies . ' balasan baru';
                        }
                    @endphp
                    
                    <span class="badge {{ $badgeClass }} me-1">
                        {{ $badgeText }}
                    </span>
                    
                    <span class="badge {{ $p->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                        {{ $p->prioritas }}
                    </span>
                    
                   <small class="d-block text-muted my-1">
                        {{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}
                    </small>
                    
                    <div class="action-buttons" onclick="event.stopPropagation();">
                        @if(isset($p->bookmarked))
                        <form action="{{ route('mahasiswa.pesan.bookmark', $p->id) }}" method="POST" class="d-inline me-2">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 bookmark-btn" title="{{ $p->bookmarked ? 'Hapus Bookmark' : 'Bookmark Pesan' }}">
                                <i class="fas fa-bookmark bookmark-icon {{ $p->bookmarked ? 'active' : '' }}"></i>
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('mahasiswa.pesan.show', $p->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                            <i class="fas fa-eye me-1"></i>Lihat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="text-center py-5">
        <p class="text-muted">Belum ada pesan</p>
    </div>
@endif