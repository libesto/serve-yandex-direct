<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YandexAuthController extends Controller
{
    /**
     * Yandex Direct Auth
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(Request $request){
        
        if(empty($request->code) || empty($request->state)){
            return redirect()->route('panel');
        }
        
        if($request->state == csrf_token()){
            
            // post request for getting token
            $client = new \GuzzleHttp\Client();
            
            $res = $client->request('POST', 'https://oauth.yandex.ru/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $request->code,
                    'client_id' => config('yandex_direct.client_id'),
                    'client_secret' => config('yandex_direct.client_secret'),
                ]
            ]);
            
            $resBody = json_decode($res->getBody());
            if(!$resBody || isset($resBody->error)){
                return redirect()->route('yandex-settings')->with('danger', 'Ошибка, связь не установлена!');
            }
            
            $user = Auth::user();
            $user->yandexDirectSetting->token = $resBody->access_token;
            $user->yandexDirectSetting->refresh_token = $resBody->refresh_token;
            $user->yandexDirectSetting->expires_in = $resBody->expires_in;
            $user->yandexDirectSetting->save();
            
            return redirect()->route('yandex-settings')->with('success', 'Связь установлена!');
        }
        return redirect()->route('panel');
    }
}
