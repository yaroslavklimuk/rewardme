<?php

namespace App\Services\RewardFactory;

use App\User;
use App\Models\{PendingReward, BonusReward, CashReward, UserPresent, RewardSetting, Present};
use App\Constants\RewardConstants as RConst;

class FirstRewardFactory implements RewardFactoryIface
{
    const REWARDS_TYPES = [RConst::CASH_REWARD, RConst::BONUS_REWARD, RConst::PRESENT_REWARD];

    public function createReward(int $userId)
    {
        $rewardsJar = [RConst::BONUS_REWARD];
        if($this->canAddPresentReward($userId)){
            $rewardsJar[] = RConst::PRESENT_REWARD;
        }
        $cashReward = $this->canAddCashReward($userId);
        if($cashReward !== false){
            $rewardsJar[] = RConst::CASH_REWARD;
        }

        $rewardType = array_rand($rewardsJar);
        switch($rewardsJar[$rewardType]){
            case RConst::PRESENT_REWARD:
                $reward = $this->createPresentReward();
                break;
            case RConst::CASH_REWARD:
                $reward = $this->createCashReward($cashReward);
                break;
            default:
                $reward = $this->createBonusReward();
        }
        $pendingReward = $this->createPendingReward($rewardsJar[$rewardType], $reward['id'], $userId);
        $pendingReward['value'] = $rewardsJar[$rewardType] === RConst::PRESENT_REWARD ? $reward['name'] : $reward['amount'];
        return $pendingReward;
    }


    private function canAddPresentReward(int $userId)
    {
        $presentsCount = Present::count();
        if($presentsCount === 0){
            return false;
        }
        $presentsLimitObj = RewardSetting::find(RConst::PRESENTS_LIMIT);
        $presentsLimit = isset($presentsLimitObj) ? $presentsLimitObj->val : 3;
        $presentsCount = UserPresent::where('user_id', $userId)->count();
        return $presentsCount < $presentsLimit;
    }

    private function canAddCashReward(int $userId)
    {
        $cashLimitObj = RewardSetting::find(RConst::CASH_LIMIT);
        $cashLimit = isset($cashLimitObj) ? $cashLimitObj->val : 1000;
        $userCashRewards = CashReward::where('user_id', $userId)->pluck('amount');
        $userPayedCash = array_sum($userCashRewards->toArray());
        $leftToPay = $cashLimit - $userPayedCash;
        if($leftToPay <= 0){
            return false;
        }
        $minCashPaymentObj = RewardSetting::find(RConst::CASH_LEFT);
        $minCashPayment = isset($minCashPaymentObj) ? $minCashPaymentObj->val : 18;
        if($leftToPay < $minCashPayment){
            return false;
        }
        $maxCashPaymentObj = RewardSetting::find(RConst::CASH_RIGHT);
        $maxCashPayment = isset($maxCashPaymentObj) ? $maxCashPaymentObj->val : 90;
        if($leftToPay < $maxCashPayment){
            $maxCashPayment = $leftToPay;
        }
        $cashPayment = rand($minCashPayment, $maxCashPayment);
        return $cashPayment;
    }


    private function createPendingReward(string $type, int $rewardId, int $userId)
    {
        $pendingReward = new PendingReward();
        $pendingReward->type = $type;
        $pendingReward->reward_id = $rewardId;
        $pendingReward->user_id = $userId;
        $pendingReward->save();
        return $pendingReward->toArray();
    }

    private function createBonusReward()
    {
        $bonusLeftObj = RewardSetting::find(RConst::BONUS_LEFT);
        $bonusLeft = isset($bonusLeftObj) ? (float)$bonusLeftObj->val : 20;
        $bonusRightObj = RewardSetting::find(RConst::BONUS_RIGHT);
        $bonusRight = isset($bonusRightObj) ? (float)$bonusRightObj->val : 130;

        $reward = new BonusReward();
        $reward->amount = rand($bonusLeft, $bonusRight);
        $reward->save();
        return $reward->toArray();
    }

    private function createCashReward(float $amount)
    {
        $reward = new CashReward();
        $reward->amount = $amount;
        $reward->save();
        return $reward->toArray();
    }

    private function createPresentReward()
    {
        $presentsObj = Present::pluck('name');
        $presents = $presentsObj->toArray();
        $presentName = $presents[array_rand($presents)];

        $reward = new UserPresent();
        $reward->name = $presentName;
        $reward->save();
        return $reward->toArray();
    }
}
