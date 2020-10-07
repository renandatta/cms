@php
use Illuminate\Support\Facades\Session;
$menu_aktif = function ($route) {
    $menu_aktif = Session::get('menu_aktif') ?? '';
    return $menu_aktif == $route ? 'active' : '';
}
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') {{ env('APP_NAME') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('lemon.png') }}" />

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    @stack('styles')
</head>
<body>
    @auth
        <nav class="navbar navbar-expand-lg navbar-dark py-0" style="background-color: #2e3351;">
            <div class="container-fluid">
                <div class="navbar-brand">
                    <img src="{{ asset('lemon.png') }}" height="30" width="30" alt="">
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item {{ $menu_aktif('dashboard') }}">
                            <a class="nav-link" href="{{ route('admin') }}">Dashboard</a>
                        </li>
                        <li class="nav-item {{ $menu_aktif('admin.user') }}">
                            <a class="nav-link" href="{{ route('admin.user') }}">User</a>
                        </li>
                        <li class="nav-item {{ $menu_aktif('admin.halaman') }}">
                            <a class="nav-link" href="{{ route('admin.halaman') }}">Halaman</a>
                        </li>
                        <li class="nav-item {{ $menu_aktif('admin.konten') }}">
                            <a class="nav-link" href="{{ route('admin.konten') }}">Konten</a>
                        </li>
                        <li class="nav-item {{ $menu_aktif('admin.kategori') }}">
                            <a class="nav-link" href="{{ route('admin.kategori') }}">Kategori</a>
                        </li>
                        <li class="nav-item {{ $menu_aktif('admin.berita') }}">
                            <a class="nav-link" href="{{ route('admin.berita') }}">Berita</a>
                        </li>
                        <li class="nav-item {{ $menu_aktif('admin.pesan') }}">
                            <a class="nav-link" href="{{ route('admin.pesan') }}">Pesan</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                {{ \Illuminate\Support\Facades\Auth::user()->nama }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item text-right" href="{{ route('logout') }}">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth
    <div class="container pb-5">
        @yield('content')
    </div>
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/autoNumeric.js') }}"></script>
    <script src="{{ asset('lib/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('lib/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        $('.select2').select2();
        let $autonumeric = $('.autonumeric'),
            $autonumericDecimal = $('.autonumeric-decimal');
        $autonumeric.attr('data-a-sep','.');
        $autonumeric.attr('data-a-dec',',');
        $autonumeric.autoNumeric({mDec: '0',vMax:'9999999999999999999999999', vMin: '-99999999999999999'});
        $autonumericDecimal.attr('data-a-sep','.');
        $autonumericDecimal.attr('data-a-dec',',');
        $autonumericDecimal.autoNumeric({mDec: '2',vMax:'999'});
        $('.datepicker').datepicker({
            format:'dd-mm-yyyy',
            autoclose:true
        });
        function add_commas(nStr) {
            nStr += '';
            let x = nStr.split('.');
            let x1 = x[0];
            let x2 = x.length > 1 ? '.' + x[1] : '';
            let rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }
        function remove_commas(nStr) {
            nStr = nStr.replace(/\./g, '');
            return nStr;
        }
    </script>
    @stack('scripts')
</body>
</html>
