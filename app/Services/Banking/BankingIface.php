<?php

namespace App\Services\Banking;

interface BankingIface
{
    public function sendMoney(string $fromAcc, string $toAcc, float $amount) : bool;
}
