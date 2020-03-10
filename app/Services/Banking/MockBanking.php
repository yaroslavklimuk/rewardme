<?php

namespace App\Services\Banking;

class MockBanking implements BankingIface
{
    private $responsesQueue = [];

    public function __construct(array $responses)
    {
        $this->responsesQueue = array_reverse($responses);
    }

    public function sendMoney(string $fromAcc, string $toAcc, float $amount) : bool
    {
        return array_pop($this->responsesQueue);
    }
}
