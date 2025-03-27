<div class="modal fade" id="modalTerima" tabindex="-1" aria-labelledby="modalTerimaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTerimaLabel">Terima Usulan Bimbingan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menerima usulan bimbingan ini?</p>
                <div class="usulan-detail mt-3">
                    <p><strong>NIM:</strong> <span class="nim-display"></span></p>
                    <p><strong>Nama:</strong> <span class="nama-display"></span></p>
                    <p><strong>Jenis Bimbingan:</strong> <span class="jenis-display"></span></p>
                </div>
                <div class="form-group mt-3">
                    <label for="lokasiBimbingan">Lokasi Bimbingan:</label>
                    <input type="text" class="form-control" id="lokasiBimbingan" required placeholder="Masukkan lokasi bimbingan">
                    <div class="invalid-feedback">Silakan isi lokasi bimbingan</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmTerima">Ya, Terima</button>
            </div>
        </div>
    </div>
</div>