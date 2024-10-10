@extends('dashboard.main', ['title' => $title])
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
        <div class="home-tab">
            <div class="d-flex row align-items-center justify-content-around border-bottom border-top pt-3">
                <div class="col-sm-12 col-md-6 col-lg-6 statistics-details d-flex justify-content-around">
                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <p class="statistics-title card-title">Artikel Anime</p>
                            <div class="media-wrapper d-flex align-items-center">
                                <div class="media">
                                    <i class="ti-gallery icon-md d-flex align-self-start me-2"></i>
                                </div>
                                <div class="media-body">
                                    <p class="rate-percentage fs-3">{{ $data['stats'][0]['articles_count'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <p class="statistics-title card-title">Artikel Game</p>
                            <div class="media-wrapper d-flex align-items-center">
                                <div class="media">
                                    <i class="ti-game icon-md d-flex align-self-start me-2"></i>
                                </div>
                                <div class="media-body">
                                    <p class="rate-percentages fs-3">{{ $data['stats'][1]['articles_count'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <p class="statistics-title card-title">Artikel Pemrograman</p>
                            <div class="media-wrapper d-flex align-items-center">
                                <div class="media">
                                    <i class="ti-shortcode icon-md d-flex align-self-start me-2"></i>
                                </div>
                                <div class="media-body">
                                    <p class="rate-percentage fs-3">{{ $data['stats'][2]['articles_count'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Artikel Terakhir --}}
                <div class="col-sm-12 col-md-6 col-lg-6 statistics-details d-flex flex-column pt-4">
                    <div class="row flex-grow">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="d-flex flex-column justify-content-between align-items-start overflow-hidden">
                                                <p class="statistics-title card-tiltle card-title-dash">
                                                    <span>
                                                        Artikel Terakhir
                                                    </span>
                                                    @if (isset($data['last_article']))
                                                        <span class="text-small me-2">
                                                        @php
                                                            $date = new DateTime($data['last_article']['created_at']);
                                                            $formattedDate = $date->format('d/m/Y');
                                                        @endphp
                                                        -- {{ $formattedDate }}
                                                        </span>
                                                        @if ($data['last_article']['is_draft'])
                                                            <span class="badge badge-opacity-warning me-3">
                                                                Didraft
                                                            </span>
                                                        @else
                                                            <span class="badge badge-opacity-success me-3">
                                                                Dirilis
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-small me-2">
                                                            -
                                                        </span>
                                                    @endif
                                                </p>
                                                <div class="list-wrapper">
                                                    <ul class="todo-list todo-list-rounded">
                                                        <li class="d-block border-bottom-0">
                                                            <div class="form-check m-0 w-100">
                                                                @if (isset($data['last_article']))
                                                                    <a href="#" data-slug="{{ $data['last_article']['slug'] }}" class="form-check-label ms-0 text-decoration-none fw-bold" onclick="showArticle(this)">
                                                                        {{ $data['last_article']['title'] }}
                                                                        <i class="input-helper rounded"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    const showArticle = (element) => {
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

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        const day = ("0" + date.getDate()).slice(-2);
        const month = ("0" + (date.getMonth() + 1)).slice(-2);
        const year = date.getFullYear();

        return day + '/' + month + '/' + year;
    };
</script>
@endsection
