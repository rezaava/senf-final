<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.wallet.index', compact('user'));
    }

    public function charge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000', // حداقل ۱۰۰۰ تومان
        ]);

        $user = User::find(Auth::user()->id);

        // فرض بر اینکه جدول users فیلدی به نام `wallet_balance` دارد
        $user->increment('wallet', $request->amount);
        $role = match ($user->roles()->first()->name) {
            'manager' => 'organ_id', // مدیر سالن
            'operator' => 'operator_id', // اپراتور
            'user' => 'user_id', // کاربر
            default => 'user_id', // دیفالت
        };
        $id = match ($role) {
            'organ_id' => $user->organ_id, // مدیر سالن
            'operator' => $user->id, // اپراتور
            'user' => $user->id, // کاربر
            default => $user->id, // دیفالت
        };
        // return $id;
        $transaction = Transaction::create([
            'user_id' => $user->id,
            $role => $id,
            'price' => $request->amount,
            'remain' => $user->wallet,
            'description' => 'شارژ کیف پول کاربر ' . $user->name . ' به مبلغ ' . $request->amount . ' تومان',
            'status' => 2,
            'payment_id',
            'invoice_details',
            'transaction_id',
            'transaction_result',
        ]);
        return back()->with('success', 'کیف پول با موفقیت شارژ شد.');
    }
}
