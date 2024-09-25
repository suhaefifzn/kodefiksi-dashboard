<nav class="sidebar sidebar-offcanvas" id="sidebar">
<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="mdi mdi-home menu-icon"></i>
            <span class="menu-title">Beranda</span>
        </a>
    </li>
    <li class="nav-item nav-category">Menu</li>
        <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
            <i class="menu-icon mdi mdi-file-document"></i>
            <span class="menu-title">Artikel</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('articles.publish') }}"> Dirilis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('articles.draft') }}"> Disimpan</a>
                </li>
            </ul>
        </div>
    </li>

    @if (session('user_admin'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('categories') }}">
                <i class="menu-icon mdi mdi-shape"></i>
                <span class="menu-title">Kategori</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users') }}">
                <i class="menu-icon mdi mdi-account-group"></i>
                <span class="menu-title">Pengguna</span>
            </a>
        </li>
    @endif

    <li class="nav-item">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="menu-icon mdi mdi-account"></i>
            <span class="menu-title">Profil</span>
        </a>
    </li>
</ul>
</nav>
