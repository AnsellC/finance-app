<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        return TransactionResource::collection(Auth::user()->transactions()->paginate(100));
    }

    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'label' => $validated['label'],
            'amount' => abs($validated['amount']),
            'date' => $validated['date'],
            'type' => intval($validated['amount']) < 0 ? 'debit' : 'credit',
        ]);

        return response()->json(new TransactionResource($transaction), 201);
    }
}
