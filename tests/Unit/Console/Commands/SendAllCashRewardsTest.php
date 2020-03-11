<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendAllCashRewardsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 
     * @return void
     */
    public function testIfItWorks()
    {
        factory(\App\Models\CashReward::class)->create([
            'user_id' => null,
            'payed' => false
        ]);
        factory(\App\Models\CashReward::class)->create([
            'user_id' => 2,
            'payed' => false
        ]);
        factory(\App\Models\CashReward::class)->create([
            'user_id' => 2,
            'payed' => false
        ]);
        factory(\App\Models\CashReward::class)->create([
            'user_id' => 4,
            'payed' => true
        ]);
        factory(\App\User::class)->create([
            'id' => 2,
        ]);
        
        $this->artisan('rewards:send_cash');
        $this->assertDatabaseHas('cash_rewards', ['user_id' => null, 'payed' => false]);
        $this->assertDatabaseHas('cash_rewards', ['user_id' => 2, 'payed' => true]);
        $this->assertDatabaseHas('cash_rewards', ['user_id' => 2, 'payed' => true]);
        $this->assertDatabaseHas('cash_rewards', ['user_id' => 4, 'payed' => true]);
    }
}
