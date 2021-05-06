<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="{{ url('main.css')}}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ url('assets-login/images/icons/favicon.png')}}"/>
    <link href="{{ asset('assets/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>

                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">

                            <div class="app-footer-right">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <strong>Muh. Fitra Rizki 1718053 &copy; Copyright 2021</strong>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ url('assets/scripts/main.js') }}"></script>
{{-- <script type="text/javascript" src="{{ url('assets/scripts/jquery.min.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!-- Page level plugins -->
<script src="{{ url('assets/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/demo/datatables-demo.js') }}"></script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

@yield('script')

</body>
</html>

@yield('modal')
