<!-- resources/views/kegiatan/photo_modal.blade.php -->
<div id="photoModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Photo Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="prevPhoto">Previous</button>
                <button type="button" class="btn btn-secondary" id="nextPhoto">Next</button>
                <a href="" id="downloadPhoto" class="btn btn-primary" download>Download</a>
                <button type="button" class="btn btn-success" id="printPhoto">Print</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
