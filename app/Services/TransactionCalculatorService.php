<?php

namespace App\Services;

use App\Contracts\TransactionCalculatorInterface;

class TransactionCalculatorService implements TransactionCalculatorInterface
{
    /**
     * @param $transaction \Illuminate\Database\Eloquent\Collection
     *
     * @return float
     */
    public function calculate($transactions)
    {
        $total = 0.00;
        foreach ($transactions as $transaction) {
            if ('credit' === $transaction->type) {
                $total += $transaction->amount;
            } else {
                $total -= $transaction->amount;
            }
        }

        return $total;
    }
}
