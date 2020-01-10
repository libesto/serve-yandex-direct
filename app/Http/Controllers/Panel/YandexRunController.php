<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Jobs\YandexDirectOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\YandexDirectService;

class YandexRunController extends Controller
{
    /**
     * Show the Yandex run page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        if(!$user->yandexDirectRun){
            $user->yandexDirectRun()->create();
            $user->load('yandexDirectRun');
        }
        
        if(!YandexDirectService::isConnected()){
            return redirect()->route('yandex-settings');
        }
        
        $time = $user->yandexDirectRun->next_run;
        if($time){
            $time = $time->format('H:i');
        }
        
        $data = [
            'title' => 'Расписание и запуск Яндекс Директ',
            'user' => $user,
            'running' => $user->yandexDirectRun->running,
            'daily_run' => $user->yandexDirectRun->daily_run,
            'time' => $time,
        ];
        
        return view('panel.yandex-run', $data);
    }
    
    /**
     * Save run settings
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $request->validate([
            'daily_run' => 'required|boolean',
            'time' => ['required', 'regex:/^(0|1|2)\d{1}:[0-5]{1}0$/'],
        ]);
        
        $user = Auth::user();
        
        $user->yandexDirectRun->daily_run = $request->input('daily_run');
        
        $time = $request->input('time');
        $time = now()->next($time);
        $user->yandexDirectRun->next_run = $time;
        
        $user->yandexDirectRun->save();
    
        return back()->with('success', 'Сохранено');
    }
    
    /**
     * Start running
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start()
    {
        $user = Auth::user();
    
        if($user->yandexDirectRun->running){
            return redirect()->route('yandex-run')->with('danger', 'Уже запущено');
        }
    
        $user->yandexDirectRun->running = true;
        $user->yandexDirectRun->save();
        YandexDirectOperation::dispatch($user);
    
        return redirect()->route('yandex-run');
    }
    
    /**
     * Get running status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        $user = Auth::user();
        
        return response()->json($user->yandexDirectRun->running);
    }
}
