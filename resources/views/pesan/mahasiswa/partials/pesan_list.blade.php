@if($pesan->count() > 0)
    @foreach($pesan as $p)
    <div class="card mb-2 message-card {{ strtolower($p->prioritas) }}" onclick="window.location.href='{{ route('mahasiswa.pesan.show', $p->id) }}'">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8 d-flex align-items-center">
                    <div class="profile-image-placeholder me-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <span class="badge bg-primary mb-1">{{ $p->subjek }}</span>
                        
                        @if($p->nim_pengirim == Auth::user()->nim)
                            <!-- Jika mahasiswa adalah pengirim, tampilkan informasi dosen penerima -->
                            <h6 class="mb-1" style="font-size: 14px;">{{ $p->penerima->nama ?? 'Dosen' }}</h6>
                            <small class="text-muted">{{ $p->penerima->jabatan ?? '' }}</small>
                        @else
                            <!-- Jika mahasiswa adalah penerima, tampilkan informasi dosen pengirim -->
                            <h6 class="mb-1" style="font-size: 14px;">
                                @php
                                    $dosenPengirim = App\Models\Dosen::where('nip', $p->nip_pengirim)->first();
                                    $namaPengirim = $dosenPengirim ? $dosenPengirim->nama : 'Dosen';
                                    $jabatanPengirim = $dosenPengirim ? $dosenPengirim->jabatan ?? 'Dosen' : 'Dosen';
                                @endphp
                                {{ $namaPengirim }}
                            </h6>
                            <small class="text-muted">{{ $jabatanPengirim }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge {{ $p->dibaca ? 'bg-success' : 'bg-danger' }} me-1">
                        {{ $p->dibaca ? 'Sudah dibaca' : 'Belum dibaca' }}
                    </span>
                    <span class="badge {{ $p->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                        {{ $p->prioritas }}
                    </span>
                    <small class="d-block text-muted my-1">
                        {{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}
                    </small>
                    <a href="{{ route('mahasiswa.pesan.show', $p->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                        <i class="fas fa-eye me-1"></i>Lihat
                    </a>
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