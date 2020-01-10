<?php

namespace App\Services;

use App\Jobs\YandexDirectOperation;
use App\YandexDirectRun;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class YandexDirectService extends PanelService
{
    /**
     * Check the token value
     *
     * Return false if the token value is empty or expires in less than 10 minutes
     *
     * @param mixed $user
     * @return bool
     */
    public static function isConnected($user = false){
        ($user) ?: $user = Auth::user();
    
        $currentTokenLifetime = now()->timestamp - $user->yandexDirectSetting->updated_at->timestamp;
        
        if(empty($user->yandexDirectSetting->token)
            || empty($user->yandexDirectSetting->expires_in)
            || $user->yandexDirectSetting->expires_in < $currentTokenLifetime + 10*60){
            return false;
        }
        
        return true;
    }
    
    /**
     * Refresh the token
     *
     * Refresh the token if it expires in less than 14 days
     *
     * @param mixed $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function refreshToken($user = false){
        ($user) ?: $user = Auth::user();
        
        $currentTokenLifetime = now()->timestamp - $user->yandexDirectSetting->updated_at->timestamp;
        $currentlyExpiresIn = $user->yandexDirectSetting->expires_in - $currentTokenLifetime;
        
        if($currentlyExpiresIn < \Carbon\CarbonInterval::days(14)->totalSeconds){
            
            // post request to refresh token
            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', 'https://oauth.yandex.ru/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $user->yandexDirectSetting->refresh_token,
                    'client_id' => config('yandex_direct.client_id'),
                    'client_secret' => config('yandex_direct.client_secret'),
                ]
            ]);
            $resBody = json_decode($res->getBody());
            if(!$resBody || isset($resBody->error)){
                return false;
            }
    
            $user->yandexDirectSetting->token = $resBody->access_token;
            $user->yandexDirectSetting->refresh_token = $resBody->refresh_token;
            $user->yandexDirectSetting->expires_in = $resBody->expires_in;
            $user->yandexDirectSetting->save();
        }
        
        return true;
    }
    
    /**
     * Get campaigns
     *
     * @param mixed $user
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getCampaigns($user = false){
        ($user) ?: $user = Auth::user();
    
        // Адрес сервиса Campaigns для отправки JSON-запросов (регистрозависимый)
        $url = 'https://' . config('yandex_direct.api_host') . '/json/v5/campaigns';
    
        // Установка HTTP-заголовков запроса
        $headers = [
            'Authorization' => 'Bearer ' . $user->yandexDirectSetting->token,   // OAuth-токен. Использование слова Bearer обязательно
            'Client-Login' => $user->yandexDirectSetting->login,                // Логин (клиента рекламного агентства)
            'Accept-Language' => 'ru',                                          // Язык ответных сообщений
            'Content-Type' => 'application/json; charset=utf-8',                // Тип данных и кодировка запроса
        ];
        
        // Параметры запроса к серверу API Директа
        $params = [
            'method' => 'get',                      // Используемый метод сервиса Campaigns
            'params' => [
                'SelectionCriteria' => [
                    'States' => config('yandex_direct.campaigns_states'), // Критерий отбора кампаний. Для получения всех кампаний должен быть пустым
                ],
                'FieldNames' => ['Id', 'Name'],     // Названия параметров, которые требуется получить
            ],
        ];
        // Преобразование входных параметров запроса в формат JSON
        $body = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', $url, [
            'headers' => $headers,
            'body' => $body,
        ]);
    
        // Обработка результата
        if($res === false){
            return false;
        }else{
            // Преобразование ответа из формата JSON
            $resBody = json_decode($res->getBody());
    
            if(isset($resBody->error)){
                return $resBody;
            }else{
                // Извлечение HTTP-заголовков ответа: RequestId (Id запроса) и Units (информация о баллах)
                if($res->hasHeader('Units')){
                    $resBody->result->Units = $res->getHeader('Units')[0];
                }
                if($res->hasHeader('RequestId')){
                    $resBody->result->RequestId = $res->getHeader('RequestId')[0];
                }
                
                return $resBody->result;
            }
        }
    }
    
    /**
     * Save campaigns in DB
     *
     * @param array $campaigns
     * @param mixed $user
     * @return bool
     */
    public static function saveCampaigns($campaigns, $user = false){
        ($user) ?: $user = Auth::user();
        
        if(count($campaigns) < 1){
            $user->yandexDirectSetting->campaigns = null;
            $user->yandexDirectSetting->save();
            return true;
        }
    
        $campaignsOrig = $user->yandexDirectSetting->campaigns;
        $campaignsOrig = ($campaignsOrig == null) ? null : json_decode($user->yandexDirectSetting->campaigns, true);
        
        foreach($campaigns as $campaign){
            $campaignsNew['campaign'.$campaign->Id] = $campaign;
            
            if($campaignsOrig && isset($campaignsOrig['campaign'.$campaign->Id])){
                $campaignsNew['campaign'.$campaign->Id]->Status = $campaignsOrig['campaign'.$campaign->Id]['Status'];
            }else{
                $campaignsNew['campaign'.$campaign->Id]->Status = false;
            }
        }
    
        $user->yandexDirectSetting->campaigns = json_encode($campaignsNew, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $user->yandexDirectSetting->save();
        
        return true;
    }
    
    /**
     * Get active campaigns ids
     *
     * @param mixed $user
     * @return array|bool
     */
    public static function getActiveCampaignsIds($user = false){
        ($user) ?: $user = Auth::user();
        
        $campaigns = $user->yandexDirectSetting->campaigns;
        $activeCampaignsIds = false;
        
        if($campaigns){
            $campaigns = json_decode($campaigns, true);
            if(count($campaigns) < 1){
                $campaigns = false;
            }else{
                $activeCampaignsIds = [];
            
                foreach($campaigns as $campaign){
                    if($campaign['Status']){
                        $activeCampaignsIds[] = $campaign['Id'];
                    }
                }
            
                if(count($activeCampaignsIds) < 1){
                    $activeCampaignsIds = false;
                }
            }
        }else{
            $campaigns = false;
        }
        
        if(!$campaigns || !$activeCampaignsIds){
            return false;
        }
        
        return $activeCampaignsIds;
    }
    
    /**
     * Get check settings
     *
     * @param mixed $user
     * @return bool|mixed
     */
    public static function getCheckSettings($user = false){
        ($user) ?: $user = Auth::user();
        
        $goodsCheck = $user->yandexDirectSetting->goods_check;
        if(!$goodsCheck){
            return false;
        }
        $goodsCheck = json_decode($goodsCheck,true);
        if(empty($goodsCheck['first_matching'])){
            return false;
        }
        
        return $goodsCheck;
    }
    
    /**
     * Get ads
     *
     * @param mixed $user
     * @param mixed $campaigns
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getAds($user = false, $campaigns = false){
        ($user) ?: $user = Auth::user();
        ($campaigns) ?: $campaigns = self::getActiveCampaignsIds($user);
        
        if(!$campaigns){
            return false;
        }
    
        $campaigns = array_chunk($campaigns, 10);
        $ads = false;
        
        foreach($campaigns as $campaigns_set){
            $offset = 0;
            do{
                // URL
                $url = 'https://' . config('yandex_direct.api_host') . '/json/v5/ads';
                
                // установка HTTP-заголовков запроса
                $headers = [
                    'Authorization' => 'Bearer ' . $user->yandexDirectSetting->token,   // OAuth-токен. Использование слова Bearer обязательно
                    'Client-Login' => $user->yandexDirectSetting->login,                // Логин (клиента рекламного агентства)
                    'Accept-Language' => 'ru',                                          // Язык ответных сообщений
                    'Content-Type' => 'application/json; charset=utf-8',                // Тип данных и кодировка запроса
                ];
                
                // параметры запроса к серверу API Директа
                $params = [
                    'method' => 'get',
                    'params' => [
                        'SelectionCriteria' => [
                            'States' => config('yandex_direct.ads_states'), // отбор с нужным состоянием
                            'CampaignIds' => $campaigns_set // не более 10 элементов в массиве
                        ],
                        'FieldNames' => ['Id', 'Status', 'State'],
                        'TextAdFieldNames' => ['Href', 'Title', 'Title2'],
                        'Page' => [
                            'Limit' => 1000,
                            'Offset' => $offset
                        ]
                    ]
                ];
                
                // преобразование входных параметров запроса в формат JSON
                $body = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                
                $client = new \GuzzleHttp\Client();
                $res = $client->request('POST', $url, [
                    'headers' => $headers,
                    'body' => $body,
                ]);
                
                // Обработка результата
                if($res === false){
                    return false;
                }else{
                    // Преобразование ответа из формата JSON
                    $resBody = json_decode($res->getBody());
                
                    if(isset($resBody->error)){
                        // logging
                        $message = 'Yandex API error | Method: ' . __METHOD__ . ' | user_id: ' . $user->id . ' | user_email: ' . $user->email;
                        Log::error($message, ['context' => json_encode($resBody->error, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
                        
                        return false;
                    }else{
                        // Извлечение HTTP-заголовков ответа: RequestId (Id запроса) и Units (информация о баллах)
                        if($res->hasHeader('Units')){
                            $resBody->result->Units = $res->getHeader('Units')[0];
                        }
                        if($res->hasHeader('RequestId')){
                            $resBody->result->RequestId = $res->getHeader('RequestId')[0];
                        }
                        
                        //Log::debug('resBody:' . json_encode($resBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                        
                        if(isset($resBody->result->Ads)){
                            foreach($resBody->result->Ads as $ad){
                                $ads[] = $ad;
                            }
                        }
        
                        $offset += 1000;
                    }
                }
            }while(isset($resBody->result->LimitedBy) && $resBody->result->LimitedBy);
        }
        
        //Log::debug('ads:' . json_encode($ads, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        
        // Cut UTM
        if($ads){
            foreach($ads as $key => $ad){
                if(isset($ads[$key]->TextAd->Href)){
                    $ads[$key]->TextAd->Href = YandexDirectService::cutUtm($ad->TextAd->Href);
                }else{
                    // exclude dynamic ads
                    unset($ads[$key]);
                }
            }
        }
        
        return $ads;
    }
    
    /**
     * Get ad status changes
     *
     * @param $ads
     * @param $links
     * @return array
     */
    public static function getAdStatusChanges($ads, $links){
        $resume = [];
        $suspend = [];
        foreach($ads as $ad){
            if($links[$ad->TextAd->Href]){
                // on
                if(substr_count($ad->State, 'SUSPENDED') > 0){
                    $resume['ads_ids'][] = $ad->Id;
                    $resume['links'][$ad->TextAd->Href] = $links[$ad->TextAd->Href];
                }
            }else{
                // off
                if(substr_count($ad->State, 'SUSPENDED') < 1){
                    $suspend['ads_ids'][] = $ad->Id;
                    $suspend['links'][$ad->TextAd->Href] = $links[$ad->TextAd->Href];
                }
            }
        }
        $changes = [
            'resume' => $resume,
            'suspend' => $suspend
        ];
        return $changes;
    }
    
    /**
     * Push ads states
     *
     * @param $adsIds
     * @param $user
     * @param $state
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function pushAdsStates($adsIds, $user, $state){
        // URL
        $url = 'https://' . config('yandex_direct.api_host') . '/json/v5/ads';
    
        // установка HTTP-заголовков запроса
        $headers = [
            'Authorization' => 'Bearer ' . $user->yandexDirectSetting->token,   // OAuth-токен. Использование слова Bearer обязательно
            'Client-Login' => $user->yandexDirectSetting->login,                // Логин (клиента рекламного агентства)
            'Accept-Language' => 'ru',                                          // Язык ответных сообщений
            'Content-Type' => 'application/json; charset=utf-8',                // Тип данных и кодировка запроса
        ];
    
        // параметры запроса к серверу API Директа
        $params = [
            'method' => $state,
            'params' => [
                'SelectionCriteria' => [
                    'Ids' => $adsIds
                ]
            ]
        ];
    
        // преобразование входных параметров запроса в формат JSON
        $body = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', $url, [
            'headers' => $headers,
            'body' => $body,
        ]);
    
        // Обработка результата
        if($res === false){
            return false;
        }
        
        // Преобразование ответа из формата JSON
        $resBody = json_decode($res->getBody());
    
        if(isset($resBody->error)){
            // logging
            $message = 'Yandex API error | Method: ' . __METHOD__ . ' | user_id: ' . $user->id . ' | user_email: ' . $user->email;
            Log::error($message, ['context' => json_encode($resBody->error, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
        
            return false;
        }
    
        if(isset($resBody->result->Errors) && is_array($resBody->result->Errors) && count($resBody->result->Errors) > 0){
            return false;
        }
        
        return true;
    }
    
    /**
     * Apply ad changes
     *
     * @param $changes
     * @param $user
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function applyAdChanges($changes, $user){
        ($user) ?: $user = Auth::user();
        
        if(isset($changes['resume']['ads_ids']) && is_array($changes['resume']['ads_ids']) && count($changes['resume']['ads_ids']) > 0){
            $resumeAds = array_chunk($changes['resume']['ads_ids'], 10000);
            foreach($resumeAds as $resumeAdsPart){
                if(!self::pushAdsStates($resumeAdsPart, $user, 'resume')){
                    return false;
                }
            }
        }
    
        if(isset($changes['suspend']['ads_ids']) && is_array($changes['suspend']['ads_ids']) && count($changes['suspend']['ads_ids']) > 0){
            $suspendAds = array_chunk($changes['suspend']['ads_ids'], 10000);
            foreach($suspendAds as $suspendAdsPart){
                if(!self::pushAdsStates($suspendAdsPart, $user, 'suspend')){
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Add jobs
     */
    public static function addJobs(){
        $runs = YandexDirectRun::where('daily_run', true)->where('next_run', '<=', now())->where('running', false)->get();
        
        foreach($runs as $run){
            $run->running = true;
            
            $time = $run->next_run->format('H:i');
            $run->next_run = now()->next($time);
            
            $run->save();
    
            YandexDirectOperation::dispatch($run->user);
        }
    }
}
