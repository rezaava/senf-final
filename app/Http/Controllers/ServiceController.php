<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Organ;
use App\Models\OrganUser;
use App\Models\ServiceUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ServiceController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $organ = $user->OrganManaging()->first();
        $services = $organ->services;
        return view('dashboard.service.index', compact('services'));
    }
    public function create()
    {
        return view('dashboard.service.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'price_max' => 'nullable|numeric',
            // 'off_type' => 'required',
            'off' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ], [
            'name.required' => 'وارد کردن نام سرویس الزامی است.',
            'name.string' => 'نام سرویس باید رشته باشد.',
            'name.max' => 'نام سرویس نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'price.required' => 'وارد کردن قیمت سرویس الزامی است.',
            'price.numeric' => 'قیمت سرویس باید عددی باشد.',
            'off_type.required' => 'نوع تخفیف الزامی است.',
            'off.numeric' => 'مقدار تخفیف باید عددی باشد.',
            'description.string' => 'توضیحات باید رشته باشد.',
            'image.image' => 'تصویر باید به فرمت مناسب باشد.',
        ]);
        $user = User::find(Auth::user()->id);
        $organ = Organ::find($user->OrganManaging()->first()->id);
        $service = new Service();
        $service->name = $request->name;
        $service->price = $request->price;
        $service->price_max = $request->price_max;
        $service->off_type = $request->off_type;
        $service->off = filled($request->off) ? $request->off : 0;
        $service->description = $request->description;
        if ($request->off_type == 1 and $request->off) {
            $service->off_price = $request->price - ($request->price * $request->off / 100);
        } elseif ($request->off_type == 2) {
            $service->off_price = $request->price - $request->off;
        } else {
            $service->off_price = $request->price;
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('files/services', $imageName);
            $service->image = 'files/services/' . $imageName;
        }
        if ($organ) {
            $organ->services()->save($service);
        } else {
            return redirect()->back()->with('fail', 'صنف معتبر نمی‌باشد');
        }
        return redirect()->route('service.list')->with('success', 'خدمت با موفقیت اضافه شد');
    }
    public function show($id)
    {
        $service = Service::with('organ')->findOrFail($id);
        return response()->json($service);
    }
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('dashboard.service.edit', compact('service'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'price_max' => 'nullable|numeric',
            // 'off_type' => 'required',
            'off' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ], [
            'name.required' => 'وارد کردن نام سرویس الزامی است.',
            'name.string' => 'نام سرویس باید رشته باشد.',
            'name.max' => 'نام سرویس نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'price.required' => 'وارد کردن قیمت سرویس الزامی است.',
            'price.numeric' => 'قیمت سرویس باید عددی باشد.',
            'off_type.required' => 'نوع تخفیف الزامی است.',
            'off.numeric' => 'مقدار تخفیف باید عددی باشد.',
            'description.string' => 'توضیحات باید رشته باشد.',
            'image.image' => 'تصویر باید به فرمت مناسب باشد.',
        ]);
        $service = Service::findOrFail($id);
        $service->name = $request->name;
        $service->price = $request->price;
        $service->price_max = $request->price_max;
        $service->off_type = $request->off_type;
        $service->off = filled($request->off) ? $request->off : 0;
        $service->description = $request->description;
        if ($request->off_type == 1 and $request->off) {
            $service->off_price = $request->price - ($request->price * $request->off / 100);
        } elseif ($request->off_type == 2) {
            $service->off_price = $request->price - $request->off;
        } else {
            $service->off_price = $request->price;
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('files/services', $imageName);
            $service->image = 'files/services/' . $imageName;
        }
        $service->save();
        return redirect()->route('service.list')->with('success', 'خدمت با موفقیت ویرایش شد');
    }
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image && file_exists($service->image)) {
            unlink($service->image);
        }
        $service->delete();
        return redirect()->route('service.list')->with('success', 'خدمت با موفقیت حذف شد');
    }
    public function myServices()
    {
        $user = User::find(Auth::user()->id);
        $services = $user->services()->where('organ_id', $user->organSelected->id)->get();

        $organ_user = OrganUser::where('user_id' , $user->id)->first();

        $organ = $organ_user->organ;
        $organ_services = $organ->services;

        // return $organ_services;
        return view('dashboard.service.MyServices', compact('services' , 'organ_services'));
    }

    public function addMyService(Request $req){

        $exists = ServiceUser::where('user_id', Auth::user()->id)->where('service_id', $req->service)->first();

        if ($exists) {
            return back()->with('error', 'این خدمت قبلاً انتخاب شده است');
        }

        $service = new ServiceUser();

        $service->user_id = Auth::user()->id;
        $service->service_id = $req->service;
        $service->save();

        return back()->with('success', 'خدمت با موفقیت اضافه شد');
    }
}
