@extends('dashboard.main', ['title' => $title])
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
                                    <p class="rate-percentage fs-3">32</p>
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
                                    <p class="rate-percentages fs-3">18</p>
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
                                    <p class="rate-percentage fs-3">20</p>
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
                                                    <span class="text-small me-2">
                                                        -- 01 September 2024
                                                    </span>
                                                    <span class="badge badge-opacity-success me-3">
                                                        Dirilis
                                                    </span>
                                                </p>
                                                <div class="list-wrapper">
                                                    <ul class="todo-list todo-list-rounded">
                                                        <li class="d-block border-bottom-0">
                                                            <div class="form-check m-0 w-100">
                                                                <a href="#" class="form-check-label ms-0 text-decoration-none fw-bold">
                                                                    Lorem Ipsum is simply dummy text of the sit amet mala kesem dolor kesemsem ning color wirange
                                                                    <i class="input-helper rounded"></i>
                                                                </a>
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
@endsection
@section('scripts')
    <script>

    </script>
@endsection
