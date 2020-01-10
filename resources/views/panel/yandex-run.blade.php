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
    
            @if($running)
                <div class="col-12">
                    <div class="alert alert-primary top-running-msg" role="alert">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Выполняется... Результат появится в разделе <a href="{{ route('yandex-review') }}">Статистика</a>
                    </div>
                    <div class="alert alert-success top-running-succes-msg" role="alert">
                        <button class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        Завершено
                    </div>
                </div>
                <script>
                    /**
                     * Get running condition every 5 seconds
                     */
                    $(document).ready(function(){
                        if($('.top-running-msg').is(':visible')){
                            (function getStatus(){
                                setTimeout(function(){
                                    $.ajax({
                                        type: 'post',
                                        url: '{{ route('yandex-run-status') }}',
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        data: {},
                                        success: function(data){
                                            if(data){
                                                getStatus();
                                            }else{
                                                $('.top-running-msg').slideUp(400, function(){
                                                    $('.top-running-succes-msg').slideDown(400).delay(2000).slideUp(400);
                                                });
                                            }
                                        },
                                        error: function(){}
                                    });
                                }, 5000);
                            })();
                        }
                    });
                </script>
            @endif
            
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('yandex-run-save') }}" method="post">
                            @csrf
                            <button class="btn btn-primary m-1" type="submit">Сохранить</button>
                            <a class="btn btn-success m-1" href="{{ route('yandex-run-start') }}">Запустить сейчас</a>
        
                            <div class="form-group row">
                                <div class="col-12">
                                    <label class="m-1">Включить планировщик:</label>
                                    <div class="switch-button switch-button-success m-1">
                                        <input type="hidden" value="0" name="daily_run">
                                        <input type="checkbox" value="1" @if($daily_run) checked @endif name="daily_run" id="daily_run">
                                        <span><label for="daily_run"></label></span>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <label class="m-1">Ежедневное время запуска (по Москве):</label>
                                    <select class="selectpicker yandex-run-time" name="time">
                                        @for( $i = 0; $i <= 23; $i++)
                                            @for( $j = 0; $j <= 5; $j++)
                                                @php($option = str_pad($i, 2, '0', STR_PAD_LEFT) . ':' . $j . '0')
                                                <option @if($time === $option) selected @endif>{{ $option }}</option>
                                            @endfor
                                        @endfor
                                    </select>
                                </div>
                            </div>
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
