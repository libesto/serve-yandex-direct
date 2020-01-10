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
            
            @if(session()->has('checkUrlStatus'))
                <div class="col-12">
                    <div class="alert alert-primary" role="alert">
                        <button class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="alert-heading">Проверено!</h4>
                        <p>
                            URL: <strong>{{ session('checkUrlStatus')['url'] }}</strong><br>
                            Соответствует условиям: <strong>{{ session('checkUrlStatus')['result'] }}</strong><br>
                        </p>
                    </div>
                </div>
            @endif
            
            @if(!$connected)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4>Аккаунт Яндекса не привязан</h4>
                            <form action="{{ route('yandex-link-account') }}" method="post">
                                @csrf
                                <input type="text" placeholder="Логин Яндекс" class="form-control yandex-login-input @error('login') is-invalid @enderror" value="{{ $login }}" name="login">
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <button type="submit" class="btn btn-primary mt-2">Привязать аккаунт</button>
                            </form>
                        </div>
                    </div>
                </div>
            
            @else
                
                <div class="col-12">
                    <div class="pills-regular">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ session()->has('tab') ?: 'active show' }}" id="pills-campaigns-tab" data-toggle="pill" href="#pills-campaigns" role="tab" aria-controls="campaigns" aria-selected="true">Кампании</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (session()->has('tab') && session('tab') === 'scan') ? 'active show' : '' }}" id="pills-scan-tab" data-toggle="pill" href="#pills-scan" role="tab" aria-controls="scan" aria-selected="false">Условия сканирования</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (session()->has('tab') && session('tab') === 'connection') ? 'active show' : '' }}" id="pills-connection-tab" data-toggle="pill" href="#pills-connection" role="tab" aria-controls="connection" aria-selected="false">Связь с Яндекс Директ</a>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade {{ session()->has('tab') ?: 'active show' }}" id="pills-campaigns" role="tabpanel" aria-labelledby="pills-campaigns-tab">
                                <div class="">
                                    <h3>Загрузите и активируйте кампании для отслеживания</h3>
    
                                    <div class="yandex-campaign-buttons">
                                        <button class="yandex-campaign-buttons__item btn btn-primary" onclick="pullCamps();">Загрузить кампании</button>
                                        <!-- Modal Start -->
                                        <div class="modal fade" id="yandex-campaign-load-modal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Загрузка кампаний</h4>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <span class="dashboard-spinner spinner-md"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal End -->
                                        @if($campaigns)
                                            <button class="yandex-campaign-buttons__item btn btn-success" onclick="campChange(); activateCamps(); return false;">Включить все</button>
                                            <button class="yandex-campaign-buttons__item btn btn-danger" onclick="campChange(); deactivateCamps(); return false;">Отключить все</button>
                                            <button type="submit" form="campaigns" class="yandex-campaign-buttons__item btn btn-outline-primary camp_saving">Сохранить изменения</button>
                                        @endif
                                    </div>
                                    
                                    @if($campaigns)
                                        <form action="{{ route('yandex-save-campaigns') }}" method="post" id="campaigns">
                                            @csrf
                                            <table class="table table-hover yandex-campaigns-list">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Идентификатор</th>
                                                    <th scope="col">Название кампании</th>
                                                    <th scope="col">Статус</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($campaigns as $key => $campaign)
                                                    <tr>
                                                        <td>{{ $campaign['Id'] }}</td>
                                                        <td>{{ $campaign['Name'] }}</td>
                                                        <td>
                                                            <div class="switch-button switch-button-success" onclick="campChange();">
                                                                <input type="checkbox" name="{{ $key }}" id="{{ $key }}" value="1" @if($campaign['Status']) checked @endif>
                                                                <span><label for="{{ $key }}"></label></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade {{ (session()->has('tab') && session('tab') === 'scan') ? 'active show' : '' }}" id="pills-scan" role="tabpanel" aria-labelledby="pills-scan-tab">
    
                                <h3>Установите условия посадочных страниц, при которых объявления будут поддерживаться активными</h3>
                                
                                <form action="{{ route('yandex-save-checks') }}" method="post">
                                    @csrf
                                    <button class="btn btn-primary" type="submit">Сохранить изменения</button>
                                    <div class="row">
                                        <div class="col-12 col-xl-5">
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <select class="selectpicker mw-100 @error('first_if') is-invalid @enderror" name="first_if">
                                                        <option value="with" @if($goodsCheck['first_if'] === 'with') selected @endif >Содержит</option>
                                                        <option value="without" @if($goodsCheck['first_if'] === 'without') selected @endif >Не содержит</option>
                                                    </select>
                                                    <input type="text" placeholder="Вхождение" class="form-control mt-2 @error('first_matching') is-invalid @enderror" value="{{ $goodsCheck['first_matching'] }}" name="first_matching">
                                                    @error('first_matching')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <label class="custom-control custom-checkbox mt-3">
                                                        <input type="hidden" value="0" name="first_part">
                                                        <input type="checkbox" class="custom-control-input @error('first_part') is-invalid @enderror" @if($goodsCheck['first_part']) checked @endif value="1" name="first_part"><span class="custom-control-label">В части кода</span>
                                                    </label>
                                                    <div class="input-group"><span class="input-group-prepend"><span class="input-group-text">От</span></span>
                                                        <input type="text" placeholder="" class="form-control @error('first_part_start') is-invalid @enderror" name="first_part_start" value="{{ $goodsCheck['first_part_start'] }}" @if(!$goodsCheck['first_part']) disabled @endif >
                                                        @error('first_part_start')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="input-group mt-3"><span class="input-group-prepend"><span class="input-group-text">До</span></span>
                                                        <input type="text" placeholder="" class="form-control @error('first_part_end') is-invalid @enderror" name="first_part_end" value="{{ $goodsCheck['first_part_end'] }}" @if(!$goodsCheck['first_part']) disabled @endif >
                                                        @error('first_part_end')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-2">
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <label class="custom-control custom-checkbox">
                                                        <input type="hidden" value="0" name="add_status">
                                                        <input type="checkbox" class="custom-control-input @error('add_status') is-invalid @enderror" @if($goodsCheck['add_status']) checked @endif value="1" name="add_status"><span class="custom-control-label">Доп. условие</span>
                                                    </label>
                                                    <select class="selectpicker mw-100 @error('add_cond') is-invalid @enderror" name="add_cond" @if(!$goodsCheck['add_status']) disabled @endif >
                                                        <option value="and" @if($goodsCheck['add_cond'] === 'and') selected @endif >И</option>
                                                        <option value="or" @if($goodsCheck['add_cond'] === 'or') selected @endif >ИЛИ</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-5">
                                            <div class="card mt-3 @if(!$goodsCheck['add_status']) custom-disabled @endif">
                                                <div class="card-body">
                                                    <select class="selectpicker mw-100 @error('second_if') is-invalid @enderror" name="second_if">
                                                        <option value="with" @if($goodsCheck['second_if'] === 'with') selected @endif >Содержит</option>
                                                        <option value="without" @if($goodsCheck['second_if'] === 'without') selected @endif >Не содержит</option>
                                                    </select>
                                                    <input type="text" placeholder="Вхождение" class="form-control mt-2 @error('second_matching') is-invalid @enderror" value="{{ $goodsCheck['second_matching'] }}" name="second_matching">
                                                    @error('second_matching')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <label class="custom-control custom-checkbox mt-3">
                                                        <input type="hidden" value="0" name="second_part">
                                                        <input type="checkbox" class="custom-control-input @error('second_part') is-invalid @enderror" @if($goodsCheck['second_part']) checked @endif value="1" name="second_part"><span class="custom-control-label">В части кода</span>
                                                    </label>
                                                    <div class="input-group"><span class="input-group-prepend"><span class="input-group-text">От</span></span>
                                                        <input type="text" placeholder="" class="form-control @error('second_part_start') is-invalid @enderror" name="second_part_start" value="{{ $goodsCheck['second_part_start'] }}" @if(!$goodsCheck['second_part']) disabled @endif >
                                                        @error('second_part_start')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="input-group mt-3"><span class="input-group-prepend"><span class="input-group-text">До</span></span>
                                                        <input type="text" placeholder="" class="form-control @error('second_part_end') is-invalid @enderror" name="second_part_end" value="{{ $goodsCheck['second_part_end'] }}" @if(!$goodsCheck['second_part']) disabled @endif >
                                                        @error('second_part_end')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h4>Проверить страницу</h4>
                                        <form action="{{ route('yandex-check-url') }}" method="post">
                                            @csrf
                                            <div class="input-group">
                                                <input type="text" placeholder="URL" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-primary">Проверить</button>
                                                </div>
                                                @error('url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="tab-pane fade {{ (session()->has('tab') && session('tab') === 'connection') ? 'active show' : '' }}" id="pills-connection" role="tabpanel" aria-labelledby="pills-connection-tab">
                                <form action="{{ route('yandex-link-account') }}" method="post">
                                    @csrf
                                    <input type="text" placeholder="Логин Яндекс" class="form-control yandex-login-input @error('login') is-invalid @enderror" value="{{ $login }}" name="login">
                                    @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <button type="submit" class="btn btn-primary mt-2">Привязать аккаунт</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    
                <script>
                    /**
                     * Enable / disable parts
                     */
                    $('input[name="first_part"]').on('click', function () {
                        if($(this).is(':checked')){
                            $('input[name="first_part_start"]').prop('disabled', false);
                            $('input[name="first_part_end"]').prop('disabled', false);
                        }else{
                            $('input[name="first_part_start"]').prop('disabled', true).val('');
                            $('input[name="first_part_end"]').prop('disabled', true).val('');
                        }
                    });
                    $('input[name="second_part"]').on('click', function () {
                        if($(this).is(':checked')){
                            $('input[name="second_part_start"]').prop('disabled', false);
                            $('input[name="second_part_end"]').prop('disabled', false);
                        }else{
                            $('input[name="second_part_start"]').prop('disabled', true).val('');
                            $('input[name="second_part_end"]').prop('disabled', true).val('');
                        }
                    });
                    
                    /**
                     * Enable / disable second condition
                     */
                    $('input[name="add_status"]').on('click', function () {
                        if($(this).is(':checked')){
                            $('select[name="add_cond"]').prop('disabled', false).selectpicker('refresh');
                            $('select[name="second_if"]').closest('div.card').removeClass('custom-disabled');
                        }else{
                            $('select[name="add_cond"]').prop('disabled', true).selectpicker('refresh').selectpicker('val', 'and');
                            $('select[name="second_if"]').selectpicker('val', 'with').closest('div.card').addClass('custom-disabled');
                            if($('input[name="second_part"]').is(':checked')){
                                $('input[name="second_part"]').prop('checked', false);
                                $('input[name="second_part_start"]').prop('disabled', true).val('');
                                $('input[name="second_part_end"]').prop('disabled', true).val('');
                            }
                            $('input[name="second_matching"]').val('');
                        }
                    });
                    
                    /**
                     * Hide top messages in 2 seconds
                     */
                    $(document).ready(function(){
                        setTimeout(function(){
                            $('.top-success-msg').slideUp(400);
                            $('.top-danger-msg').slideUp(400);
                        }, 2000);
                    });
                    
                    /**
                     * Pull all the campaigns
                     */
                    function pullCamps() {
                        $("#yandex-campaign-load-modal").modal({
                            keyboard: false,
                            backdrop: 'static',
                        });
                        $.ajax({
                            type: 'post',
                            url: '{{ route('yandex-pull-campaigns') }}',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {},
                            success: function(data){
                                location.reload();
                            },
                            error: function() {
                                location.reload();
                            }
                        });
                    }
                    
                    /**
                     * Activate save button
                     */
                    function campChange() {
                        $('.yandex-campaign-buttons__item.camp_saving').addClass('btn-primary').removeClass('btn-outline-primary');
                    }

                    /**
                     * Activate all the campaigns
                     */
                    function activateCamps() {
                        $('td div.switch-button input[type="checkbox"]').prop("checked", true);
                    }

                    /**
                     * Deactivate all the campaigns
                     */
                    function deactivateCamps() {
                        $('td div.switch-button input[type="checkbox"]').prop("checked", false);
                    }
                </script>
            @endif
            
        </div>
    </div>
@endsection
