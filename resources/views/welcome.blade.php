<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/vendors/themify-icons/css/themify-icons.css')}}">

        <!-- Bootstrap + Dorang main styles -->
        <link rel="stylesheet" href="{{asset('assets/css/dorang.css')}}">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body  data-spy="scroll" data-target=".navbar" data-offset="40" id="home" class="dark-theme"> 
    
        <div class="flex-center position-ref">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/users') }}">DashBoard</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    
    <header class="header">
        <div class="overlay"></div>
        <div class="header-content">
            <h1 class="header-title">Bank Recovery</h1>
            <p class="header-subtitle"></p>


            @if (Route::has('login'))
                
                    @auth
                    <a href="{{ url('/users') }}" class="btn btn-theme-color modal-toggle"><i class="ti-control-play text-danger"></i> Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-theme-color modal-toggle"><i class="ti-control-play text-danger"></i> Login</a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-theme-color modal-toggle"><i class="ti-control-play text-danger"></i>Register</a>
                        @endif
                    @endauth
                
            @endif
            <!-- <button class="btn btn-theme-color modal-toggle"><i class="ti-control-play text-danger"></i> Watch Video</button> -->

        </div>
    </header><!-- end of page header -->

            <div class="content">
                <!-- page header -->
    



                <!-- <div class="title m-b-md">
                    Bank Recovery
                </div> -->

                <!-- <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div> -->
            </div>
        

        
    </body>
</html>
