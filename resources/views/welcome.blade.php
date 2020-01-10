<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
        <!-- Description -->
        <meta name="description" content="AdMount — автоматизация Яндекс Директ. Запуск / остановка объявлений: в зависимости от содержания страниц.">
    
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,700,700i&display=swap&subset=cyrillic" rel="stylesheet">
    
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet">
        <link href="{{ asset('css/main-public.css') }}" rel="stylesheet">
    
        <!-- app js -->
        <script src="{{ asset('js/app.js') }}"></script>
    
        <title>AdMount — автоматизация Яндекс Директ</title>
    
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        
            ym(56111119, "init", {
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true,
                webvisor:true
            });
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/56111119" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
        
    </head>
    
    <body>
        <!--================ start banner Area =================-->
        <section class="home-banner-area">
            <div class="container">
                <div class="row justify-content-end fullscreen">
                    <div class="col-lg-6 col-md-12 home-banner-left d-flex fullscreen align-items-center">
                        <a class="navbar-brand home-logo" href="{{ url('/') }}">AdMount</a>
                        
                        <div class="text-left">
                            <h1 class="home-header">
                                Автоматизируй <span>Яндекс Директ</span> бесплатно!
                            </h1>
                            <p class="mx-auto mb-40 add-text text-body">
                                <i class="fa fa-check"></i> Запуск / остановка объявлений: в зависимости от содержания страниц
                            </p>
                            @auth
                                <a href="{{ route('panel') }}" class="primary-btn mb-3">Войти в панель</a>
                            @else
                                <a href="{{ route('login') }}" class="primary-btn mb-3">Вход</a>
                            
                                @if(Route::has('register'))
                                    <a href="{{ route('register') }}" class="primary-btn mb-3">Регистрация</a>
                                @endif
                            @endauth
                        
                            <div class="d-flex align-items-center mt-60">
                                <a id="play-home-video" class="video-play-button" href="https://www.youtube.com/watch?v=dmrGbFs88Mo">
                                    <span></span>
                                </a>
                                <div class="watch_video">
                                    <h5>Как это работает?</h5>
                                    <p>Смотри видео!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 no-padding home-banner-right d-flex fullscreen align-items-end">
                        <img class="img-fluid" src="{{ asset('images/header-img.png') }}" alt="">
                    </div>
                </div>
            </div>
        </section>
        <!--================ End banner Area =================-->

        <!--================ start footer Area  =================-->
        <footer class="footer-area section_gap">
            <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-12 col-sm-6">
                            <p class="footer-text m-0">© {{ now()->year }} AdMount, контакты: <a href="mailto:{{ config('global.email_info') }}">{{ config('global.email_info') }}</a></p>
                        </div>
                        <div class="col-12 col-sm-6">
                            {{--<!--noindex-->
                            <p class="footer-text m-0 colorlib-text">This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" rel="nofollow">Colorlib</a></p>
                            <!--/noindex-->--}}
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--================ End footer Area  =================-->
        
        <script src="{{ asset('js/main-public.js') }}"></script>
    </body>
</html>
