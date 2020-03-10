<?php

namespace App\Http\Controllers;

use App\Services\RewardFactory\RewardFactoryIface;
use App\Services\RewardManager\RewardManagerIface;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function renderPage()
    {
        return View::make('welcome');
    }

    public function claimReward(Request $request, RewardFactoryIface $rewardFactory)
    {
        $pendingReward = $rewardFactory->createReward();
        return response()->json($pendingReward);
    }

    public function acceptReward(Request $request, RewardManagerIface $rewardManager)
    {
        $pendRewardId = $request->input('reward_id');
        $rewardManager->acceptReward($pendRewardId, $request->user()->id);
        return response()->json(['success' => true]);
    }

    public function rejectReward(Request $request, RewardManagerIface $rewardManager)
    {
        $pendRewardId = $request->input('reward_id');
        $rewardManager->rejectReward($pendRewardId, $request->user()->id);
        return response()->json(['success' => true]);
    }
}
