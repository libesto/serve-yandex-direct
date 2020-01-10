@extends('layouts.panel')

@section('content')
    <div class="container-fluid">
        <div class="row">

            @if(session()->has('success'))
                <div class="col-12">
                    <div class="alert alert-success top-success-msg" role="alert">
                        <button class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
    
            @if(session()->has('danger'))
                <div class="col-12">
                    <div class="alert alert-danger top-danger-msg" role="alert">
                        <button class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('danger') }}
                    </div>
                </div>
            @elseif($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger top-danger-msg" role="alert">
                        <button class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        Ошибка при заполнении полей
                    </div>
                </div>
            @endif
    
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Основные данные</h4>
                        <form action="{{ route('profile-update-data') }}" method="post" class="profile-form">
                            @csrf
                            <div class="input-group mb-3"><span class="input-group-prepend"><span class="input-group-text">Имя</span></span>
                                <input type="text" placeholder="" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $user->name }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group mb-3"><span class="input-group-prepend"><span class="input-group-text">Email</span></span>
                                <input type="email" placeholder="" class="form-control" value="{{ $user->email }}" disabled>
                            </div>
                            <button class="btn btn-primary" type="submit">Обновить данные</button>
                        </form>
    
                        <h4 class="mt-4">Пароль</h4>
                        <form action="{{ route('profile-update-password') }}" method="post" class="profile-form">
                            @csrf
                            <div class="input-group mb-3"><span class="input-group-prepend"><span class="input-group-text">Текущий пароль</span></span>
                                <input type="password" placeholder="" class="form-control @error('current_password') is-invalid @enderror" name="current_password" value="">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group mb-3"><span class="input-group-prepend"><span class="input-group-text">Новый пароль</span></span>
                                <input type="password" placeholder="" class="form-control @error('new_password') is-invalid @enderror" name="new_password" value="">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-group mb-3"><span class="input-group-prepend"><span class="input-group-text">Подтверждение пароля</span></span>
                                <input type="password" placeholder="" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" value="">
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-primary" type="submit">Обновить пароль</button>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                /**
                 * Hide top messages in 2 seconds
                 */
                $(document).ready(function(){
                    setTimeout(function(){
                        $('.top-success-msg').slideUp(400);
                        $('.top-danger-msg').slideUp(400);
                    }, 2000);
                });
            </script>
            
        </div>
    </div>
@endsection
