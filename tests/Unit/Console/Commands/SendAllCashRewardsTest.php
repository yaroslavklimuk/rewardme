<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendAllCashRewardsTest extends TestCase
{
    /**
     * 
     * @return void
     */
    public function testIfItWorks()
    {
        factory(\App\Model\CashReward)->create([
            'user_id' => null,
            'payed' => false
        ]);
        factory(\App\Model\CashReward)->create([
            'user_id' => 2,
            'payed' => false
        ]);
        factory(\App\Model\CashReward)->create([
            'user_id' => 3,
            'payed' => false
        ]);
        factory(\App\Model\CashReward)->create([
            'user_id' => 4,
            'payed' => true
        ]);
        
        $this->artisan('rewards:send_cash');
        $this->assertDatabaseHas('cash_rewards', ['user_id' => null, 'payed' => false]);
        $this->assertDatabaseHas('cash_rewards', ['user_id' => 2, 'payed' => true]);
        $this->assertDatabaseHas('cash_rewards', ['user_id' => 3, 'payed' => true]);
        $this->assertDatabaseHas('cash_rewards', ['user_id' => 4, 'payed' => true]);
    }
}
