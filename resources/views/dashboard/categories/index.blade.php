@extends('dashboard.main', [ 'title' => $title ])
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ $title }}</div>
                        <hr>

                        <div class="button-wrapper my-4 d-flex align-items-center justify-content-between">
                            <form class="form-sample col-md-4" id="searchCategoryForm">
                                <div class="input-group d-flex align-items-center">
                                    <select class="form-select" id="selectCategory">
                                        <option selected disabled>Pilih Kategori</option>
                                    </select>
                                    <button class="btn btn-dark" type="submit" id="searhButton">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                            <button class="btn btn-primary border-0 px-3 d-flex align-content-center gap-2" id="addCategoryButton" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fa fa-plus"></i>
                                Tambah Kategori
                            </button>
                        </div>

                        <div class="table-wrapper d-none" id="tableWrapperCategory">
                            {{-- Detail Category --}}
                            <table class="table table-hover" id="tableDetailCategory">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>Nama</th>
                                        <th>Artikel Dirilis</th>
                                        <th>Artikel Didraft</th>
                                        <th>Total Artikel</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1.</td>
                                        <td id="columnName"></td>
                                        <td id="columnDirilis"></td>
                                        <td id="columnDidraft"></td>
                                        <td id="columnTotal"></td>
                                        <td>
                                            <button class="badge badge-secondary border-0" title="Edit" id="editButton" data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button class="badge badge-danger border-0" title="Delete" id="deleteButton">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr/>

                            {{-- List Articles By Category --}}
                            <div class="table-wrapper-list-articles mt-5">
                                <table class="table table-hover" id="tableListArticles">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th>Judul Artikel</th>
                                            <th>Status</th>
                                            <th>Penulis</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add Category --}}
    <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addCategoryModalLabel">Tambah Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('categories.add') }}" method="POST" id="addCategoryForm" class="form-sample material-form bordered">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group my-2">
                            <input type="text" name="name" id="name"  autocomplete="off" required>
                            <label for="input" class="control-label">Nama</label>
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

    {{-- Modal Edit Kategori --}}
    <div class="modal fade" id="editCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editCategoryModalLabel">Edit Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editCategoryForm" class="form-sample material-form bordered">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="form-group my-2">
                            <input type="text" name="name" id="nameEdit" autocomplete="off" required>
                            <label for="input" class="control-label">Nama</label>
                            <i class="bar"></i>
                        </div>
                        <input type="hidden" name="slug" id="slugEdit" autocomplete="off" required>
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
        $(document).ready(() => {
            loadListCategory();

            // add new category
            $('#addCategoryForm').on('submit', (e) => {
                e.preventDefault();
                const formData = {
                    '_token': e.target[0].value,
                    'name': e.target[1].value,
                };
                const formAction = e.currentTarget.action;

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    success: (response, status, xhr) => {
                        $('#addCategoryModal').modal('hide');
                        $('#addCategoryForm').trigger('reset');
                        toast('success', response.message, 2000);
                        loadListCategory();
                    },
                    error: (xhr, status) => {
                        if (xhr.status <= 404) {
                            return toast('warning', xhr.responseJSON.message);
                        }

                        if (xhr.status === 422) {
                            return toast('warning', 'Ensure the fields are filled in correctly and that no duplicate category exists');
                        }

                        return toast('error', 'Internal server error');
                    }
                });
            });

            // search for one category
            $('#searchCategoryForm').on('submit', (e) => {
                e.preventDefault();
                $('#tableListArticles').DataTable().destroy();

                const category = e.currentTarget[0].value;

                if (category.length <= 3) {
                    return toast('warning', 'Please select a category first')
                }

                return loadTable(category);
            });

            // delete category
            $('#deleteButton').on('click', (e) => {
                e.preventDefault();
                const {category} = e.currentTarget.dataset;

                alertConfirm('warning', 'Are your sure? You won\'t be able to revert this!')
                    .then((results) => {
                        if (results.isConfirmed) [
                            $.ajax({
                                url: @json(route('categories.delete')),
                                type: 'DELETE',
                                data: {
                                    _token: @json(csrf_token()),
                                    slug: category
                                },
                                success: (response, status, xhr) => {
                                    toast('success', response.message, 2000);

                                    $('#tableWrapperCategory').addClass('d-none');
                                    $('#tableListArticles').DataTable().destroy();
                                    $('#columnName').text('');
                                    $('#columnDirilis').text('');
                                    $('#columnDidraft').text('');
                                    $('#columnTotal').text('');
                                    $('#deleteButton').attr('data-category', '');
                                    $('#editButton').attr('data-category', '');

                                    loadListCategory();
                                },
                                error: (xhr, status) => {
                                    if (xhr.status <= 404) {
                                        return toast('warning', xhr.responseJSON.message);
                                    }
                                    return toast('error', 'Internal server error');
                                }
                            })
                        ]
                    });
            });

            // edit category button
            $('#editButton').on('click', (e) => {
                e.preventDefault();
                $('#editCategoryModal').modal('show');
            });

            // edit category submit form
            $('#editCategoryForm').on('submit', (e) => {
                e.preventDefault();
                const formData = {
                    '_token': e.target[0].value,
                    '_method': e.target[1].value,
                    'name': e.target[2].value,
                    'slug': e.target[3].value
                };
                const formAction = '/categories/edit/' + formData.slug;

                $.ajax({
                    url: formAction,
                    type: 'PUT',
                    data: formData,
                    success: (response, status, xhr) => {
                        $('#editCategoryModal').modal('hide');
                        $('#editCategoryForm').trigger('reset');
                        $('#tableWrapperCategory').addClass('d-none');
                        $('#tableListArticles').DataTable().destroy();

                        toast('success', response.message);
                        loadListCategory();
                    },
                    error: (xhr, status) => {
                        console.log(xhr);
                        if (xhr.status <= 404) {
                            return toast('warning', xhr.responseJSON.message);
                        }
                        return toast('error', 'Internal server error');
                    }
                });
            });
        });

        const loadTable = (category) => {
            $('#tableWrapperCategory').removeClass('d-none');

            $('#tableListArticles').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('categories.get.table') }}" + '?category=' + category,
                    type: "GET",
                    data: (val) => {
                        // console.log(val);
                    },
                    dataSrc: (json) => {
                        // console.log("Data received from server:", json.extraData);
                        $('#columnName').text(json.extraData.name);
                        $('#columnDirilis').text(json.extraData.publish_articles_count);
                        $('#columnDidraft').text(json.extraData.draft_articles_count);
                        $('#columnTotal').text(json.extraData.total_articles);
                        $('#deleteButton').attr('data-category', json.extraData.slug);
                        $('#editButton').attr('data-category', json.extraData.slug);
                        $('#editCategoryForm #nameEdit').val(json.extraData.name);
                        $('#editCategoryForm #slugEdit').val(json.extraData.slug);

                        return json.data;
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'no.',
                        className: 'text-center',
                        render: (data, type, row, meta) => {
                            return meta.row + meta.settings._iDisplayStart + 1 + '.';
                        },
                    },
                    { data: 'title', name: 'judulArtikel'},
                    {
                        data: 'is_draft',
                        name: 'status',
                        render: (data, type, row, meta) => {
                            const status = data ? 'Draft' : 'Dirilis';
                            return status;
                        }
                    },
                    {
                        data: 'user',
                        name: 'penulis',
                        render: (data, type, row, meta) => {
                            return data.username;
                        }
                    }
                ]
            });
        };

        const loadListCategory = () => {
            $.ajax({
                url: @json(route('categories.get.list')),
                type: 'GET',
                beforeSend: () => {
                    $('#selectCategory').html(
                        '<option selected disabled>Loading...</option>'
                    ).prop('disabled', true);
                },
                success: (response, status, xhr) => {
                    const {data: {categories}} = response;
                    let optionTags = '<option selected disabled>Pilih Kategori</option>';

                    $.each(categories, (index, val) => {
                        optionTags += `<option value='${val.slug}'>${val.name}</option>`;
                    });

                    $('#selectCategory').html(optionTags).prop('disabled', false);
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
