<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\YandexDirectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YandexReviewController extends Controller
{
    /**
     * Show yandex review page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        if(!YandexDirectService::isConnected()){
            return redirect()->route('yandex-settings');
        }
        
        $results = $user->yandexDirectResults()->orderBy('start_time', 'desc')->paginate(10);
        foreach($results as $k => $v){
            $results[$k]->run_result = json_decode($results[$k]->run_result, true);
        }
        
        $data = [
            'title' => 'Отчет по запускам',
            'results' => $results,
            'running' => $user->yandexDirectRun->running,
        ];
        
        return view('panel.yandex-review', $data);
    }
}
