<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
        return view("dashboard.index");
    }
    public function role(){
        $admin = Role::create([
            'name'=>'admin',
            'display_name'=>'ادمین',
            'description'=>'ادمین اصلی سایت'
        ]);
        $organ = Role::create([
            'name'=>'manager',
            'display_name'=>'مدیر سالن',
            'description'=>'مدیر سالن'
        ]);
        $operator = Role::create([
            'name'=>'operator',
            'display_name'=>'اپراتور',
            'description'=>'اپراتور سالن'
        ]);
        $user = Role::create([
            'name'=>'user',
            'display_name'=>'کاربر',
            'description'=>'کاربر عادی سایت'
        ]);
        return 'ok';
    }
}
