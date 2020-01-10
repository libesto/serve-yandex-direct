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
            @endif
    
            @if($running)
                <div class="col-12">
                    <div class="alert alert-primary top-running-msg" role="alert">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Выполняется...
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
                                                    location.reload();
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
                @if(count($results) === 0)
                    <div class="card">
                        <div class="card-body">
                            Нет завершенных запусков
                        </div>
                    </div>
                @else
                    @foreach($results as $result)
                        <div class="card mb-4">
                            <div class="card-header">
                                Время запуска: <span class="badge badge-primary">{{ $result->start_time->format('d.m.Y в H:i') }}</span>
                            </div>
                            <div class="card-body">
                                @if(isset($result->run_result['warning']))
                                    <div class="alert alert-danger mb-0" role="alert">
                                        {{ $result->run_result['warning'] }}
                                    </div>
                                @else
                                    <div class="card bg-success overflow-hidden mb-3">
                                        @if(isset($result->run_result['resume']['ads_ids']))
                                            <div class="card-header">
                                                Запущенных объявлений: <span class="badge badge-success">{{ count($result->run_result['resume']['ads_ids']) }}</span>
                                                <br>
                                                Уникальных ссылок: <span class="badge badge-success">{{ count($result->run_result['resume']['links']) }}</span>
                                            </div>
                                            <div class="card-body text-white">
                                                Ссылки:
                                                <br>
                                                @foreach($result->run_result['resume']['links'] as $link => $status)
                                                    <a class="d-block text-white" target="_blank" href="{{ $link }}">{{ $link }}</a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="card-header">
                                                Запущенных объявлений: <span class="badge badge-success">0</span>
                                            </div>
                                        @endif
                                    </div>
                        
                                    <div class="card bg-warning overflow-hidden">
                                        @if(isset($result->run_result['suspend']['ads_ids']))
                                            <div class="card-header">
                                                Остановленных объявлений: <span class="badge badge-warning">{{ count($result->run_result['suspend']['ads_ids']) }}</span>
                                                <br>
                                                Уникальных ссылок: <span class="badge badge-warning">{{ count($result->run_result['suspend']['links']) }}</span>
                                            </div>
                                            <div class="card-body text-body">
                                                Ссылки:
                                                <br>
                                                @foreach($result->run_result['suspend']['links'] as $link => $status)
                                                    <a class="d-block text-body" target="_blank" href="{{ $link }}">{{ $link }}</a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="card-header">
                                                Остановленных объявлений: <span class="badge badge-warning">0</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
        
                    {{ $results->links() }}
                @endif
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
