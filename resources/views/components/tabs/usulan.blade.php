<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead class="text-center">
            <tr>
                <th scope="col" class="text-center align-middle">No.</th>
                <th scope="col" class="text-center align-middle">NIM</th>
                <th scope="col" class="text-center align-middle">Nama</th>
                <th scope="col" class="text-center align-middle">Jenis Bimbingan</th>
                <th scope="col" class="text-center align-middle">Tanggal</th>
                <th scope="col" class="text-center align-middle">Waktu</th>
                <th scope="col" class="text-center align-middle">Lokasi</th>
                <th scope="col" class="text-center align-middle">Status</th>
                <th scope="col" class="text-center align-middle">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usulan as $index => $item)
            <tr class="text-center">
                <td>{{ ($usulan->currentPage() - 1) * $usulan->perPage() + $loop->iteration }}</td>
                <td>{{ $item->nim }}</td>
                <td>{{ $item->mahasiswa_nama }}</td>
                <td>{{ ucfirst($item->jenis_bimbingan) }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}</td>
                <td>{{ $item->lokasi ?? '-' }}</td>
                <td class="fw-bold bg-{{ $item->status === 'DISETUJUI' ? 'success' : 
                ($item->status === 'DITOLAK' ? 'danger' : 'info') }}">
                    {{ $item->status }}
                </td>
                <td>
                    <a href="{{ route('mahasiswa.aksiInformasi', $item->id) }}" 
                       class="btn btn-sm btn-info">
                        <i class="bi bi-info-circle"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data usulan bimbingan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>