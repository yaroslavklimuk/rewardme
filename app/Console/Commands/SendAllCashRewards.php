<?php

namespace App\Console\Commands;

use App\User;
use App\Models\CashReward;
use App\Services\Banking\BankingIface;
use Illuminate\Console\Command;

class SendAllCashRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rewards:send_cash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all not processed cash rewards';

    protected $banking;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BankingIface $banking)
    {
        parent::__construct();
        $this->banking = $banking;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rewards = CashReward::where('payed', false)->whereNotNull('user_id')->get();
        $rewardsCount = $rewards->count();
        for($i=0; $i<$rewardsCount; $i++){
            $reward = $rewards->pop();
            file_put_contents(storage_path('COMM'), $reward->user_id . PHP_EOL, FILE_APPEND);
            $user = User::find($reward->user_id);
            $result = $this->banking->sendMoney('defaultAcc', $user->bank_acc, $reward->amount);
            if($result === true){
                $reward->payed = true;
                $reward->save();
            }
        }
    }
}
