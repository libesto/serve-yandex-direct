<?php

namespace Tests\Feature;

use App\Services\YandexDirectService;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class YandexDirectServiceTest extends TestCase
{
    /**
     * Test refreshing token
     *
     * @return void
     */
    public function testRefreshToken()
    {
        $user = User::find(3);
        $result = YandexDirectService::refreshToken($user);
        
        $this->assertTrue($result);
    }
}
