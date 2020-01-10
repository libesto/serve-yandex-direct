<?php

namespace App\Jobs;

use App\Services\YandexDirectService;
use App\User;
use App\YandexDirectResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class YandexDirectOperation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout;
    
    /**
     * User model
     *
     * @var User
     */
    public $user;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        // Set 1 hour timeout
        $this->timeout = \Carbon\CarbonInterval::hour()->totalSeconds;
        
        // Set user
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        $start_time = now();
        $stopDailyRunning = false;
        
        if(!YandexDirectService::isConnected($this->user) || !YandexDirectService::refreshToken($this->user)){
            $changes = [
                'warning' => 'Установите соединение с аккаунтом Яндекс',
            ];
            $stopDailyRunning = true;
            goto endWithChanges;
        }
    
        $campaigns = YandexDirectService::getActiveCampaignsIds($this->user);
        if(!$campaigns){
            $changes = [
                'warning' => 'Нет активных кампаний',
            ];
            $stopDailyRunning = true;
            goto endWithChanges;
        }
    
        $goodsCheck = YandexDirectService::getCheckSettings($this->user);
        if(!$goodsCheck){
            $changes = [
                'warning' => 'Установите условия сканирования',
            ];
            $stopDailyRunning = true;
            goto endWithChanges;
        }
    
        $ads = YandexDirectService::getAds($this->user);
        if(!$ads){
            $changes = [
                'warning' => 'Ошибка при получении объявлений',
            ];
            goto endWithChanges;
        }
        
        $adsLinks = YandexDirectService::getAdsLinks($ads);
        
        $adsLinksWithStatuses = YandexDirectService::checkUrls($adsLinks, $goodsCheck);
        if(!$adsLinksWithStatuses){
            $changes = [
                'warning' => 'Ошибка в ссылках объявлений',
            ];
            goto endWithChanges;
        }else{
            $changes = YandexDirectService::getAdStatusChanges($ads, $adsLinksWithStatuses);
        }
        
        if(!YandexDirectService::applyAdChanges($changes, $this->user)){
            $changes = [
                'warning' => 'Ошибка при отправке изменений в Яндекс Директ',
            ];
            goto endWithChanges;
        }
    
        endWithChanges:
        if(isset($changes['warning']) && $this->user->yandexDirectRun->daily_run && $stopDailyRunning){
            $this->user->yandexDirectRun->daily_run = false;
            $changes['warning'] .= ' (запуск по расписанию был отключен)';
        }
        
        $result = new YandexDirectResult([
            'run_result' => json_encode($changes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'start_time' => $start_time,
        ]);
        
        $this->user->yandexDirectResults()->save($result);
        
        $this->user->yandexDirectRun->running = false;
        $this->user->yandexDirectRun->save();
    }
    
    /**
     * The job failed to process.
     *
     * @return void
     */
    public function failed()
    {
        $this->user->yandexDirectRun->running = false;
        $this->user->yandexDirectRun->save();
    }
}
