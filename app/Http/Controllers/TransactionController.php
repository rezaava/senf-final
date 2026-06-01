<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::find(auth()->id());

        $query = Transaction::query();

        // نقش‌ها رو چک می‌کنیم
        if ($user->hasRole('admin')) {
            if ($request->filled('organ_id')) {
                $query->where('organ_id', $request->organ_id);
            }
            if ($request->filled('operator_id')) {
                $query->where('operator_id', $request->operator_id);
            }
        } elseif ($user->hasRole('manager')) {
            $query->where('organ_id', $user->organ_id); // مثلاً هر مدیر یک organ_id داره
            if ($request->filled('operator_id')) {
                $query->where('operator_id', $request->operator_id);
            }
        } else {
            // نقش اپراتور
            $query->where('operator_id', $user->id);
        }

        // فیلتر تاریخ
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->latest()->paginate(15);

        // لیست سالن و اپراتور برای فیلترها
        $organs = $user->hasRole('admin') ? Organ::all() : [];
        $operators = $user->hasRole('admin') ? User::all() : ($user->hasRole('manager') ? $user->organSelected->operator()->whereHasRole('operator')->get() : []);

        return view('dashboard.transactions.index', compact('transactions', 'organs', 'operators'));


        // $transactions = Auth::user()->transactions;
        // return view('dashboard.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
