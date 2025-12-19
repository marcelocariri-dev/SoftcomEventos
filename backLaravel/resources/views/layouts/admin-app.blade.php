<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-base" content="{{ url('') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="{{ asset('bootstrap/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/toastr.min.css') }}">
</head>

<body>
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="" class="user-image img-circle elevation-2" alt="Teste ALT" title="Teste TITLE">
                        <span class="d-none d-md-inline">
                            {{ Auth::user()->name }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <li class="user-header bg-primary">
                            <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">

                            <p>
                                {{ Auth::user()->name }} - {{ Auth::user()->email }}
                            </p>
                        </li>
                        <li class="user-footer">
                            <a class="btn btn-default btn-flat float-right" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                            >
                                Sair
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>


                        </li>
                    </ul>
                </li>
            </ul>
        </nav>


        <aside class="main-sidebar sidebar-dark-primary elevation-4">

            <a href="index3.html" class="brand-link">
                <!--<img src="dist\img\AdminLTELogo" alt="AdminLTELogo" class="brand-image img-circle elevation-3"  style="opacity: .8">-->
                <img alt="image" class="img-responsive brand-image logo-softcom animated swing" src="https://softcomshop.s3-us-west-2.amazonaws.com/img/logo.png">
                <span class="brand-text font-weight-light">SOFTCOM</span>
            </a>

            <div class="sidebar">


                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ url("clientes") }}" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Clientes
                                </p>
                            </a>
                        </li>
                    </ul>
                   
                </nav>
                <nav>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ url("grupos") }}" class="nav-link">
                                <i class=" nav-icon fas fa-layer-group"></i>
                                <p>
                                    Grupo
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>

        </aside>


        <div class="content-wrapper" style="padding: 40px">
            @if (Route::has('Registerclientes'))
            <div class="top-right links">
           @auth
               
         @if (Route::has('registerClientes'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </div>
        @endif

            @yield("content")
        </div>

    </div>


            <script src="{{ asset('js/jquery.min.js') }}"></script>
            <script src="{{ asset('js/popper.min.js') }}"></script>
            <script src="{{ asset('js/bootstrap.min.js') }}"></script>
            <script src="{{ asset('js/adminlte.min.js') }}"></script>
            <script src="{{ asset('js/toastr.min.js') }}"></script>
            <script>
                var csrfToken = $("meta[name='csrf-token']").attr("content");
                var urlBase = $("meta[name='url-base']").attr("content");
            </script>
            @stack("js")
            {!! Toastr::message() !!}
</body>

</html>
