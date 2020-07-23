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

    /**
     * Returns all the transactions for the logged in user.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return TransactionResource::collection(Auth::user()->transactions()->paginate(100));
    }

    /**
     * Returns a specific transaction.
     *
     * @return TransactionResource
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    /**
     * Updates a transaction.
     *
     * @return TransactionResource|\Illuminate\Http\JsonResponse
     */
    public function update(Transaction $transaction, TransactionUpdateRequest $request)
    {
        $validated = $request->validated();

        if (isset($validated['amount'])) {
            $validated['amount'] = abs($validated['amount']);
            $validated['type'] = intval($validated['amount']) < 0 ? 'debit' : 'credit';
        }

        if (!$transaction->update($validated)) {
            return response()->json([
                'message' => 'Failed to updated transaction.',
            ], 400);
        }

        return new TransactionResource($transaction->fresh());
    }

    /**
     * Creates a new transaction.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Deletes a transaction.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
