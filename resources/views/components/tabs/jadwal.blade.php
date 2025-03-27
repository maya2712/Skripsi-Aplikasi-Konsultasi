<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead class="text-center">
            <tr>
                <th scope="col" class="text-center align-middle">No.</th>
                <th scope="col" class="text-center align-middle">NIP</th>
                <th scope="col" class="text-center align-middle">Nama Dosen</th>
                <th scope="col" class="text-center align-middle">Total Bimbingan</th>
                <th scope="col" class="text-center align-middle">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftarDosen as $index => $dosen)
            <tr class="text-center">
                <td>{{ ($daftarDosen->currentPage() - 1) * $daftarDosen->perPage() + $loop->iteration }}</td>
                <td>{{ $dosen->nip }}</td>
                <td>{{ $dosen->nama }}</td>
                <td>{{ $dosen->total_bimbingan }}</td>
                <td>
                    <a href="{{ route('mahasiswa.detaildaftar', $dosen->nip) }}" 
                       class="btn btn-sm btn-info">
                        <i class="bi bi-info-circle"></i> 
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data dosen</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>