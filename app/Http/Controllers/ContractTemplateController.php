<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractTemplateRequest;
use App\Models\ContractTemplate;
use App\Models\Organ;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractTemplateController extends Controller
{
    public function index()
    {
        $templates = ContractTemplate::where('created_by', auth()->id())->get();
        return view('dashboard.contract_templates.list', compact('templates'));
    }

    public function create()
    {
        $user = User::find(auth()->id());
        if ($user->hasRole('manager')) {
            $organ = Organ::findOrFail(Auth::user()->organ_id);
            return view('dashboard.contract_templates.create_operator', compact('organ'));
        }
        return view('dashboard.contract_templates.create_organ');
    }

    public function store(StoreContractTemplateRequest $request)
    {
        // return $request;
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('manager')) {
            $organ = Organ::find($user->organSelected->id);
        }
        $data = $request->validated();
        $contract = ContractTemplate::create([
            'created_by' => auth()->id(),
            'organ_id' => $organ->id ?? null,
            'title' => $request->title,
            'target_role' => $request->target_role,
            'type' => $request->target_role === 'operator' ? $request->type : null,
            'text' => $request->text,
            'percentage' => $request->percentage,
            'amount' => $request->amount,
        ]);
        // اگر قرارداد از نوع درصدی بود، سرویس‌ها رو با درصدشون ذخیره کن
        if ($contract->type === 'percentage' && isset($data['service']) && is_array($data['service'])) {
            $serviceData = [];
            foreach ($data['service'] as $serviceId => $percentage) {
                $serviceData[$serviceId] = ['percentage' => $percentage];
            }

            $contract->services()->sync($serviceData); // ذخیره در جدول میانی
        }
        return redirect()->route('contract-templates.index')->with('success', 'قرارداد ذخیره شد.');
    }

    public function edit(ContractTemplate $contractTemplate)
    {

        return view('dashboard.contract_templates.edit', compact('contractTemplate'));
    }

    public function update(StoreContractTemplateRequest $request, ContractTemplate $contractTemplate)
    {

        $data = $request->validated();

        $contractTemplate->update([
            'title' => $data['title'],
            'target_role' => $data['target_role'],
            'type' => $request->target_role === 'operator' ? $request->type : null,
            'text' => $data['text'],
            'percentage' => $data['percentage'] ?? null,
            'amount' => $data['amount'] ?? null,
        ]);

        if ($contractTemplate->type === 'percentage' && isset($data['service'])) {
            $serviceData = [];
            foreach ($data['service'] as $serviceId => $percentage) {
                $serviceData[$serviceId] = ['percentage' => $percentage];
            }
            $contractTemplate->services()->sync($serviceData);
        } else {
            $contractTemplate->services()->detach(); // چون نوع قرارداد دیگه percentage نیست
        }

        return redirect()->route('contract-templates.index')->with('success', 'قرارداد با موفقیت ویرایش شد.');
    }

    public function destroy(ContractTemplate $contractTemplate)
    {
        $contractTemplate->delete();
        return back()->with('success', 'قرارداد حذف شد.');
    }
}
