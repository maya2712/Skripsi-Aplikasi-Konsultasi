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
        <tbody id="tabelUsulan">
            <tr>
                <td class="text-center">1</td>
                <td class="text-center">2107112735</td>
                <td class="text-center">Tri Murniati</td>
                <td class="text-center">Bimbingan Kerja Praktek</td>
                <td class="text-center">Senin, 4 Oktober 2024</td>
                <td class="text-center">13.30 - 16.00</td>
                <td class="text-center"></td>
                <td class="text-center">USULAN</td>
                <td class="text-center">
                    <div class="action-icons">
                        <a href="{{ url('/terimausulanbimbingan') }}" class="action-icon info-icon">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        <a href="#" class="action-icon approve-icon" data-bs-toggle="modal" data-bs-target="#modalTerima">
                            <i class="fas fa-check"></i>
                        </a>
                        <a href="#" class="action-icon reject-icon" data-bs-toggle="modal" data-bs-target="#modalTolak">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td class="text-center">2107110255</td>
                <td class="text-center">Syahirah Tri Meilina</td>
                <td class="text-center">Bimbingan Skripsi</td>
                <td class="text-center">Senin, 30 September 2024</td>
                <td class="text-center">13.30 - 16.00</td>
                <td class="text-center"></td>
                <td class="text-center">USULAN</td>
                <td class="text-center">
                    <div class="action-icons">
                        <a href="{{ url('/terimausulanbimbingan') }}" class="action-icon info-icon" title="Lihat Detail">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        <a href="#" class="action-icon approve-icon" data-bs-toggle="modal" data-bs-target="#modalTerima" title="Terima Usulan">
                            <i class="fas fa-check"></i>
                        </a>
                        <a href="#" class="action-icon reject-icon" data-bs-toggle="modal" data-bs-target="#modalTolak" title="Tolak Usulan">
                        <i class="fas fa-times"></i>
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>