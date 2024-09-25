<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
    </div>
    <div>
        <a class="navbar-brand brand-logo fw-bold" href="{{ route('home') }}">
            <span class="text-dark">
                Kode
            </span>
            <span class="text-primary">
                Fiksi
            </span>
        </a>
        <a class="navbar-brand brand-logo-mini fw-bold" href="{{ route('home') }}">
            <img src="/assets/images/logo.png" alt="Kode Fiksi Logo">
        </a>
    </div>
</div>
<div class="navbar-menu-wrapper d-flex align-items-top">
    <ul class="navbar-nav">
    <li class="nav-item fw-semibold d-none d-lg-block ms-0">
        <h1 class="welcome-text"><span id="greeting">...</span>, <span class="text-black fw-bold">{{ session('user_name') }}</span></h1>
        <h3 class="welcome-sub-text">Semoga aktivitasmu berjalan lancar dan menyenangkan</h3>
    </li>
    </ul>
    <ul class="navbar-nav ms-auto">
    <li class="nav-item dropdown d-none d-lg-block user-dropdown">
        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
        <img class="img-xs rounded-circle" src="{{ session('user_img') }}" alt="Profile image"> </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
        <div class="dropdown-header text-center">
            <div class="mx-auto mt-3" style="width: 75px; height: 75px">
                <img class="img-md rounded-circle" src="{{ session('user_img') }}" alt="Profile image" style="object-fit:cover; width: 100%; height: 100%;">
            </div>
            <p class="mb-1 mt-3 fw-semibold">Suhaefi Fauzian</p>
        </div>
        <a class="dropdown-item" href="{{ route('profile') }}"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
        <button class="dropdown-item" id="signOutButton"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</button>
        </div>
    </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
    <span class="mdi mdi-menu"></span>
    </button>
</div>
</nav>
