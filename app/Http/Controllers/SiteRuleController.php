<?php

namespace App\Http\Controllers;

use App\Models\SiteRule;
use Illuminate\Http\Request;

class SiteRuleController extends Controller
{
    public function edit()
    {
        $rule = SiteRule::firstOrCreate([], ['content' => '']);
        return view('dashboard.site-rules.edit', compact('rule'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10',
        ]);

        $rule = SiteRule::first();
        $rule->update(['content' => $request->content]);

        return redirect()->route('site-rules.edit')->with('success', 'قوانین با موفقیت به‌روزرسانی شد.');
    }
}
