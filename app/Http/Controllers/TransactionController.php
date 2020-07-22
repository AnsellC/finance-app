<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionResource;
use App\Transaction;
use Exception;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Transaction::class, 'transaction');
    }

    public function index()
    {
        return TransactionResource::collection(Auth::user()->transactions()->paginate(100));
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function update(Transaction $transaction, TransactionUpdateRequest $request)
    {
        if (!$transaction->update($request->validated())) {
            return response()->json([
                'message' => 'Failed to updated transaction.',
            ], 400);
        }

        return new TransactionResource($transaction->fresh());
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

    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([], 204);
    }
}
