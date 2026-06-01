<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganRequest;
use App\Models\Category;
use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Models\Organ;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class OrganController extends Controller
{
    public function list()
    {
        $organs = Organ::paginate(20);
        return view('dashboard.organs.list', compact('organs'));
    }
    public function create()
    {
        return view('dashboard.organs.create');
    }
    public function store(OrganRequest $request)
    {
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $time = str_replace($persianNumbers, $englishNumbers, $request->date);
        $jalaliDate = Jalalian::fromFormat('Y/m/d', $time);
        $gregorianDate = $jalaliDate->toCarbon()->format('Y-m-d');

        $user = User::find(Auth::user()->id);

        $organ = new Organ();
        $organ->name = $request->name;
        $organ->status = 0;
        $organ->phone = $request->phone;
        $organ->mobile = $request->mobile;
        $organ->address = $request->address;
        $organ->postalCode = $request->postalCode;
        $organ->description = $request->description;
        $organ->email = $request->email;
        $organ->RegistrationDate = $gregorianDate;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/organs/image';
            $file->move($destination_path, $file_name);
            $organ->image = $destination_path . '/' . $file_name;
        }
        $user->OrganManaging()->save($organ);
        $user->organs()->attach($organ);
        $category = Category::find($request->category);
        if ($category) {
            $organ->Categories()->attach($category);
        }
        $user->save();

        $requestChalor = new ModelsRequest([
            'title' => 'درخواست همکاری',
            'status' => 0,
            'description' => ' درخواست همکاری آقا/خانم ' . $user->name . ' با صنف ' . $organ->name,
        ]);
        $organ->requests()->save($requestChalor);
        // $user->requests_owner()->associate($requestChalor);
        $requestChalor->user()->associate($user);
        $organ->save();
        $requestChalor->save();


        $template = ContractTemplate::findOrFail($request->contract);
        $contract = new Contract();
        $contract->template_id   = $template->id;
        $contract->from_user_id  = $template->creator->id;           // کسی که قرارداد رو ایجاد می‌کنه
        $contract->to_user_id    = auth()->id();            // طرف دوم قرارداد
        $contract->organ_id      = $organ->id;              // سالن طرف قرارداد
        $contract->status        = 'pending';              // حالت اولیه
        $contract->start_date    = now();                   // یا از فرم دریافت کن
        $contract->end_date      = now()->addYear();        // یا تاریخ سفارشی
        $contract->signed_at     = null;                    // وقتی تایید شد می‌زنیم
        $contract->save();

        return redirect('/')->with('success', 'درخواست همکاری با موفقیت ارسال شد');
    }
    public function delete($id)
    {
        $Organ = Organ::find($id);
        if ($Organ) {
            $Organ->delete();
        }
        return redirect()->back()->with('success', 'ارگان با موفقیت حذف شد');
    }
    public function update($id, OrganRequest $request)
    {

        $Organ = Organ::find($id);
        $Organ->name = $request->name;
        $Organ->phone = $request->phone;
        $Organ->addres = $request->addres;
        $Organ->description = $request->description;
        $Organ->manager_id = $request->manager_id;
        $Organ->mobail = $request->mobail;
        $Organ->email = $request->email;
        $Organ->save();

        return redirect()->back()->with('success', 'ارگان با موفقیت ویرایش شد');
    }
    public function status(Organ $organ, Request $request)
    {
        $organ->status = $request->status;
        $organ->save();
        return redirect()->back()->with('success', 'وضعیت سالن با موفقیت ویرایش شد.');
    }
    public function profile(Organ $organ)
    {
        return view('dashboard.organs.show', compact('organ'));
    }
    public function search(Request $request)
    {
        $keyword = $request->query('q');

        $organ = Organ::with(['services', 'contractTemplate.services'])->where('name', "$keyword")
            ->orWhere('id', $keyword)->get();

        if (!$organ) {
            return response()->json(['error' => 'ارگانی یافت نشد.']);
        }

        return response()->json($organ);
    }
}
