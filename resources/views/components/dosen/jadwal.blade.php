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
                <th class="text-center">Kode Dosen</th>
                <th class="text-center">Nama Dosen</th>
                <th class="text-center">Total Bimbingan</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="text-center">ED</td>
                <td class="text-center">Edi Susilo, S.Pd., M.Kom., M.Eng.</td>
                <td class="text-center">2</td>
                <td class="text-center">
                    <a href="{{ url('/detaildaftar') }}" class="badge btn btn-info p-1 mb-1">
                        <i class="fas fa-info-circle"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>