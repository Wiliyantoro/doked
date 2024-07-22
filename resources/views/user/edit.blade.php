<!-- Modal Edit Pengguna -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_name">Nama</label>
                        <input type="text" name="edit_name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" name="edit_email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_level">Level</label>
                        <select name="edit_level" id="edit_level" class="form-control" required>
                            <option value="1">Administrator</option>
                            <option value="2">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_foto_pengguna">Foto Pengguna</label>
                        <input type="file" name="edit_foto_pengguna" id="edit_foto_pengguna" class="form-control" accept="image/*" onchange="previewEditImage(event)">
                        <img id="edit_image_preview" src="#" alt="Preview" class="mt-2 img-thumbnail" style="max-width: 200px;">
                    </div>
                    <div class="form-group">
                        <label for="current_password">Kata Sandi Lama</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('current_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password_confirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="alert alert-danger d-none" id="editError"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="updateUser()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function previewEditImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('edit_image_preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function togglePasswordVisibility(fieldId, button) {
        var field = document.getElementById(fieldId);
        var icon = button.querySelector('i');
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function editUser(id) {
        var user = getUserById(id);
        $('#edit_name').val(user.name);
        $('#edit_email').val(user.email);
        $('#edit_level').val(user.level);
        $('#edit_id').val(user.id);

        // Set the image preview
        $('#edit_image_preview').attr('src', '{{ asset("storage/foto_pengguna") }}' + '/' + user.foto_pengguna);

        $('#editUserModal').modal('show');
    }

    function updateUser() {
        var id = $('#edit_id').val();
        var formData = new FormData($('#editUserForm')[0]);

        axios.post('/user/' + id, formData)
            .then(response => {
                // Tutup modal edit
                $('#editUserModal').modal('hide');
                // Refresh halaman
                window.location.reload();
            })
            .catch(error => {
                if (error.response) {
                    var errors = error.response.data.errors;
                    var errorMessage = '';

                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += errors[key].join('<br>') + '<br>';
                        }
                    }

                    var errorDiv = document.getElementById('editError');
                    errorDiv.innerHTML = errorMessage;
                    errorDiv.classList.remove('d-none');
                }
            });
    }
</script>
