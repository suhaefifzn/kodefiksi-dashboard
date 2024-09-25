@extends('dashboard.main', [ 'title' => $title ])
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Pengguna</div>
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
                            <label for="input" class="control-label">Email</label>
                            <i class="bar"></i>
                        </div>
                        <div class="form-group my-2">
                            <input type="password" name="password" id="password" required>
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
    </script>
@endsection
