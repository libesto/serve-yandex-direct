@extends('layouts.panel')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        Профиль
                    </div>
                    <div class="card-body p-1">
                        <p class="card-text text-center home-icon">
                            <i class="fa fa-fw fa-address-card"></i>
                        </p>
                    </div>
                    <div class="card-footer p-0 text-center d-flex justify-content-center ">
                        <div class="card-footer-item card-footer-item-bordered px-1">
                            <a href="{{ route('profile') }}" class="card-link">Перейти в настройки профиля</a>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        Яндекс Директ
                    </div>
                    <div class="card-body p-1">
                        <p class="card-text text-center home-icon">
                            <i class="fas fa-fw fa-file"></i>
                        </p>
                    </div>
                    <div class="card-footer p-0 text-center d-flex justify-content-center ">
                        <div class="card-footer-item card-footer-item-bordered px-1">
                            <a href="{{ route('yandex-review') }}" class="card-link">Статистика</a>
                        </div>
                        <div class="card-footer-item card-footer-item-bordered px-1">
                            <a href="{{ route('yandex-run') }}" class="card-link">Запуск</a>
                        </div>
                        <div class="card-footer-item card-footer-item-bordered px-1">
                            <a href="{{ route('yandex-settings') }}" class="card-link">Настройки</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
