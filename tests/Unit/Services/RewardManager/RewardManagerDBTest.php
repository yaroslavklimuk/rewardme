<?php
/**
 * Created by PhpStorm.
 * User: yakl
 * Date: 3/11/20
 * Time: 9:44 AM
 */

namespace Tests\Unit\Services\RewardManager;

use App\Services\RewardManager\RewardManagerDB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class RewardManagerDBTest extends TestCase
{
    use RefreshDatabase;

    public function testIfConvertCashIntoBonusWorks()
    {

        $user = factory(\App\User::class)->create([
            'id' => 2,
            'bonuses' => 35
        ]);
        $cash1 = factory(\App\Models\CashReward::class)->create([
            'user_id' => $user->id,
            'amount' => 10,
            'payed' => false
        ]);
        $cash2 = factory(\App\Models\CashReward::class)->create([
            'user_id' => $user->id,
            'amount' => 15,
            'payed' => false
        ]);

        $manager = new RewardManagerDB();
        $manager->convertCashIntoBonus($cash1->id);
        $manager->convertCashIntoBonus($cash2->id, 1.5);

        $this->assertDatabaseHas('bonus_rewards', ['user_id' => 2, 'amount' => 12]);
        $this->assertDatabaseHas('bonus_rewards', ['user_id' => 2, 'amount' => 22]);

        $this->assertDatabaseMissing('cash_rewards', ['id' => $cash1->id]);
        $this->assertDatabaseMissing('cash_rewards', ['id' => $cash2->id]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'bonuses' => 70]);
    }

}