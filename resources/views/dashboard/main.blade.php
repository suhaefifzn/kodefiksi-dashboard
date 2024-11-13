<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kode Fiksi {{ isset($title) ? ' - ' . $title : '' }}</title>

    {{-- Favicons --}}
    <link rel="icon" type="image/png" href="/assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="/assets/favicon/site.webmanifest" />

    <link rel="stylesheet" href="/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="/assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/select.dataTables.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/datatables.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/css/my.css">

    {{-- CSS --}}
    @yield('style')

  </head>
  <body class="with-welcome-text">
      @include('dashboard.navbar')
      <div class="container-fluid page-body-wrapper">
        @include('dashboard.sidebar')
        <div class="main-panel">
          @yield('content')
          @include('dashboard.footer')
        </div>
      </div>
    </div>

    <script src="/assets/js/jquery.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="/assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="/assets/js/off-canvas.js"></script>
    <script src="/assets/js/template.js"></script>
    <script src="/assets/js/settings.js"></script>
    <script src="/assets/js/hoverable-collapse.js"></script>
    <script src="/assets/js/todolist.js"></script>
    <script src="/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="/assets/js/dashboard.js"></script>
    {{-- <script src="assets/js/Chart.roundedBarCharts.js"></script> --}}

    <script src="/assets/js/datatables.min.js"></script>
    <script src="/assets/js/datatables.bootstrap5.min.js"></script>

    @include('utils.sweetalert-js')
    @include('utils.greeting-js')
    @yield('scripts')

    <script>
        $(document).ready(() => {
            $('#signOutButton').on('click', () => {
                window.location.href = '/logout';
            });

            generateQuotes();
        });

        const generateQuotes = () => {
            $.ajax({
                url: 'https://api.api-ninjas.com/v1/quotes?category=success',
                type: 'GET',
                headers: {
                    'X-Api-Key': @json(config('app.my_config.api_ninjas_token'))
                },
                beforeSend: () => {
                    $('#greetingSub').addClass('skeleton-text')
                },
                success: (response, status, xhr) => {
                    const { quote } = response[0];
                    $('#greetingSub').text(quote).removeClass('skeleton-text');
                },
                error: (xhr) => {
                    // console.log(xhr);
                    $('#greetingSub')
                        .text('Good luck and have fun')
                        .removeClass('skeleton-text');
                }
            });
        }
    </script>
  </body>
</html>
