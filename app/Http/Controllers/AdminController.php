<?php

namespace App\Http\Controllers;

use App\Models\Government;
use App\Models\OfferService;
use App\Models\User;
use App\Models\Review;
use App\Models\GovernmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    // عرض لوحة تحكم المسؤول
    public function dashboard()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'غير مصرح لك بالدخول إلى هذه الصفحة');
        }

        $stats = [
            'governments_count' => Government::count(),
            'services_count' => OfferService::count(),
            'users_count' => User::count(),
            'reviews_count' => Review::count(),
            'latest_users' => User::orderBy('created_at', 'desc')->take(5)->get(),
            'latest_reviews' => Review::with(['user', 'government'])->orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // دالة مساعدة لرفع الصور
    private function uploadImages($files, $path)
    {
        $uploaded = [];
        foreach ($files as $file) {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($path, $filename, 'public');
            $uploaded[] = $filePath;
        }
        return $uploaded;
    }

    // دالة لجلب الإحداثيات والعنوان من عنوان نصي
    private function getLocationFromAddress($searchAddress)
    {
        if (empty($searchAddress)) {
            return ['lat' => null, 'lng' => null, 'address' => null];
        }

        $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($searchAddress) . '&format=json&limit=1&addressdetails=1';

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Dalili-Al-Thacki/1.0'
            ])->get($url);

            $data = $response->json();

            if (!empty($data) && isset($data[0])) {
                return [
                    'lat' => $data[0]['lat'],
                    'lng' => $data[0]['lon'],
                    'address' => $data[0]['display_name'] ?? $searchAddress
                ];
            }
        } catch (\Exception $e) {
            // في حالة فشل الاتصال بالـ API
        }

        return ['lat' => null, 'lng' => null, 'address' => null];
    }

    // ==================== إدارة الجهات ====================
    public function governments()
    {
        $governments = Government::with('category')->orderBy('created_at', 'desc')->get();
        $categories = GovernmentCategory::all();
        $services = OfferService::orderBy('name')->get();
        return view('admin.governments', compact('governments', 'categories', 'services'));
    }

    public function storeGovernment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'search_address' => 'nullable|string|max:500',
            'contact_number' => 'nullable|string|max:20',
            'work_hours' => 'nullable|string|max:50',
            'category_id' => 'required|exists:government_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $governmentData = [
            'name' => $request->name,
            'description' => $request->description,
            'contact_number' => $request->contact_number,
            'work_hours' => $request->work_hours,
            'government_category_id' => $request->category_id,
        ];

        // جلب الإحداثيات والعنوان من البحث
        if ($request->filled('search_address')) {
            $location = $this->getLocationFromAddress($request->search_address);
            $governmentData['location_lat'] = $location['lat'];
            $governmentData['location_long'] = $location['lng'];
            $governmentData['address'] = $location['address'];
        }

        // رفع الصور
        if ($request->hasFile('images')) {
            $path = 'governments/' . time();
            $governmentData['images'] = $this->uploadImages($request->file('images'), $path);
        }

        $government = Government::create($governmentData);

        return redirect()->route('admin.governments')->with('success', 'تم إضافة الجهة بنجاح');
    }

    public function updateGovernment(Request $request, $id)
    {
        $government = Government::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'search_address' => 'nullable|string|max:500',
            'contact_number' => 'nullable|string|max:20',
            'work_hours' => 'nullable|string|max:50',
            'category_id' => 'required|exists:government_categories,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'remove_images' => 'nullable|array',
            'services' => 'nullable|array',
            'services.*.id' => 'exists:offer_services,id',
            'services.*.description' => 'nullable|string',
            'services.*.contact_number' => 'nullable|string|max:20',
            'services.*.work_hours' => 'nullable|string|max:50',
            'services.*.price' => 'nullable|string|max:100',
        ]);

        $governmentData = [
            'name' => $request->name,
            'description' => $request->description,
            'contact_number' => $request->contact_number,
            'work_hours' => $request->work_hours,
            'government_category_id' => $request->category_id,
        ];

        // جلب الإحداثيات والعنوان من البحث (إذا تم إدخال عنوان جديد)
        if ($request->filled('search_address')) {
            $location = $this->getLocationFromAddress($request->search_address);
            $governmentData['location_lat'] = $location['lat'];
            $governmentData['location_long'] = $location['lng'];
            $governmentData['address'] = $location['address'];
        } else {
            $governmentData['location_lat'] = $government->location_lat;
            $governmentData['location_long'] = $government->location_long;
            $governmentData['address'] = $government->address;
        }

        // معالجة الصور الحالية
        $currentImages = $government->images ?? [];

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index) {
                if (isset($currentImages[$index])) {
                    Storage::disk('public')->delete($currentImages[$index]);
                    unset($currentImages[$index]);
                }
            }
            $currentImages = array_values($currentImages);
        }

        if ($request->hasFile('new_images')) {
            $path = 'governments/' . $government->id;
            $newImages = $this->uploadImages($request->file('new_images'), $path);
            $currentImages = array_merge($currentImages, $newImages);
        }

        $governmentData['images'] = $currentImages;
        $government->update($governmentData);

        // معالجة الخدمات مع pivot
        if ($request->has('services')) {
            $servicesData = [];
            foreach ($request->services as $serviceId => $details) {
                if (isset($details['id'])) {
                    $servicesData[$details['id']] = [
                        'description' => $details['description'] ?? null,
                        'contact_number' => $details['contact_number'] ?? null,
                        'work_hours' => $details['work_hours'] ?? null,
                        'price' => $details['price'] ?? null,
                    ];
                }
            }
            $government->services()->sync($servicesData);
        } else {
            $government->services()->detach();
        }

        return redirect()->route('admin.governments')->with('success', 'تم تحديث الجهة بنجاح');
    }

    public function destroyGovernment($id)
    {
        $government = Government::findOrFail($id);

        if ($government->images) {
            foreach ($government->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $government->delete();

        return redirect()->route('admin.governments')->with('success', 'تم حذف الجهة بنجاح');
    }

    // ==================== إدارة الخدمات ====================
    public function services()
    {
        $services = OfferService::with('category')->orderBy('created_at', 'desc')->get();
        $categories = GovernmentCategory::all();
        return view('admin.services', compact('services', 'categories'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:government_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $serviceData = [
            'name' => $request->name,
            'description' => $request->description,
            'government_category_id' => $request->category_id,
        ];

        if ($request->hasFile('images')) {
            $path = 'services/' . time();
            $serviceData['images'] = $this->uploadImages($request->file('images'), $path);
        }

        OfferService::create($serviceData);

        return redirect()->route('admin.services')->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function updateService(Request $request, $id)
    {
        $service = OfferService::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:government_categories,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        $serviceData = [
            'name' => $request->name,
            'description' => $request->description,
            'government_category_id' => $request->category_id,
        ];

        $currentImages = $service->images ?? [];

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index) {
                if (isset($currentImages[$index])) {
                    Storage::disk('public')->delete($currentImages[$index]);
                    unset($currentImages[$index]);
                }
            }
            $currentImages = array_values($currentImages);
        }

        if ($request->hasFile('new_images')) {
            $path = 'services/' . $service->id;
            $newImages = $this->uploadImages($request->file('new_images'), $path);
            $currentImages = array_merge($currentImages, $newImages);
        }

        $serviceData['images'] = $currentImages;
        $service->update($serviceData);

        return redirect()->route('admin.services')->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroyService($id)
    {
        $service = OfferService::findOrFail($id);

        if ($service->images) {
            foreach ($service->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $service->delete();

        return redirect()->route('admin.services')->with('success', 'تم حذف الخدمة بنجاح');
    }

    // ==================== إدارة المستخدمين ====================
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => 'required|in:admin,registered',
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('success', 'تم تحديث دور المستخدم بنجاح');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'تم حذف المستخدم بنجاح');
    }
}
