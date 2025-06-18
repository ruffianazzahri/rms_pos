<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RMS POS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/backend.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/fonts/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/regular.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/solid.css') }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    @yield('specificpagestyles')
</head>

<body>
    <!-- loader Start -->
    {{-- <div id="loading">
        <div id="loading-center"></div>
    </div> --}}
    <!-- loader END -->

    <!-- Wrapper Start -->
    <div class="wrapper" style="background-color: #FEF3E2">
        @include('dashboard.body.sidebar')

        @include('dashboard.body.navbar')

        <div class="content-page">
            @yield('container')
        </div>
    </div>
    <!-- Wrapper End-->

    @include('dashboard.body.footer')

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/js/backend-bundle.min.js') }}"></script>
    <script src="https://kit.fontawesome.com/4c897dc313.js" crossorigin="anonymous"></script>

    @yield('specificpagescripts')

    <!-- App JavaScript -->

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/vendor/fontawesome/js/fontawesome.js') }}"></script>
    <script src="{{ asset('assets/vendor/fontawesome/js/regular.js') }}"></script>
    <script src="{{ asset('assets/vendor/fontawesome/js/solid.js') }}"></script>
    <script>
        const dayNames = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const monthNames = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    function updateDateTime() {
        const now = new Date();
        const day = dayNames[now.getDay()];
        const date = now.getDate();
        const month = monthNames[now.getMonth()];
        const year = now.getFullYear();

        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');

        const fullDate = `${day}, ${date} ${month} ${year} - ${hours}:${minutes}:${seconds}`;
        document.getElementById("datetime").innerText = fullDate;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
    </script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    @yield('scripts')


</body>

</html>