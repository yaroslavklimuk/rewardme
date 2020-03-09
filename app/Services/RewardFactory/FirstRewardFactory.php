<?php

namespace App\Services\RewardFactory;

use App\User;
use App\Models\{PendingReward, BonusReward, CashReward, UserPresent, RewardSetting};
use App\Constants\RewardConstants as Const;

class FirstRewardFactory
{
    const REWARDS_TYPES = [Const::CASH_REWARD, Const::BONUS_REWARD, Const::PRESENT_REWARD];

    public function createReward(int $userId)
    {
        $rewardsJar = [Const::BONUS_REWARD];
        if($this->canAddPresentReward($userId)){
            $rewardsJar[] = Const::PRESENT_REWARD;
        }
        if($this->canAddCashReward($userId)){
            $rewardsJar[] = Const::CASH_REWARD;
        }

        // проверить, что не достигнут лимит выплат наличных 

        // много-много логики
    }

    private function canAddPresentReward(int $userId)
    {
        $presentsLimitObj = RewardSetting::where('key', Const::PRESENTS_LIMIT)->first();
        $presentsLimit = isset($presentsLimitObj) ? $presentsLimitObj->val : 3;
        $presentsCount = UserPresent::where('user_id', $userId)->count();
        return $presentsCount < $presentsLimit;
    }

    private function canAddCashReward(int $userId)
    {
        
    }
}
