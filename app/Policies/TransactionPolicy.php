<?php

namespace App\Policies;

use App\Transaction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function update(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id;
    }

    public function edit(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id;
    }

    public function view(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id;
    }

    public function destroy(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id;
    }
}
