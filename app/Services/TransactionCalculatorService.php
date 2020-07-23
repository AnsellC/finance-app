<?php

namespace App\Services;

use App\Contracts\TransactionCalculatorInterface;

class TransactionCalculatorService implements TransactionCalculatorInterface
{
    /**
     * Calculates the total of a collection of transactions.
     *
     * @param \Illuminate\Database\Eloquent\Collection $transactions
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
