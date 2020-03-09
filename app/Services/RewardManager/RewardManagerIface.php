<?php

namespace App\Services\RewardManager;

interface RewardManagerIface
{
    public function acceptReward(int $rewardId, int $userId);
    public function rejectReward(int $rewardId, int $userId);
}
