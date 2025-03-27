<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        Tampilkan
        <select class="form-select form-select-sm d-inline-block w-auto">
            <option>50</option>
        </select>
        entri
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr class="table-secondary">
                <th class="text-center">No.</th>
                <th class="text-center">NIM</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Jenis Bimbingan</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Waktu</th>
                <th class="text-center">Lokasi</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="text-center">2107110255</td>
                <td class="text-center">Syahirah Tri Meilina</td>
                <td class="text-center">Bimbingan Skripsi</td>
                <td class="text-center">Senin, 30 September 2024</td>
                <td class="text-center">13.30 - 16.00</td>
                <td class="text-center"></td>
                <td class="text-center">SELESAI</td>
                <td class="text-center">
                    <a href="{{ url('/riwayatdosen') }}" class="badge btn btn-info p-1 mb-1">
                        <i class="fas fa-info-circle"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>