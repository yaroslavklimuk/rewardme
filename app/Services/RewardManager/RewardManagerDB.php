<?php

namespace App\Services\RewardManager;

use App\User;
use App\Models\{PendingReward, BonusReward, CashReward, UserPresent, RewardSetting};
use App\Constants\RewardConstants as RConst;

class RewardManagerDB
{
    public function acceptReward(int $pendRewardId, int $userId)
    {
        $pendingReward = $this->getPendingReward($pendRewardId, $userId);
        switch($pendingReward->type){
            case RConst::BONUS_REWARD :
                $this->acceptBonusReward($pendingReward->reward_id, $userId);
                break;
            case RConst::CASH_REWARD :
                $this->acceptCashReward($pendingReward->reward_id, $userId);
                break;
            case RConst::PRESENT_REWARD :
                $this->acceptPresentReward($pendingReward->reward_id, $userId);
                break;
            default:
                throw new \Exception('Bad reward type!');
        }
    }

    protected function acceptBonusReward(int $rewardId, int $userId)
    {
        $reward = BonusReward::find($rewardId);
        $reward->user_id = $userId;
        $reward->save();

        $user = User::find($userId);
        $user->bonuses = $user->bonuses + $reward->amount;
        $user->save();
    }

    protected function acceptCashReward(int $rewardId, int $userId)
    {
        $reward = CashReward::find($rewardId);
        $reward->user_id = $userId;
        $reward->save();

        // try to send money via bank's API
    }

    protected function acceptPresentReward(int $rewardId, int $userId)
    {
        $reward = UserPresent::find($rewardId);
        $reward->user_id = $userId;
        $reward->save();

        // notify stuff about new present
    }


    public function rejectReward(int $pendRewardId, int $userId)
    {
        $pendingReward = $this->getPendingReward($pendRewardId, $userId);
        switch($pendingReward->type){
            case RConst::BONUS_REWARD :
                $this->rejectBonusReward($pendingReward->reward_id);
                break;
            case RConst::CASH_REWARD :
                $this->rejectCashReward($pendingReward->reward_id);
                break;
            case RConst::PRESENT_REWARD :
                $this->rejectPresentReward($pendingReward->reward_id);
                break;
            default:
                PendingReward::destroy($pendRewardId);
                throw new \Exception('Bad reward type!');
        }
        PendingReward::destroy($pendRewardId);
    }

    protected function rejectBonusReward(int $rewardId)
    {
        BonusReward::destroy($rewardId);
    }

    protected function rejectCashReward(int $rewardId)
    {
        CashReward::destroy($rewardId);
    }

    protected function rejectPresentReward(int $rewardId)
    {
        UserPresent::destroy($rewardId);
    }


    protected function getPendingReward(int $pendRewardId, int $userId)
    {
        $pendingReward = PendingReward::findOrFail($pendRewardId);
        if($pendingReward->user_id !== $userId){
            throw new \Exception('Wrong reward id!');
        }
        return $pendingReward;
    }
}
