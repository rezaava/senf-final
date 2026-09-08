<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Gallery;
use App\Models\Organ;
use App\Models\Service;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SiteController extends Controller
{
    public function home()
    {
        $sliders = Slider::where('status', 1)->where('type' , 1)->get();
        $categories = Category::all();
        $top_operators = User::take(5)->whereHasRole('operator')->get();
        $top_organs = Organ::where('status', 1)->take(5)->get();
        $cities = City::where('parent', null)->get();

        return view('web.index', compact('sliders', 'categories', 'top_operators', 'top_organs', 'cities'));
    }

    public function salon(Organ $salon)
    {
        $gallery = Gallery::where('organ_id', $salon->id)->whereNull('operator_id')->get();
        return view('web.salon', compact('salon', 'gallery'));
    }
    public function service(Service $service)
    {
        return view('web.service', compact('service'));
    }
    public function category(Category $category, Request $request)
    {
        $city = session('user_city', 'تهران');
        // return $city;
        $cityId = City::where('title', $city)->first();
        $query = Service::query()->with(['category', 'organ'])->whereHas('organ', function ($q) use ($cityId) {
            $q->where('city_id', $cityId?->id ?? 8);
        });

        // Category
        if ($category) {
            $query->where('category_id', $category->id);
        }
        // Categories
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Price range
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }

        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        // Today only
        if ($request->today) {
            $query->whereHas('reservations', function ($q) {
                $q->whereDate('date', now()->toDateString());
            });
        }

        match ($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating'     => $query->orderBy('score', 'desc'), // اگر فیلد داری
            default      => $query->latest(),
        };

        $services = $query->paginate(10)->withQueryString();

        $categories = Category::where('parent_id', $category->id)->get();
        $sliders = Slider::where('status', 1)->where('type' , 2)->get();
        return view('web.services', compact('category', 'services', 'categories' , 'sliders'));
    }

    public function set_location(Request $request)
    {
        // پیش‌فرض: تهران
        $city = 'تهران';
        $lat = 35.6892;
        $lng = 51.3890;

        if ($request->filled(['lat', 'lng'])) {
            $lat = $request->lat;
            $lng = $request->lng;

            // Reverse Geocoding (تشخیص شهر)
            $city = $this->getCityFromCoordinates($lat, $lng) ?? 'تهران';
        }

        session([
            'user_city' => $city,
            'user_lat'  => $lat,
            'user_lng'  => $lng
        ]);

        return response()->json(['status' => 'ok']);
    }

    private function getCityFromCoordinates($lat, $lng)
    {
        $url = "https://nominatim.openstreetmap.org/reverse";

        $response = Http::withHeaders([
            'User-Agent' => 'YourAppName'
        ])->get($url, [
            'format' => 'json',
            'lat' => $lat,
            'lon' => $lng,
            'accept-language' => 'fa'
        ]);

        if (!$response->successful()) {
            return null;
        }

        $address = $response->json('address');

        // اولویت: شهرستان ← استان
        $rawCity =
            $address['county']
            ?? $address['state']
            ?? null;

        if (!$rawCity) {
            return null;
        }

        if (!City::where('title', $rawCity)->first()) {
            return null;
        }

        return $this->normalizeCityName($rawCity);
    }
    private function normalizeCityName(string $name): string
    {
        $removeWords = [
            'استان',
            'شهرستان',
            'بخش',
            'دهستان',
            'شهر'
        ];

        $cleanName = str_replace($removeWords, '', $name);

        return trim($cleanName);
    }
    public function setCity(Request $request)
    {
        $request->validate([
            'city' => 'required|string'
        ]);

        session(['user_city' => $request->city]);

        return response()->json(['success' => true]);
    }
}
