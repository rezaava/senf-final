<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::user()) {
            return redirect('/');
        } else {
            return view('auth.login');
        }
    }
    public function signin(LoginRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            $user = new User();
            $user->mobile = $request->mobile;
            $user->save();
            $user->addRole('user');
        }
        $user->sms = 12345;
        $user->save();
        return view('auth.code', compact('user'))->with('success', 'کد تایید با موفقیت ارسال شد');
    }
    public function code(Request $request)
    {
        $user = user::where('sms', $request->code)->where('id', $request->id)->first();
        $organ = $user->organs()->first();
        if ($user) {
            $user->sms = null;
            Auth::login($user);
            if($user->hasRole('manager')){
                $organ = $user->organs()->first();
                $user->OrganSelected()->associate($organ);
                $user->save();
                return redirect('/')->with('success', $user->name . ' عزیز! با موفقیت وارد شدید. ');
            }
            // $user->save();
            // if ($user->hasRole('operator') and $user->organs()->count() > 1) {
            //     return redirect(route('selectOrgan'))->with('success', 'برای ادامه لطفا یک سالن را انتخاب کنید');
            // } else {
            //     $organ = $user->organs()->first();
            //     $user->organ_id = $user->OrganSelected()->associate($organ);
            //     $user->save();
            //     return redirect('/')->with('success', $user->name . ' عزیز! با موفقیت وارد شدید. ');
            // }

        } else {
            return redirect(route('login'))->with('fail', 'کد تایید وارد شده اشتباه است');
        }
    }
    public function signup()
    {
        if (Auth::user()) {
            return redirect('/');
        } else {
            return view('auth.register');
        }
    }
    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        $user->syncRoles(['user']);
        return redirect()->route('login')->with('success', 'ثبت نام با موفقیت تکمیل شد');
    }
    public function logout()
    {
        if (Auth::user()) {
            $user = User::find(Auth::user()->id);
            $user->organ_id = null;
            $user->save();
            Auth::logout();
            return redirect('/')->with('success', 'شما با موفقیت خارج شدید');
        } else {
            return redirect('/');
        }
    }
    public function selectOrgan()
    {
        $user = User::find(Auth::user()->id);
        $organs = $user->organs;
        return view('auth.select_organ', compact('organs'));
    }
    public function selectOrganStore(Request $request)
    {
        // return $request;
        $user = User::find(Auth::user()->id);
        $user->OrganSelected()->associate($request->organ);
        $user->save();
        return redirect('/')->with('success', $user->name . ' عزیز! با موفقیت وارد شدید. ');
    }
}
