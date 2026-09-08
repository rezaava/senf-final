<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(){
        $cities = City::where('parent', null)->get();
        return view('web.profile' , compact('cities'));
    }

    public function getCitiesByProvince($provinceId)
    {
        $cities = City::where('parent', $provinceId)->get();

        return response()->json(['data' => $cities]);
    }

    public function update(Request $request){
        $request->validate([
            'name'=>'required|string',
            'mobile'=>'required|regex:/^09\d{9}$/',
            'birthDate'=>'nullable',
        ],[
            'name.required'=>'لطفا نام خود را وارد کنید.',
            'mobile.required'=>'شماره موبایل خود را وارد کنید.',
            'mobile.regex'=>'شماره موبایل باید 11 رقمی باید و با 09 شروع شود.',
        ]);
        $user = User::find(auth()->id());
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->birthDate = $request->birthDate;
        $user->city = $request->city;
        $user->city2 = $request->city2;
        $user->save();
        return redirect()->back()->with('success','پروفایل با موفقیت تکمیل شد.');
    }
}
