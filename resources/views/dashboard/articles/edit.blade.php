@extends('dashboard.main', [ 'title' => $title ])
@section('style')
    <link rel="stylesheet" href="/assets/vendors/ckeditor5/ckeditor5.css">
    <link rel="stylesheet" href="/assets/vendors/ckeditor5/ckeditor5-content.css">
    <link rel="stylesheet" href="/assets/vendors/ckeditor5/ckeditor5-editor.css">
    <style>
        #wordCount .ck.ck-word-count {
            display: flex;
            gap: 1em;
            margin-top: 10px;
            font-size: 14px;
            border-left: 5px solid black;
            padding-left: 8px;
        }

        .ck-editor__editable_inline {
            height: 500px;
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
                    <form action="POST" id="editArticleForm">
                        <input type="hidden" name="slug" id="slug" value="{{ $data['article']['slug'] }}">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="title" value="{{ $data['article']['title'] }}" autocomplete="off" required>
                            <label for="title">Judul</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="category" required>
                              <option selected disabled>Klik untuk membuka pilihan</option>
                              @foreach ($data['categories'] as $category)
                                @if ($data['article']['category_id'] == $category['id'])
                                    <option value="{{ $category['id'] }}" selected>{{ $category['name'] }}</option>
                                @else
                                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                @endif
                              @endforeach
                            </select>
                            <label for="category">Kategori</label>
                        </div>
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input class="form-control" type="file" accept=".png,.jpg,.webp" id="thumbnail" autocomplete="off">
                            <div id="previewThumbnailWrapper" style="width: 100%; max-height: 1080px" class="border border-primary rounded border-opacity-25 overflow-hidden text-center my-3">
                                <img src="{{ $data['article']['img_thumbnail'] }}" alt="Preview Thumbnail" class="img-fluid" id="previewThumbnail" style="height: 100%;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-dark" id="sisipkanGambarModalButton">
                                Sisipkan Gambar Lainnya
                            </button>
                        </div>
                        <div class="mb-3 col-12 col-xl-8">
                            <textarea name="body" id="body">{{ $data['article']['body'] }}</textarea>
                            <div id="wordCount"></div>
                        </div>
                        <div class="mb-3 col-12 col-xl-8 d-flex justify-content-end gap-2">
                            <button class="btn btn-primary" type="button" data-draft="false" onclick="editArticle(this)">
                                Publish
                            </button>
                            <button class="btn btn-warning" type="button" data-draft="true" onclick="editArticle(this)">
                                Draft
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Sisipkan Gambar Lainnya --}}
<div class="modal fade" id="sisipkanGambarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sisipkanGambarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="sisipkanGambarModalLabel">Sisipkan Gambar Lainnya</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('articles.body.images.add') }}" method="POST" enctype="multipart/form-data" id="addBodyImageForm">
                    <div class="input-group">
                        <input type="file" accept=".png,.jpg,.webp" class="form-control" id="uploadBodyImageInput" aria-describedby="uploadBodyImageButton" aria-label="Upload" autocomplete="off">
                        <button class="btn btn-primary border-0" id="uploadBodyImageButton" title="Upload Image">
                            <i class="fa fa-upload"></i>
                        </button>
                    </div>
                </form>
                <hr>
                <table class="table table-hover mt-5" id="bodyImagesTable">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Path</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Gambar --}}
<div class="modal fade" id="detailGambarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailGambarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="detailGambarModalLabel">Detail Gambar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="imageWrapper2" class="width: 100%; position: relative">
                    <img class="img-fluid" style="width: 100%; object-fit: cover; object-position: center">
                </div>
                <div id="pathWrapper" class="my-3">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm text-muted" id="imgUrlInput" readonly>
                        <button class="btn btn-sm btn-dark border-0" type="button" id="copyImgUrlButton">
                            <i class="fa fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "/assets/vendors/ckeditor5/ckeditor5.js",
            "ckeditor5/": "/assets/vendors/ckeditor5/"
        }
    }
</script>

<script>
    let ckEditor = null;
</script>

<script type="module">
    import {
        ClassicEditor,
        GeneralHtmlSupport,
        AccessibilityHelp,
        Bold,
        Essentials,
        Italic,
        Mention,
        Paragraph,
        SelectAll,
        Undo,
        Font,
        Link,
        BlockQuote,
        Alignment,
        Heading,
        SourceEditing,
        Image,
        ImageInsert,
        CodeBlock,
        List,
        Table,
        TableToolbar,
        WordCount
    } from 'ckeditor5';

    ClassicEditor
        .create( document.querySelector( '#body' ), {
            plugins: [
                GeneralHtmlSupport,
                AccessibilityHelp,
                Bold,
                Essentials,
                Italic,
                Mention,
                Paragraph,
                SelectAll,
                Undo,
                Font,
                Link,
                BlockQuote,
                Alignment,
                Heading,
                SourceEditing,
                Image,
                ImageInsert,
                CodeBlock,
                List,
                Table,
                TableToolbar,
                WordCount
            ],
            toolbar: [
                'undo', 'redo',
                '|', 'sourceEditing', 'heading', 'alignment', 'bulletedList', 'numberedList',
                '|', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
                '|', 'bold', 'italic', 'link', 'blockquote',
                '|', 'insertImageViaUrl', 'codeBlock', 'insertTable',
            ],
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            },
            table: {
                contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
            }
        }).then((editor) => {
            const wordCountPlugin = editor.plugins.get( 'WordCount' );
            const wordCountWrapper = document.getElementById( 'wordCount');
            wordCountWrapper.appendChild( wordCountPlugin.wordCountContainer );
            ckEditor = editor;
        });
</script>

<script>
    $(document).ready(() => {
        // reset datatable body images
        let table = null;

        $('#sisipkanGambarModal').on('hidden.bs.modal', function () {
            if ($.fn.DataTable.isDataTable('#bodyImagesTable')) {
                $('#bodyImagesTable').DataTable().destroy();
            }
        });

        const menuArtikel = $('a[href="#type"]')[0];
        $(menuArtikel.parentElement).addClass('active');

        $('#thumbnail').on('change', (e1) => {
            const file = e1.currentTarget.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = (e2) => {
                    $('#previewThumbnail').attr('src', event.target.result);
                    $('#previewThumbnailWrapper').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // open modal sisipkan gambar
        $('#sisipkanGambarModalButton').on('click', (e) => {
            e.preventDefault();
            table = $('#bodyImagesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: @json(route('articles.body.images.table')),
                    type: 'GET',
                    data: (val) => {
                        // console.log(val);
                    },
                    dataSrc: (json) => {
                        // console.log('Data received from server:', json);
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
                        searchable: false
                    },
                    {
                        data: 'path',
                        name: 'path'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        class: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#sisipkanGambarModal').modal('show');
        });

        // upload body image
        $('#addBodyImageForm').on('submit', (e) => {
            e.preventDefault();
            const data = {
                '_token': @json(csrf_token()),
                'image': e.currentTarget[0].files[0],
            };
            const formAction = e.currentTarget.action;
            const formData = new FormData();

            $.each(data, (key, value) => {
                formData.append(key, value);
            });

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                cache: false,
                processData:false,
                contentType: false,
                success: (response, status, xhr) => {
                    toast('success', response.message, 2000);
                    table.ajax.reload(null, false);
                },
                error: (xhr, status) => {
                    console.log(xhr);

                    if (xhr.status <= 404) {
                        return toast('warning', xhr.responseJSON.message);
                    }

                    if (xhr.status == 422) {
                        return toast('warning', 'Ensure the file is an image in .png or .jpg format and is less than 2MB in size');
                    }

                    return toast('error', 'Internal server error');
                }
            });
        });

        // copy img url button clicked
        $('#copyImgUrlButton').on('click', (e) => {
            e.preventDefault();
            $('#imgUrlInput').select();
            document.execCommand('copy');
            toast('success', 'The image URL has been successfully copied');
        });
    });

    const showImage = (element) => {
        const { path } = element.dataset;
        const imgUrl = @json(config('app.my_config.api_url')) + '/' + path;

        $('#imgUrlInput').val(imgUrl);
        $('#imageWrapper2 img').attr('src', imgUrl).attr('loading', 'lazy');
        $('#detailGambarModal').modal('show');
    };

    const editArticle = (element) => {
        const { draft } = element.dataset;
        const slug = $('#editArticleForm #slug').val();
        const bodyContent = ckEditor.getData();

        const data = {
            '_token': @json(csrf_token()),
            '_method': 'put',
            'title': $('#editArticleForm #title').val(),
            'category_id': $('#editArticleForm #category').find(':selected').val(),
            'img_thumbnail': $('#editArticleForm #thumbnail')[0].files[0],
            'is_draft': draft,
            'body': bodyContent,
            'slug': slug
        };
        const formData = new FormData();

        $.each(data, (key, val) => {
            formData.append(key, val);
        });

        $.ajax({
            url: @json(route('articles.update')),
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: (response, status, xhr) => {
                return toast('success', response.message, 2000)
                    .then(() => {
                        location.href = @json(route('articles.index')) + '?is_draft=' + draft;
                    });
            },
            error: (xhr, status) => {
                console.log(xhr);

                if (xhr.status <= 404) {
                    return toast('warning', xhr.responseJSON.message);
                }

                if (xhr.status == 422) {
                    return toast('warning', 'Ensure the file is an image in .png or .jpg format and is less than 2MB in size');
                }

                return toast('error', 'Internal server error');
            }
        });
    };
</script>
@endsection
