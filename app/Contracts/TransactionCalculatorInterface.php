<?php

namespace App\Contracts;

interface TransactionCalculatorInterface
{
    /**
     * @param $transaction \Illuminate\Database\Eloquent\Collection
     *
     * @return float
     */
    public function calculate($transaction);
}
