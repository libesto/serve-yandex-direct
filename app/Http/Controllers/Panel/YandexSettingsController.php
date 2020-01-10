<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\YandexDirectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class YandexSettingsController extends Controller
{
    /**
     * Show the Yandex settings page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        if(!$user->yandexDirectSetting){
            $user->yandexDirectSetting()->create();
            $user->load('yandexDirectSetting');
        }
    
        $campaigns = $user->yandexDirectSetting->campaigns;
        if($campaigns){
            $campaigns = json_decode($campaigns, true);
            if(count($campaigns) < 1){
                $campaigns = false;
            }
        }else{
            $campaigns = false;
        }
        
        $goodsCheck = $user->yandexDirectSetting->goods_check;
        if($goodsCheck){
            $goodsCheck = json_decode($goodsCheck, true);
        }else{
            $goodsCheck = [
                'first_if' => 'with',
                'first_matching' => '',
                'first_part' => false,
                'first_part_start' => '',
                'first_part_end' => '',
                
                'add_status' => false,
                'add_cond' => 'and',
                
                'second_if' => 'with',
                'second_matching' => '',
                'second_part' => false,
                'second_part_start' => '',
                'second_part_end' => '',
            ];
    
            $user->yandexDirectSetting->goods_check = json_encode($goodsCheck, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $user->yandexDirectSetting->save();
        }
        foreach($goodsCheck as $k => $v){
            $goodsCheck[$k] = old($k) ?? $v;
        }
    
        $login = old('login') ?? $user->yandexDirectSetting->login ?? '';
    
        $data = [
            'title' => 'Настройки Яндекс Директ',
            'user' => $user,
            'connected' => YandexDirectService::isConnected(),
            'campaigns' => $campaigns,
            'goodsCheck' => $goodsCheck,
            'login' => $login,
        ];
        
        return view('panel.yandex-settings', $data);
    }
    
    /**
     * Save campaigns in DB
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveCampaigns(Request $request)
    {
        $campaignsStatus = $request->except('_token');
        $user = Auth::user();
        
        if(!$user->yandexDirectSetting || !$user->yandexDirectSetting->campaigns){
            return redirect()->route('panel');
        }
        
        $campaigns = json_decode($user->yandexDirectSetting->campaigns, true);
        if(count($campaigns) < 1){
            return redirect()->route('panel');
        }
        
        foreach($campaigns as $key => $campaign){
            if(isset($campaignsStatus[$key]) && $campaignsStatus[$key]){
                $campaigns[$key]['Status'] = true;
            }else{
                $campaigns[$key]['Status'] = false;
            }
        }
    
        $user->yandexDirectSetting->campaigns = json_encode($campaigns, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $user->yandexDirectSetting->save();
        
        return redirect()->route('yandex-settings')->with('success', 'Изменения сохранены!');
    }
    
    /**
     * Pull campaigns
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pullCampaigns()
    {
        if(!YandexDirectService::isConnected()){
            session()->flash('danger', 'Нету связи с аккаунтом');
            return response()->json();
        }
        
        if(!YandexDirectService::refreshToken()){
            session()->flash('danger', 'Ошибка при обновлении токена');
            return response()->json();
        }
        
        $res = YandexDirectService::getCampaigns();
        if(!$res){
            session()->flash('danger', 'Ошибка при загрузке кампаний');
            return response()->json();
        }
        
        if(isset($res->error)){
            // logging
            $user = Auth::user();
            $message = 'Yandex API error | Method: ' . __METHOD__ . ' | user_id: ' . $user->id . ' | user_email: ' . $user->email;
            Log::error($message, ['context' => json_encode($res->error, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
    
            session()->flash('danger', 'Ошибка API при загрузке кампаний');
            return response()->json();
        }
        
        if(!isset($res->Campaigns)){
            $res->Campaigns = [];
        }
        
        if(YandexDirectService::saveCampaigns($res->Campaigns)){
            session()->flash('success', 'Загружено!');
            return response()->json();
        }
        
        session()->flash('danger', 'Ошибка при сохранении кампаний');
        return response()->json();
    }
    
    /**
     * Save checks
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveChecks(Request $request)
    {
        $request->session()->flash('tab', 'scan');
        
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'first_if' => ['required', 'regex:/^(with|without)$/'],
            'first_matching' => 'required|string|max:255',
            'first_part' => 'required|boolean',
            'add_status' => 'required|boolean',
        ]);
        
        if(isset($input['first_part']) && $input['first_part']){
            $validator->addRules([
                'first_part_start' => 'required|string|max:255',
                'first_part_end' => 'required|string|max:255',
            ]);
        }
        
        if(isset($input['add_status']) && $input['add_status']){
            $validator->addRules([
                'add_cond' => ['required', 'regex:/^(and|or)$/'],
                'second_if' => ['required', 'regex:/^(with|without)$/'],
                'second_matching' => 'required|string|max:255',
                'second_part' => 'required|boolean',
            ]);
    
            if(isset($input['second_part']) && $input['second_part']){
                $validator->addRules([
                    'second_part_start' => 'required|string|max:255',
                    'second_part_end' => 'required|string|max:255',
                ]);
            }
        }
        
        $validator->validate();
    
        $user = Auth::user();
        $goodsCheck = $user->yandexDirectSetting->goods_check;
        
        if(!$goodsCheck){
            return redirect()->route('yandex-settings')->withInput()->with('danger', 'Ошибка при сохранении');
        }
    
        $goodsCheck = json_decode($goodsCheck,true);
        
        $goodsCheck['first_if'] = $input['first_if'];
        $goodsCheck['first_matching'] = $input['first_matching'];
        $goodsCheck['first_part'] = (bool)$input['first_part'];
        $goodsCheck['add_status'] = (bool)$input['add_status'];
        
        if($goodsCheck['first_part']){
            $goodsCheck['first_part_start'] = $input['first_part_start'];
            $goodsCheck['first_part_end'] = $input['first_part_end'];
        }else{
            $goodsCheck['first_part_start'] = '';
            $goodsCheck['first_part_end'] = '';
        }
        
        if($goodsCheck['add_status']){
            $goodsCheck['add_cond'] = $input['add_cond'];
            $goodsCheck['second_if'] = $input['second_if'];
            $goodsCheck['second_matching'] = $input['second_matching'];
            $goodsCheck['second_part'] = (bool)$input['second_part'];
            
            if($goodsCheck['second_part']){
                $goodsCheck['second_part_start'] = $input['second_part_start'];
                $goodsCheck['second_part_end'] = $input['second_part_end'];
            }else{
                $goodsCheck['second_part_start'] = '';
                $goodsCheck['second_part_end'] = '';
            }
        }else{
            $goodsCheck['add_cond'] = 'and';
            $goodsCheck['second_if'] = 'with';
            $goodsCheck['second_matching'] = '';
            $goodsCheck['second_part'] = false;
            $goodsCheck['second_part_start'] = '';
            $goodsCheck['second_part_end'] = '';
        }
    
        $user->yandexDirectSetting->goods_check = json_encode($goodsCheck, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $user->yandexDirectSetting->save();
        
        return back()->with('success', 'Сохранено');
    }
    
    /**
     * Check URL
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkUrl(Request $request)
    {
        $request->session()->flash('tab', 'scan');
    
        $request->validate([
            'url' => 'required|url|max:255',
        ]);
    
        $user = Auth::user();
        $goodsCheck = $user->yandexDirectSetting->goods_check;
    
        if(!$goodsCheck){
            return redirect()->route('yandex-settings')->withInput()->with('danger', 'Ошибка при сохранении');
        }
    
        $goodsCheck = json_decode($goodsCheck,true);
    
        if(empty($goodsCheck['first_matching'])){
            return redirect()->route('yandex-settings')->withInput()->with('danger', 'Не указаны условия');
        }
    
        $url = $request->input('url');
        
        $result = YandexDirectService::checkUrl($url, $goodsCheck);
        $result = $result ? 'Да' : 'Нет';
        
        $checkUrlStatus = [
            'url' => $url,
            'result' => $result,
        ];
        
        return back()->with('checkUrlStatus', $checkUrlStatus)->withInput();
    }
    
    /**
     * Link Yandex account
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function linkAccount(Request $request){
        $request->session()->flash('tab', 'connection');
        
        $request->validate([
            'login' => 'required|string|max:255',
        ]);
    
        $user = Auth::user();
        $user->yandexDirectSetting->login = $request->input('login');
        $user->yandexDirectSetting->save();
        
        $authLink = 'https://oauth.yandex.ru/authorize?response_type=code&client_id=' . config('yandex_direct.client_id')
            . '&redirect_uri=' . route('yandex-auth')
            . '&state=' . csrf_token()
            . '&login_hint=' . $user->yandexDirectSetting->login;
        
        return redirect($authLink);
    }
}
