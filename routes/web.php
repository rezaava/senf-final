<?php

use App\Http\Controllers\API\AvailableSlotController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\OtpAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContractTemplateController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationalVideoController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrganController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SalonCalendarController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SiteRuleController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SpecialOfferController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\SiteController;
use App\Http\Controllers\Web\UserController as WebUserController;
use App\Http\Controllers\WorkHourController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// otp authentication ==================================================================================================================
Route::middleware('guest')->group(function () {
    // Step 1: Phone
    Route::get('/login', [OtpAuthController::class, 'showPhoneForm'])
        ->name('login');

    Route::post('/auth/phone', [OtpAuthController::class, 'sendOtp'])
        ->name('auth.phone.send');
    // Route::post('/auth/phone', [OtpAuthController::class, 'sendOtp'])
    //     ->name('auth.phone.send')->middleware('throttle:otp');

    // Step 2: OTP
    Route::post('/auth/verify', [OtpAuthController::class, 'verifyOtp'])
        ->name('auth.otp.verify');

    Route::post('/auth/resend', [OtpAuthController::class, 'resendOtp'])
        ->name('auth.otp.resend');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// روت‌های پس از لاگین
Route::middleware('auth')->group(function () {
    Route::get('/api/cities/by-province/{province}', [WebUserController::class, 'getCitiesByProvince']);
    // ذخیره جنسیت (برای کاربران جدید)
    Route::post('/auth/save-gender', [OtpAuthController::class, 'saveGender'])
        ->name('auth.save.gender');

    // دریافت نقش‌های کاربر
    Route::post('/auth/get-roles', [OtpAuthController::class, 'getUserRoles'])
        ->name('auth.get.roles');

    // انتخاب نقش (برای کاربرانی که چند نقش دارند)
    Route::post('/auth/select-role', [OtpAuthController::class, 'selectRole'])
        ->name('auth.select.role');

    // دریافت لیست سالن‌های آرایشگر
    Route::post('/auth/get-operator-salons', [OtpAuthController::class, 'getOperatorSalons'])
        ->name('auth.get.operator.salons');

    // انتخاب سالن (برای آرایشگران چند سالنه)
    Route::post('/auth/select-salon', [OtpAuthController::class, 'selectSalon'])
        ->name('auth.select.salon');
});

// web pages ========================================================================================================================

Route::get('/', [SiteController::class, 'home'])->name('home');


Route::middleware('auth')->group(function () {

Route::get('/salon/{salon}', [SiteController::class, 'salon'])->name('salon');
Route::get('/service/{service}', [SiteController::class, 'service'])->name('service');
Route::get('/category/{category}', [SiteController::class, 'category'])->name('category');

// گرفتن لوکیشن کاربر
Route::post('/set-location', [SiteController::class, 'set_location']);
Route::post('/set-city', [SiteController::class, 'setCity'])->name('set.city');

// user profile
Route::get('/profile', [WebUserController::class, 'profile'])->name('profile');
Route::post('/profile/update', [WebUserController::class, 'update'])->name('profile.update');

// comment store
Route::post('/comments/store', [CommentController::class, 'store'])->middleware('auth');

// search page
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::prefix('api')->group(function () {
    Route::get('/search/categories', [SearchController::class, 'getCategories'])->name('api.search.categories');
    Route::get('/search/cities', [SearchController::class, 'getCities'])->name('api.search.cities');
    Route::post('/search/salons', [SearchController::class, 'searchSalons'])->name('api.search.salons');
    Route::post('/search/services', [SearchController::class, 'searchServices'])->name('api.search.services');
    Route::get('/salon/{id}', [SearchController::class, 'getSalonDetail'])->name('api.salon.detail');
});

// reservation ======================================================================================================================
Route::post('/reservations/store', [ReservationController::class, 'store']);
Route::get('/api/reservations/available-slots', [AvailableSlotController::class, 'index']);
Route::get('/api/operators/{operator}/available-days', [AvailableSlotController::class, 'days']);
Route::get('/api/operators', [AvailableSlotController::class, 'operators']);

// favorites =====================================================================================================================
Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);

// cart ==========================================================================================================================
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::delete('/cart/remove/{reservation}', [CartController::class, 'remove'])->name('cart.remove');


});


Route::middleware(['auth' , 'role:admin|manager|operator|user' ])->get('/cooperation', [RequestController::class, 'cooperation'])->name('cooperation');

Route::middleware(['auth' , 'role:admin|manager|operator' ])->group(function () {
    // dashboard pages ==================================================================================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');
    Route::post('/organStore', [RequestController::class, 'organStore'])->name('organStore');
    Route::get('/operatorRequest', [RequestController::class, 'operatorRequest'])->name('operatorRequest');
    Route::post('/operatorStore', [RequestController::class, 'operatorStore'])->name('operatorStore');
    Route::get('/search-organ', [OrganController::class, 'search'])->name('organ.search');
    
    // user profile
    Route::prefix('/user')->group(function () {
        Route::get('/{id?}', [UserController::class, 'profile'])->name('user.profile');
        Route::post('/edit/{id}', [UserController::class, 'update'])->name('user.profile.update');
    });
    // user requests
    Route::prefix('/myRequest')->group(function () {
        Route::get('/', [RequestController::class, 'list'])->name('myRequest.list');
        // Route::get('/edit', [OrganController::class,'edit'])->name('request.edit');
        // Route::post('/update', [OrganController::class,'update'])->name('request.update');
        Route::post('/send/{id}', [RequestController::class, 'status'])->name('myRequest.status');
        Route::get('/{id}', [RequestController::class, 'show'])->name('myRequest.show');
    });

    Route::prefix('/dashboard')->group(function () {
        // operators
        Route::prefix('/users')->group(function () {
            Route::get('/new', [UserController::class, 'new'])->name('user.create');
            Route::post('/new/{organ}', [UserController::class, 'newPost'])->name('user.store');
            Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::post('/edit/{id}', [UserController::class, 'editPost'])->name('user.editPost');
            Route::get('/{id?}', [UserController::class, 'list'])->name('user.list');
        });
        // categories
        Route::prefix('/categories')->group(function () {
            Route::get('/', [CategoryController::class, 'list'])->name('category.list');
            Route::get('/parent/{id}', [CategoryController::class, 'listParent']);
            Route::get('/new', [CategoryController::class, 'new'])->name('category.new');
            Route::post('/new', [CategoryController::class, 'newPost'])->name('category.newPost');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::post('/edit/{id}', [CategoryController::class, 'editPost'])->name('category.editPost');
            Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');
        });
        // organs
        Route::prefix('/organs')->group(function () {
            Route::get('/', [OrganController::class, 'list'])->name('organ.list');
            Route::get('/create', [OrganController::class, 'create'])->name('organ.create');
            Route::post('/store', [OrganController::class, 'store'])->name('organ.store');
            Route::get('/edit', [OrganController::class, 'edit'])->name('organ.edit');
            Route::post('/update', [OrganController::class, 'update'])->name('organ.update');
            Route::post('/status/{organ}', [OrganController::class, 'status'])->name('organ.status');
            Route::get('/profile/{organ}', [OrganController::class, 'profile'])->name('organ.profile');
        });
        // requests
        Route::prefix('/request')->group(function () {
            Route::get('/', [RequestController::class, 'list'])->name('request.list');
            // Route::get('/edit', [OrganController::class,'edit'])->name('request.edit');
            // Route::post('/update', [OrganController::class,'update'])->name('request.update');
            Route::post('/status/{id}', [RequestController::class, 'status'])->name('request.status');
            Route::get('/{id}', [RequestController::class, 'show'])->name('request.show');
        });
        // services
        Route::prefix('/services')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('service.list');
            Route::get('/create', [ServiceController::class, 'create'])->name('service.create');
            Route::post('/store', [ServiceController::class, 'store'])->name('service.store');
            Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
            Route::post('/update/{id}', [ServiceController::class, 'update'])->name('service.update');
            Route::get('/delete/{id}', [ServiceController::class, 'destroy'])->name('service.delete');
        });
        // my services (operators)
        Route::prefix('/Myservices')->group(function () {
            Route::get('/', [ServiceController::class, 'myServices'])->name('Myservices.list');
            Route::post('/add/service' , [ServiceController::class , 'addMyService']);
            // Route::get('/create', [ServiceController::class, 'create'])->name('service.create');
            // Route::post('/store', [ServiceController::class, 'store'])->name('service.store');
            // Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
            // Route::post('/update/{id}', [ServiceController::class, 'update'])->name('service.update');
            Route::get('/delete/{id}', [ServiceController::class, 'destroy'])->name('service.delete');
            // appointments
            Route::get('/{service}/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
            Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
            Route::get('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.delete');
        });

        // contracts templates
        Route::resource('contract-templates', ContractTemplateController::class);
        // slider
        Route::resource('sliders', SliderController::class);
        // banners
        Route::resource('/banners', BannerController::class)->except(['show']);
        // special offers
        Route::get('/special-offers', [SpecialOfferController::class, 'edit'])->name('special-offers.edit');
        Route::post('/special-offers', [SpecialOfferController::class, 'update'])->name('special-offers.update');
        // coupons
        Route::resource('/coupons', CouponController::class)->except(['show']);
        // gllery
        Route::resource('galleries', GalleryController::class);
        // educational videos
        Route::resource('educational-videos', EducationalVideoController::class);
        // site rules
        Route::get('/site-rules/edit', [SiteRuleController::class, 'edit'])->name('site-rules.edit');
        Route::put('/site-rules/update', [SiteRuleController::class, 'update'])->name('site-rules.update');
        // appointments
        Route::get('/appointments', [AppointmentController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/show', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments/update-status', [AppointmentController::class, 'update_status'])->name('appointments.update-status');
        // orders
        Route::prefix('/orders')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('orders.index');
            Route::get('/show/{reservation}', [OrderController::class, 'show'])->name('orders.show');
            Route::get('/status/{order}', [OrderController::class, 'status'])->name('orders.status');
            Route::get('/price-offer/{id}', [OrderController::class, 'price_offer'])->name('orders.price-offer');
            Route::post('/{reservation}/suggest-price', [OrderController::class, 'suggestPrice'])->name('appointments.suggest_price');
            Route::post('/{reservation}/finalize-price', [OrderController::class, 'finalizePrice'])->name('appointments.finalize_price');
        });
        // wallet
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
        Route::post('/wallet/charge', [WalletController::class, 'charge'])->name('wallet.charge');
        // transactions
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transaction.index');
        // ticket
        Route::prefix('/tickets')->group(function () {
            Route::get('/', [TicketController::class, 'index'])->name('tickets.index');
            Route::get('/create', [TicketController::class, 'create'])->name('tickets.create');
            Route::post('/store', [TicketController::class, 'store'])->name('tickets.store');
            Route::get('/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
            Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
        });
        // reports
        Route::prefix('admin/reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index'); // لیست کلی درآمد سالن‌ها
            Route::get('/{organ}', [ReportController::class, 'show'])->name('show'); // جزئیات هر سالن
        });
        Route::prefix('/comments')->name('comments.')->group(function () {
            Route::get('/', [CommentController::class, 'index'])->name('index');
            Route::get('/organs', [CommentController::class, 'organs'])->name('organs');
            Route::get('/services', [CommentController::class, 'services'])->name('services');
            Route::get('/approve/{comment}', [CommentController::class, 'approve'])->name('approve');
            Route::get('/delete/{comment}', [CommentController::class, 'destroy'])->name('delete');
        });


        // reservation =============================================================================================================
        // work hour
        Route::get('/work-hour/{user?}', [WorkHourController::class, 'index'])->name('work-hour.index');
        Route::post('/work-hour/store', [WorkHourController::class, 'store'])->name('work-hour.store');

        // operator calender =============================
        Route::get('/calendar/{operator}', [CalendarController::class, 'index'])->name('calende.operator');
        Route::get('/calendar-data/{operator}', [CalendarController::class, 'data']);
        Route::post('/operator/time-off', [CalendarController::class, 'time_off'])->name('time_off');

        // organ calender ================================
        Route::get('/calendar/organ/{organ}', [CalendarController::class, 'organ_index'])->name('calende.organ');
        Route::get('/salon-calendar/data', [SalonCalendarController::class, 'data']);
        Route::post('/salon-calendar/store', [SalonCalendarController::class, 'store']);
    });

});

Route::get('/admin/ajax/operator-services/{id}', function ($id) {
    $user = User::findOrFail($id);
    $services = $user->services()->get();

    return response()->json($services);
});
// captcha
Route::get('/refresh-captcha', function () {
    return captcha_img('default');
});
