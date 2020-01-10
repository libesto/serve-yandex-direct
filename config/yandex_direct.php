<?php

return [

    //'api_host' => 'api-sandbox.direct.yandex.com', // for test
    'api_host' => 'api.direct.yandex.com',
    'sandbox_api_host' => 'api-sandbox.direct.yandex.com',

    'client_id' => 'test',
    'client_secret' => 'test',

    /**
     * Состояния с которыми загружаются кампании
     * Возможные состояния здесь: https://yandex.ru/dev/direct/doc/dg/objects/campaign-docpage/#status
     */
    //'campaigns_states' => [], // for test
    'campaigns_states' => ['ON', 'SUSPENDED', 'OFF'],

    /**
     * Состояния с которыми загружаются объявления при обработке
     * Возможные состояния здесь: https://yandex.ru/dev/direct/doc/dg/objects/ad-docpage/#status
     */
    //'ads_states' => [], // for test
    'ads_states' => ['ON', 'SUSPENDED', 'OFF_BY_MONITORING', 'OFF'],

];
