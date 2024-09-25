@extends('dashboard.main', ['title' => $title])
@section('content')
    <div class="content-wrapper">
        <div class="row">
            {{-- foto profil --}}
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Foto Profilku</div>
                        <hr>
                        <form action="{{ route('profile.edit.image') }}" method="POST" class="form-sample material-form bordered d-flex justify-content-center flex-column align-items-center" enctype="multipart/form-data">
                            @csrf
                            <div class="image-container mt-3" style="width: 300px; height: 300px" id="imageContainer">
                                <img src="{{ config('app.my_config.api_url') . '/' . $data['image'] }}" alt="Foto Profil" class="img-fluid img-thumbnail rounded rounded-circle" style="object-fit: cover; width: 100%; height: 100%" id="fotoProfil"/>
                            </div>
                            <div class="form-group border-0 mt-3">
                                <input type="file" class="file-upload-default d-none" name="image" id="imageUpload" accept=".png,.jpg">
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info d-none" disabled/>
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-success" type="button" id="selectImageButton">
                                            Pilih Foto
                                        </button>
                                        <button class="btn btn-primary" id="uploadImageButton">
                                            Upload
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- data name, username, .dll --}}
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Data Profilku</div>
                        <hr>
                        <form action="{{ route('profile.edit.data') }}" method="POST" class="form-sample material-form bordered" id="profileDataForm">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <input type="text" name="name" id="name" value="{{ $data['name'] }}" autocomplete="off" required>
                                <label for="input" class="control-label">Nama</label>
                                <i class="bar"></i>
                            </div>
                            <div class="form-group">
                                <input type="text" name="username" id="username" value="{{ $data['username'] }}" autocomplete="off" required>
                                <label for="input" class="control-label">Username</label>
                                <i class="bar"></i>
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" id="email" value="{{ $data['email'] }}" autocomplete="off" required>
                                <label for="input" class="control-label">Email</label>
                                <i class="bar"></i>
                            </div>
                            <div class="button-container d-flex justify-content-between">
                                <button class="button btn btn-primary" type="submit">
                                    Simpan
                                </button>
                                <button class="button btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                    Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Change Password-->
    <div class="modal fade" id="editPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPasswordModalLabel">Ganti Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.edit.password') }}" method="POST" id="editPasswordForm" class="form-sample material-form bordered">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="form-group my-2">
                            <input type="password" name="new_password" id="newPassword"  autocomplete="off" required>
                            <label for="input" class="control-label">Password Baru</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-2">
                            <input type="password" name="confirm_new_password" id="confirmNewPassword" required>
                            <label for="input" class="control-label">Konfirmasi Password Baru</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-2">
                            <input type="password" name="old_password" id="oldPassword" required>
                            <label for="input" class="control-label">Password Lama</label>
                            <i class="bar"></i>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @if (session('error_image'))
        <script>
            const errorMessage = @json(session('error_image'));
            toast('warning', errorMessage);
        </script>
    @endif

    <script>
        $(document).ready(() => {
            // upload foto profil
            $('#selectImageButton').on('click', () => {
                $('#imageUpload').trigger('click');
            });

            $('#imageUpload').on('change', (e1) => {
                const file = e1.currentTarget.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e2) => {
                        $('#fotoProfil').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // edit profile data
            $('#profileDataForm').on('submit', (e) => {
                e.preventDefault();
                const formData = {
                    '_token': e.target[0].value,
                    '_method': e.target[1].value,
                    'name': e.target[2].value,
                    'username': e.target[3].value,
                    'email': e.target[4].value,
                };
                const formAction = e.currentTarget.action;

                $.ajax({
                    url: formAction,
                    type: 'PUT',
                    data: formData,
                    success: (response, status, xhr) => {
                        return toast('success', response.message, 2000)
                            .then(() => {
                                location.reload();
                            });
                    },
                    error: (xhr, status) => {
                        return toast('error', xhr.responseJSON.message);
                    }
                });
            });

            // edit password account
            $('#editPasswordForm').on('submit', (e) => {
                e.preventDefault();
                const formData = {
                    '_token': e.target[0].value,
                    '_method': e.target[1].value,
                    'new_password': e.target[2].value,
                    'confirm_new_password': e.target[3].value,
                    'old_password': e.target[4].value,
                };
                const formAction = e.currentTarget.action;

                $.ajax({
                    url: formAction,
                    type: 'PUT',
                    data: formData,
                    success: (response, status, xhr) => {
                        return toast('success', response.message, 2000)
                            .then(() => {
                                location.href = '/logout';
                            });
                    },
                    error: (xhr, status) => {
                        if (xhr.status <= 403) {
                            return toast('warning', xhr.responseJSON.message);
                        }

                        if (xhr.status === 422) {
                            return toast('warning', 'Ensure that the content of each field is correct');
                        }

                        return toast('error', 'Internal server error');
                    }
                })
            });
        });
    </script>
@endsection
