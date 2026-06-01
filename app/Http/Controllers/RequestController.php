<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Models\Organ;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function cooperation()
    {
        $user = User::find(Auth::user()->id);
        if (!$user->meliCode or !$user->birthDate) {
            return redirect()->route('user.profile')->with('fail', 'لطفا ابتدا پروفایل خود را تکمیل کنید');
        } else {
            if ($user->requests_owner()->where('status', 0)->count() > 0) {
                return back()->with('fail', 'شما درخواست در انتظار دارید لطفا تا تایید یا رد درخواست قبلی صبر کنید');
            }
            $categories = Category::all();
            $contracts = ContractTemplate::where('target_role', 'manager')->get();
            return view('dashboard.request.coooperation', compact('categories', 'contracts'));
        }
    }
    public function list()
    {
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('manager')) {
            $requests = $user->OrganManaging()->first()->requests_owner;
        } elseif ($user->hasRole('admin')) {
            $requests = ModelsRequest::whereNull('organ_id')->paginate(20);
        } else {
            $requests = $user->requests_owner;
        }
        return view('dashboard.request.list', compact('requests'));
    }
    public function show($id)
    {
        $request = ModelsRequest::findOrFail($id);
        $organ = $request->requestable;
        if ($request->requestable_type == 'App\\Models\\Organ') {
            $contract = $organ->contracts()->first();
        } elseif ($request->requestable_type == 'App\\Models\\User') {
            $contract = $organ->receivedContracts()->first();
        }
        $contract_template = $contract->template;
        return view('dashboard.request.show', compact('request', 'contract_template'));
    }
    public function status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1', // یا هر مقادیری که داری
            'reject' => 'required_if:status,0',
        ], [
            'status.required' => 'وارد کردن نوع الزامی است.',
            'status.in' => 'نوع انتخاب شده معتبر نیست.',
            'reject.required_if' => 'در صورت رد درخواست فیلد دلیل رد الزامی است.',
        ]);
        $requestModel = ModelsRequest::find($id);
        $requestOwner = $requestModel->requestable;
        if ($requestOwner instanceof Organ) {
            $organ = Organ::find($requestOwner->id);
            $user = User::find($organ->manager_id);
            if ($request->status == 0) {
                $requestModel->update([
                    'status' => 2,
                    'reject' => $request->reject,
                ]);
                $organ->status = 2;
                $contract = Contract::find($organ->contracts()->first()->id);
                $contract->status = 'rejected';
                $contract->save();
                $organ->save();
                $user->organs()->updateExistingPivot($requestOwner->id, [
                    'status' => 2,
                ]);
                $user->receivedContracts();
                $user->save();
                return redirect()->route('request.list')->with('success', 'درخواست با موفقیت رد شد');
            } elseif ($request->status == 1) {
                $requestModel->update([
                    'status' => 1,
                ]);
                $organ->status = 1;
                $contract = Contract::find($organ->contracts()->first()->id);
                $contract->status = 'approved';
                $contract->save();
                $organ->save();
                $user->organs()->updateExistingPivot($requestOwner->id, [
                    'status' => 1,
                ]);
                $user->save();
                $user->syncRoles(['manager']);
                return redirect()->route('request.list')->with('success', 'درخواست با موفقیت تایید شد');
            }
        } elseif ($requestOwner instanceof User) {
            $user = User::find($requestOwner->id);
            if ($request->status == 0) {
                $requestModel->update([
                    'status' => 2,
                    'reject' => $request->reject,
                ]);
                $user->organs()->updateExistingPivot($requestModel->organ->id, [
                    'status' => 2,
                ]);
                $user->save();
                return redirect()->route('request.list')->with('success', 'درخواست با موفقیت رد شد');
            } elseif ($request->status == 1) {
                $requestModel->update([
                    'status' => 1,
                ]);
                $user->organs()->updateExistingPivot($requestModel->organ->id, [
                    'status' => 1,
                ]);
                $user->save();
                $user->syncRoles(['operator']);
                return redirect()->route('request.list')->with('success', 'درخواست با موفقیت تایید شد');
            }
        }
    }
    public function operatorRequest()
    {
        $user = User::find(Auth::user()->id);
        if (!$user->meliCode or !$user->birthDate) {
            return redirect()->route('user.profile')->with('fail', 'لطفا ابتدا پروفایل خود را تکمیل کنید');
        } else {
            if ($user->requests()->whereIn('status', [0])->count() > 0) {
                return back()->with('fail', 'شما درخواست در انتظار دارید لطفا تا تایید یا رد درخواست قبلی صبر کنید');
            }
            $organs = Organ::all();
            return view('dashboard.request.operator', compact('organs'));
        }
    }
    public function operatorStore(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $request->validate([
            'organs'   => 'required|array',
            'organs.*' => 'exists:organs,id',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'contract.*' => 'required',
        ]);
        // Sync organs and services
        $user->organs()->sync($request->organs);
        $user->services()->sync($request->services ?? []);

        foreach ($request->organs as $key => $organId) {
            // ساخت یک درخواست جدید برای هر ارگان
            $organ = Organ::find($organId);
            $requestChalor = new ModelsRequest([
                'title' => 'درخواست اپراتور شدن',
                'status' => 0,
                'description' => 'درخواست اپراتور شدن توسط ' . $user->name,
                'organ_id' => $organId, // اگر مدل request فیلد organ_id داره
            ]);

            // اتصال درخواست به کاربر درخواست‌دهنده
            $user->requests()->save($requestChalor);

            // اگر کاربر owner هست (یا کاربری که درخواست براش فرستاده شده)، ارتباط رو برقرار کن
            $owner = User::find(Auth::user()->id);
            $requestChalor->organ()->associate($organ);
            $requestChalor->user()->associate($owner->id);
            $requestChalor->save();

            // contract
            $template = ContractTemplate::findOrFail($request->contract[$key]);
            $contract = new Contract();
            $contract->template_id   = $template->id;
            $contract->from_user_id  = $template->creator->id;           // کسی که قرارداد رو ایجاد می‌کنه
            $contract->to_user_id    = auth()->id();            // طرف دوم قرارداد
            $contract->organ_id      = $organId;              // سالن طرف قرارداد
            $contract->status        = 'pending';              // حالت اولیه
            $contract->start_date    = now();                   // یا از فرم دریافت کن
            $contract->end_date      = now()->addYear();        // یا تاریخ سفارشی
            $contract->signed_at     = null;                    // وقتی تایید شد می‌زنیم
            $contract->save();
        }
        return redirect(Route('request.list'))->with('success', 'درخواست با موفقیت ثبت شد.\\n لطفا منتظر تایید سالن باشید.');
    }
}
