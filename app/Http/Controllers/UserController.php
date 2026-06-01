<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Organ;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Psy\Command\EditCommand;

class UserController extends Controller
{
    public function profile($id = null)
    {
        if ($id) {
            $user = User::findOrFail($id);
        } else {
            $user = User::find(Auth::user()->id);
        }
        return view("dashboard.user.profile", compact("user"));
    }
    public function list($id = null)
    {
        if ($id) {
            $organ = Organ::find($id);
            $users = $organ->users()->whereHasRole('operator')->get();
        } else {

            $user = User::find(Auth::user()->id);
            if ($user->hasRole('manager')) {
                $organ = $user->OrganManaging()->first();
                $users = $organ->users()->whereHasRole('operator')->get();
            } else {
                $users = User::latest()->get();
            }
        }
        return view("dashboard.user.list", compact("users"));
    }
    public function new()
    {
        return view("dashboard.user.new");
    }
    public function newPost(RegisterRequest $request, $organ)
    {
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $time = str_replace($persianNumbers, $englishNumbers, $request->birthDate);
        $jalaliDate = Jalalian::fromFormat('Y/m/d', $time);
        $gregorianDate = $jalaliDate->toCarbon()->format('Y-m-d');

        $user = new User();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->meliCode = $request->meliCode;
        $user->birthDate = $gregorianDate;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->status = 0;
        $user->save();
        $orgarUser = Organ::find($organ);
        $user->organs()->sync([$orgarUser->id]);
        $user->syncRoles(['operator']);

        $requestChalor = new ModelsRequest([
            'title' => 'درخواست تایید اپراتور',
            'status' => 0,
            'description' => ' درخواست تایید اپراتور ' . $user->name . ' برای صنف ' . $orgarUser->name . ' توسط آقا/خانم ' . Auth::user()->name,
        ]);
        $user->requests()->save($requestChalor);
        $owner = User::find(Auth::user()->id);
        $owner->requests_owner()->associate($requestChalor);
        return redirect()->route('user.list')->with('success', '!اپراتور با موفقیت اضافه شد. لطفا منتظر تایید ادمین باشید.');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view("dashboard.users_customers.edit", compact("user"));
    }
    public function update($id, EditUserRequest $request)
    {
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $time = str_replace($persianNumbers, $englishNumbers, $request->birthDate);
        $jalaliDate = Jalalian::fromFormat('Y/m/d', $time);
        $gregorianDate = $jalaliDate->toCarbon()->format('Y-m-d');

        $user = User::find($id);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->meliCode = $request->meliCode;
        $user->birthDate = $gregorianDate;
        $request->filled('password') && $user->password = $request->password;
        $user->email = $request->email;
        $user->save();
        return redirect()->route('index')->with('success', 'پروفایل با موفقیت تکمیل شد.');
    }
    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
        }
        return redirect()->back()->with('success', 'کاربر با موفقیت حذف شد');
    }
}
