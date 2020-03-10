<?php

namespace App\Services\Banking;

class TrueBanking implements BankingIface
{
    public function sendMoney(string $fromAcc, string $toAcc, float $amount) : bool
    {
        return true;
    }
}
