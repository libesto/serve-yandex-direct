<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->prefix('panel')->namespace('Panel')->group(function () {
    Route::get('/', 'PanelController@index')->name('panel');
    
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::post('/profile-update-data', 'ProfileController@updateData')->name('profile-update-data');
    Route::post('/profile-update-password', 'ProfileController@updatePassword')->name('profile-update-password');
    
    Route::get('/yandex-settings', 'YandexSettingsController@index')->name('yandex-settings');
    Route::post('/yandex-settings/link-account', 'YandexSettingsController@linkAccount')->name('yandex-link-account');
    Route::get('/yandex-auth', 'YandexAuthController@index')->name('yandex-auth');
    Route::post('/yandex-settings/save-campaigns', 'YandexSettingsController@saveCampaigns')->name('yandex-save-campaigns');
    Route::post('/yandex-settings/pull-campaigns', 'YandexSettingsController@pullCampaigns')->name('yandex-pull-campaigns');
    Route::post('/yandex-settings/save-checks', 'YandexSettingsController@saveChecks')->name('yandex-save-checks');
    Route::post('/yandex-settings/check-url', 'YandexSettingsController@checkUrl')->name('yandex-check-url');
    
    Route::get('/yandex-run', 'YandexRunController@index')->name('yandex-run');
    Route::post('/yandex-run/save', 'YandexRunController@save')->name('yandex-run-save');
    Route::get('/yandex-run/start', 'YandexRunController@start')->name('yandex-run-start');
    Route::post('/yandex-run/status', 'YandexRunController@status')->name('yandex-run-status');
    
    Route::get('/yandex-review', 'YandexReviewController@index')->name('yandex-review');
});

/* Test routes */
//Route::view('/test-panel', 'layouts.panel');
//Route::get('/test', function () {
//    phpinfo();
//});

