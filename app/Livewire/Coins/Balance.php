<?php

namespace App\Livewire\Coins;

use App\Models\CoinBalance;
use App\Models\CoinTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class Balance extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public function render()
    {
        $balance = CoinBalance::where('user_id', auth()->id())->first();

        $query = CoinTransaction::where('user_id', auth()->id())
            ->with('transactionable');

        if ($this->filter === 'credits') {
            $query->where('type', 'credit');
        } elseif ($this->filter === 'debits') {
            $query->where('type', 'debit');
        }

        $transactions = $query->latest()->paginate(20);

        return view('livewire.coins.balance', [
            'balance' => $balance,
            'transactions' => $transactions,
        ])->layout('layouts.app');
    }
}
