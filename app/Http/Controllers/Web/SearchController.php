<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Organ;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        return view('web.search', compact('categories'));
    }

    /**
     * دریافت دسته‌بندی‌های خدمات
     */
    public function getCategories(Request $request)
    {
        $categories = Category::orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'categories' => $categories,
        ]);
    }

    /**
     * دریافت شهرها
     */
    public function getCities(Request $request)
    {
        $cities = City::where('active', 1)
            ->orderBy('title')
            ->get(['id', 'title', 'parent']);

        return response()->json([
            'success' => true,
            'cities' => $cities,
        ]);
    }

    /**
     * جستجوی آرایشگاه‌ها
     */
    public function searchSalons(Request $request)
    {
        $city = session('user_city', 'تهران');
        $cityId = City::where('title', $city)->first();
        $query = Organ::with(['services'])
            ->where('status', 1)->where('city_id', $cityId?->id ?? 8);

        // فیلتر بر اساس نام
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // فیلتر بر اساس شهر
        if ($request->has('city_id') && $request->city_id) {
            $query->where('city_id', $request->city_id);
        }

        // مرتب‌سازی
        // $sortBy = $request->get('sort_by', 'rating');
        // switch ($sortBy) {
        //     case 'rating':
        //         $query->orderBy('average_rating', 'desc');
        //         break;
        //     case 'cheapest':
        //         $query->orderBy('min_price', 'asc');
        //         break;
        //     case 'expensive':
        //         $query->orderBy('max_price', 'desc');
        //         break;
        //     case 'nearest':
        //         if ($request->has('latitude') && $request->has('longitude')) {
        //             $lat = $request->latitude;
        //             $lng = $request->longitude;
        //             $query->selectRaw('*,
        //                 (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
        //                 [$lat, $lng, $lat])
        //                 ->orderBy('distance', 'asc');
        //         }
        //         break;
        // }

        $salons = $query->paginate(12);

        return response()->json([
            'success' => true,
            'salons' => $salons,
            'total' => $salons->total(),
        ]);
    }

    /**
     * جستجوی خدمات
     */
    public function searchServices(Request $request)
    {
        $city = session('user_city', 'تهران');
        // return $city;
        $cityId = City::where('title', $city)->first();
        $query = Service::query()->with(['category', 'organ'])->whereHas('organ', function ($q) use ($cityId) {
            $q->where('city_id', $cityId?->id ?? 8);
        });

        // فیلتر بر اساس دسته‌بندی
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // فیلتر بر اساس شهر
        if ($request->has('city_id') && $request->city_id) {
            $query->whereHas('organ', function ($q) use ($request) {
                $q->where('city_id', $request->city_id);
            });
        }

        // فیلتر بر اساس تاریخ
        if ($request->has('date') && $request->date) {
            // اینجا می‌توانید منطق بررسی زمان‌های خالی آرایشگاه‌ها را اضافه کنید
        }

        // مرتب‌سازی
        $sortBy = $request->get('sort_by', 'rating');
        switch ($sortBy) {
            case 'rating':
                $query->orderBy('score', 'desc');
                break;
            case 'cheapest':
                $query->orderBy('price', 'asc');
                break;
            case 'expensive':
                $query->orderBy('price', 'desc');
                break;
        }

        $services = $query->paginate(12);

        return response()->json([
            'success' => true,
            'services' => $services,
            'total' => $services->total(),
        ]);
    }

    /**
     * دریافت جزئیات آرایشگاه
     */
    public function getSalonDetail($id)
    {
        $salon = Organ::with(['services'])
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if (! $salon) {
            return response()->json([
                'success' => false,
                'message' => 'آرایشگاه یافت نشد',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'salon' => $salon,
        ]);
    }
}
