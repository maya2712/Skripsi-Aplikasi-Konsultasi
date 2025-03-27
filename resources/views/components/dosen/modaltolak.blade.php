<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTolakLabel">Tolak Usulan Bimbingan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="usulan-detail mb-3">
                    <p><strong>NIM:</strong> <span class="nim-display"></span></p>
                    <p><strong>Nama:</strong> <span class="nama-display"></span></p>
                    <p><strong>Jenis Bimbingan:</strong> <span class="jenis-display"></span></p>
                </div>
                <div class="form-group">
                    <label for="alasanPenolakan">Alasan Penolakan:</label>
                    <textarea class="form-control" id="alasanPenolakan" rows="3" required placeholder="Tuliskan alasan penolakan usulan bimbingan"></textarea>
                    <div class="invalid-feedback">Silakan isi alasan penolakan</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmTolak">Ya, Tolak</button>
            </div>
        </div>
    </div>
</div>