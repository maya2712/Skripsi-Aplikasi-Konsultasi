@foreach($pesan as $p)
<div class="card mb-2 message-card {{ strtolower($p->prioritas) }}" onclick="window.location.href='{{ route('dosen.pesan.show', $p->id) }}'">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center">
                <div class="profile-image-placeholder me-3">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <span class="badge bg-primary mb-1">{{ $p->subjek }}</span>
                    <h6 class="mb-1" style="font-size: 14px;">{{ $p->pengirim->nama }}</h6>
                    <small class="text-muted">{{ $p->nim_pengirim }}</small>
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
                <div class="action-buttons" onclick="event.stopPropagation();">
                    @if(isset($p->bookmarked))
                    <form action="{{ route('dosen.pesan.bookmark', $p->id) }}" method="POST" class="d-inline me-2">
                        @csrf
                        <button type="submit" class="btn btn-link p-0" title="{{ $p->bookmarked ? 'Hapus Bookmark' : 'Bookmark Pesan' }}">
                            <i class="fas fa-bookmark bookmark-icon {{ $p->bookmarked ? 'active' : '' }}"></i>
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('dosen.pesan.show', $p->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                        <i class="fas fa-eye me-1"></i>Lihat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@if($pesan->count() == 0)
<div class="text-center py-5">
    <p class="text-muted">Belum ada pesan</p>
</div>
@endif