<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead class="text-center">
            <tr>
                <th scope="col" class="text-center align-middle">No.</th>
                <th scope="col" class="text-center align-middle">Tanggal</th>
                <th scope="col" class="text-center align-middle">Dosen</th>
                <th scope="col" class="text-center align-middle">Jenis Bimbingan</th>
                <th scope="col" class="text-center align-middle">Waktu</th>
                <th scope="col" class="text-center align-middle">Lokasi</th>
                <th scope="col" class="text-center align-middle">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $item)
            <tr class="text-center">
                <td>{{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y') }}</td>
                <td>{{ $item->dosen_nama }}</td>
                <td>{{ ucfirst($item->jenis_bimbingan) }}</td>
                <td>{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}</td>
                <td>{{ $item->lokasi ?? '-' }}</td>
                <td>
                    <a href="{{ route('mahasiswa.riwayatDetail', $item->id) }}" 
                       class="btn btn-sm btn-info">
                        <i class="bi bi-info-circle"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada riwayat bimbingan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>