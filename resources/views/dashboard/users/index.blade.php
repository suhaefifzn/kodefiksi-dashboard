@extends('dashboard.main', [ 'title' => $title ])
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ $title }}</div>
                        <hr>

                        <div class="button-wrapper mb-5">
                            <button class="btn btn-primary px-3 d-flex align-content-center gap-2" id="addUserButton" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fa fa-plus"></i>
                                Tambah Pengguna
                            </button>
                        </div>

                        <table class="table table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Email</th>
                                    <th>is Admin</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add User --}}
    <div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUserModalLabel">Tambah Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.add') }}" method="POST" id="addUserForm" class="form-sample material-form bordered">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group my-2">
                            <input type="text" name="name" id="name"  autocomplete="off" required>
                            <label for="input" class="control-label">Nama</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-2">
                            <input type="text" name="username" id="username" required>
                            <label for="input" class="control-label">Username</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-2">
                            <input type="email" name="email" id="email" required>
                            <label for="input" class="control-label">sEmail</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-2">
                            <input type="password" name="password" id="passwordAdd" required>
                            <label for="input" class="control-label">Password</label>
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

    {{-- Modal Change Password --}}
    <div class="modal fade" id="editPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPasswordModalLabel">Ganti Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.change.password') }}" method="POST" id="editPasswordForm" class="form-sample material-form bordered">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="form-group my-2">
                            <input type="hidden" name="_username" id="usernameEditPassword">
                        </div>
                        <div class="form-group my-2">
                            <input type="password" name="password" id="password" minlength="8" required>
                            <label for="input" class="control-label">Password <span class="small text-danger">*minimal 8 karakter</span></label>
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

    {{-- Modal Edit Profile --}}
    <div class="modal fade" id="editProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editProfileModalLabel">Edit Profil Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editProfileForm" class="form-sample material-form bordered">
                    @csrf
                    @method('put')
                    <div class="modal-body p-3">
                        <input type="hidden" name="_username" id="_usernameEdit">
                        <div class="form-group my-2">
                            <input type="text" name="name" id="nameEdit" autocomplete="off" required>
                            <label for="input" class="control-label">Nama</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-2">
                            <input type="text" name="username" id="usernameEdit" autocomplete="off" required>
                            <label for="input" class="control-label">Username</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-2">
                            <input type="email" name="email" id="emailEdit" autocomplete="off" required>
                            <label for="input" class="control-label">Email</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>Kategori</th>
                                        <th>Total Artikel</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTableTotalArticlesUser">
                                </tbody>
                            </table>
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
    <script>
        let table = null;

        $(document).ready(() => {
            // initialize datatable
            table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.table') }}",
                    type: "GET",
                    dataSrc: function(json) {
                        // console.log("Data received from server:", json);
                        return json.data;
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'no.',
                        className: 'text-center',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1 + '.';
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'email', name: 'email' },
                    { data: 'is_admin', name: 'isAdmin' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // add user
            $('#addUserForm').on('submit', (e) => {
                e.preventDefault();
                const formData = {
                    '_token': e.target[0].value,
                    'name': e.target[1].value,
                    'username': e.target[2].value,
                    'email': e.target[3].value,
                    'password': e.target[4].value,
                };
                const formAction = e.currentTarget.action;

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    success: (response, status, xhr) => {
                        toast('success', response.message, 2000);
                        table.ajax.reload(null, false);
                        $('#addUserModal').modal('hide');
                        $('#addUserForm').trigger('reset');
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
                });
            });

            // edit user password
            $('#editPasswordForm').on('submit', (e) => {
                e.preventDefault();
                const formData = {
                    '_token': e.target[0].value,
                    '_method': e.target[1].value,
                    'username': e.target[2].value,
                    'password': e.target[3].value,
                };
                const formAction = e.currentTarget.action;

                if (formData.username.length < 1) {
                    return toast('error', 'The username of the selected user was not found');
                }

                $.ajax({
                    url: formAction,
                    type: 'PUT',
                    data: formData,
                    success: (response, status, xhr) => {
                        toast('success', response.message, 2000);
                        table.ajax.reload(null, false);
                        $('#editPasswordModal').modal('hide');
                        $('#editPasswordForm').trigger('reset');
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
                });
            });

            // edit user profile
            $('#editProfileForm').on('submit', (e) => {
                e.preventDefault();
                const formData = {
                    '_token': e.target[0].value,
                    '_method': e.target[1].value,
                    '_username': e.target[2].value,
                    'name': e.target[3].value,
                    'username': e.target[4].value,
                    'email': e.target[5].value,
                };
                const formAction = '/users/profile/' + formData._username;

                if (formData._username.length < 1) {
                    return toast('error', 'The username of the selected user was not found');
                }

                $.ajax({
                    url: formAction,
                    type: 'PUT',
                    data: formData,
                    success: (response, status, xhr) => {
                        toast('success', response.message, 2000);
                        table.ajax.reload(null, false);
                        $('#editProfileModal').modal('hide');
                        $('#editProfileForm').trigger('reset');
                    },
                    error: (xhr, status) => {
                        if (xhr.status <= 404) {
                            return toast('warning', xhr.responseJSON.message);
                        }

                        if (xhr.status === 422) {
                            return toast('warning', 'Ensure that the content of each field is correct and that the username and email have not been used by another user');
                        }

                        return toast('error', 'Internal server error');
                    }
                });
            });
        });

        const deleteUser = (element) => {
            const {username, token} = element.dataset;

            alertConfirm('warning', 'Are you sure? You won\'t be able to revert this!')
                .then((results) => {
                    if (results.isConfirmed) {
                        $.ajax({
                            url: '{{ route("users.delete") }}',
                            type: 'DELETE',
                            data: {
                                '_token': token,
                                'username': username
                            },
                            success: (response, status, xhr) => {
                                toast('success', 'User has been deleted!', 2000);
                                table.ajax.reload(null, false);
                            },
                            error: (xhr, status) => {
                                if (xhr.status < 500) {
                                    return toast('warning', xhr.responseJSON.message);
                                }
                                return toast('error', 'Internal server error');
                            }
                        })
                    }
                });
        };

        const editPassword = (element) => {
            const {username} = element.dataset;
            $('#usernameEditPassword').val(username);
            $('#editPasswordModal').modal('show');
        };

        const editUser = (element) => {
            const {username} = element.dataset;
            $('#_usernameEdit').val(username);

            const url = '/users/profile/' + username;

            $.ajax({
                url,
                type: 'GET',
                success: (response, status, xhr) => {
                    const profile = response.data;
                    const articles = profile.articles;

                    $('#nameEdit').val(profile.name);
                    $('#usernameEdit').val(profile.username);
                    $('#emailEdit').val(profile.email);

                    let tableRows = '';
                    $.each(articles, (index, val) => {
                        tableRows += `<tr>
                                <td class='text-center'>${++index}</td>
                                <td>${val.name}</td>
                                <td>${val.articles_count}</td>
                            </tr>`;
                    });

                    $('#bodyTableTotalArticlesUser').html(tableRows);

                    // show modal
                    $('#editProfileModal').modal('show');
                },
                error: (xhr, status) => {
                    if (xhr.status <= 404) {
                        return toast('warning', xhr.responseJSON.message);
                    }
                    return toast('error', 'Internal server error');
                }
            });
        };
    </script>
@endsection
