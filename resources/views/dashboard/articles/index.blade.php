@extends('dashboard.main', [ 'title' => $title ])
@section('style')
<style>
    #article p {
        margin-top: 1em;
        margin-bottom: 1em;
        font-size: 0.9em;
    }

    #article figure.image {
        width: 100%;
        position: relative;
    }

    #article figure img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
</style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ $title }}</div>
                        <hr>

                        <div class="button-wrapper mb-5">
                            <button class="btn btn-primary px-3 d-flex align-content-center gap-2" id="addArticleButton">
                                <i class="fa fa-plus"></i>
                                Tambah Artikel
                            </button>
                        </div>

                        <form class="d-none">
                            <input type="hidden" name="query_is_draft" id="queryIsDraft" value="{{ $data['query']['is_draft'] }}">
                        </form>

                        <table class="table table-hover" id="articlesTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Judul</th>
                                    <th>Author</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Show Article --}}
    <div class="modal fade" id="showArticleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showArticleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showArticleModalLabel">Detail Artikel</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="showArticleForm">
                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" id="status" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" id="judul" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <input type="text" id="kategori" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Dibuat</label>
                                <input type="text" id="tanggal" class="form-control">
                            </div>
                            <div class="mb-5">
                                <label for="thumbnail" class="form-label">Thumbnail</label>
                                <div style="height: 350px; position: relative" class="overflow-hidden rounded">
                                    <img alt="Image Thumbnail" class="img-fluid" style="object-fit: cover; position: center; width: 100%; height: 100%" id="thumbnail" loading="lazy">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="article" class="form-label">Body</label>
                                <section class="px-3 rounded overflow-hidden" style="background-color: var(--bs-secondary-bg); position: relative" id="article">
                                </section>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let table = null;

        $(document).ready(() => {
            const queryIsDraft = $('#queryIsDraft').val();

            // initialize datatable
            table = $('#articlesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('articles.table') . '?is_draft=' }}" + queryIsDraft,
                    type: "GET",
                    data: (val) => {
                        // console.log(val);
                    },
                    dataSrc: (json) => {
                        // console.log("Data received from server:", json);
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
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'user',
                        name: 'username',
                        orderable: false,
                        searchable: false,
                        render: (data, type, row, meta) => {
                            return data.username;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: true,
                        searchable: false,
                        render: (data, type, row, meta) => {
                            const formattedDate = formatDate(data);
                            return formattedDate;
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // add article button clicked
            $('#addArticleButton').on('click', (e) => {
                e.preventDefault();
                location.href = @json(route('articles.create'));
            })
        });

        const deleteArticle = (element) => {
            const { slug, lang } = element.dataset;

            alertConfirm('warning', 'Are you sure? You won\'t be able to revert this!')
                .then((results) => {
                    if (results.isConfirmed) {
                        $.ajax({
                            url: @json(route('articles.delete')),
                            type: 'DELETE',
                            data: {
                                '_token': @json(csrf_token()),
                                'slug': slug,
                                'lang_id': lang
                            },
                            success: (response, status, xhr) => {
                                toast('success', 'Article has been deleted!', 2000);
                                table.ajax.reload(null, false);
                            },
                            error: (xhr, status) => {
                                console.log(xhr);
                                if (xhr.status < 500) {
                                    return toast('warning', xhr.responseJSON.message);
                                }
                                return toast('error', 'Internal server error');
                            }
                        })
                    }
                });
        };

        const showArticle = (element) => {
            /**
             * get data detail artikel dulu
             * jika berhasil tampilkan dalam modal
             **/
            const { slug } = element.dataset;
            const url = '/articles/' + slug;

            $.ajax({
                url,
                type: 'GET',
                success: (response, status, xhr) => {
                    const { data: article } = response;
                    const articleStatus = article.is_draft ? 'Draft' : 'Rilis';

                    $('#showArticleForm #status').val(articleStatus);
                    $('#showArticleForm #judul').val(article.title);
                    $('#showArticleForm #kategori').val(article.category.name);
                    $('#showArticleForm #tanggal').val(formatDate(article.created_at));
                    $('#showArticleForm #thumbnail').attr('src', article.img_thumbnail);
                    $('#showArticleForm #article').html(article.body);
                    $('#showArticleForm #article img').attr('loading', 'lazy');

                    $('#showArticleModal').modal('show');
                },
                error: (xhr, status) => {
                    if (xhr.status <= 404) {
                        return toast('warning', xhr.responseJSON.message);
                    }
                    return toast('error', 'Internal server error');
                }
            })
        };

        const editArticle = (element) => {
            const { slug } = element.dataset;
            const url = '/articles/edit/' + slug;
            location.href = url;
        };

        const formatDate = (dateString) => {
            const date = new Date(dateString);
            const day = ("0" + date.getDate()).slice(-2);
            const month = ("0" + (date.getMonth() + 1)).slice(-2);
            const year = date.getFullYear();

            return day + '/' + month + '/' + year;
        };
    </script>
@endsection
