<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
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
              <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
          </div>
      </div>
  </div>
</div>
<script>
  $('.delete-btn').on('click', function() {
      var id = $(this).data('id');
      $('#confirmDeleteButton').data('id', id);
      $('#confirmDeleteModal').modal('show');
  });

  $('#confirmDeleteButton').on('click', function() {
      var id = $(this).data('id');
      $('#deleteForm' + id).submit();
  });
</script>
