<!-- Modal konfirmasi hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin menghapus kegiatan ini?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
        </div>
      </div>
    </div>
  </div>
  <script>
  // Script untuk menangani konfirmasi hapus
  document.querySelectorAll('.delete-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
              var kegiatanId = this.getAttribute('data-id');
              $('#confirmDeleteModal').modal('show');
              document.getElementById('confirmDeleteBtn').onclick = function() {
                  document.getElementById('deleteForm' + kegiatanId).submit();
              }
          });
      });
      </script>