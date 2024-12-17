<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="robots" content="noindex, nofollow">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kode Fiksi {{ isset($title) ? ' - ' . $title : '' }}</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    {{-- Favicons --}}
    <link rel="icon" type="image/png" href="/assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="/assets/favicon/site.webmanifest" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo fs-3 fw-bold">
                    <span class="text-dark">
                        Kode
                    </span>
                    <span class="text-primary">
                        Fiksi
                    </span>
                </div>
                <h4>Hello! Selamat datang.</h4>
                <h6 class="fw-light">Silahkan Sign In untuk melanjutkan ke Dashboard.</h6>
                <form class="pt-3" action="{{ route('auth.login') }}" method="POST" id="signinForm">
                    @csrf
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" required>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <button class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">SIGN IN</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="/assets/js/jquery.js"></script>
    @include('utils.sweetalert-js')
    <script>
        $(document).ready(() => {
            $('#signinForm').on('submit', (e) => {
                e.preventDefault();
                const formData = {
                    _token: e.target[0].value,
                    email: e.target[1].value,
                    password: e.target[2].value,
                };
                const formAction = e.currentTarget.action;

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    success: (response, status, xhr) => {
                        const url = response.url;
                        location.href = url;
                    },
                    error: (xhr, status) => {
                        return toast('error', xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
  </body>
</html>
