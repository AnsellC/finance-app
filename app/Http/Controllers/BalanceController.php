<?php

namespace App\Http\Controllers;

use App\Contracts\TransactionCalculatorInterface;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    private $calculator;

    public function __construct(TransactionCalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Returns the user's current balance.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'balance' => $this->calculator->calculate(Auth::user()->transactions),
        ]);
    }
}
