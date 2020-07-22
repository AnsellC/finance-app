<?php

namespace App\Policies;

use App\Transaction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

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

    public function delete(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id;
    }

    public function create()
    {
        return Auth::check();
    }

    public function viewAny()
    {
        return Auth::check();
    }
}
