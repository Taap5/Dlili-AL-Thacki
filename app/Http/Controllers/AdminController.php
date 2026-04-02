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
// دالة لجلب الإحداثيات والعنوان من عنوان نصي باللغة العربية
private function getLocationFromAddress($searchAddress)
{
    if (empty($searchAddress)) {
        return ['lat' => null, 'lng' => null, 'address' => null];
    }

    try {
        $apiKey = env('LOCATIONIQ_KEY');

        $response = Http::timeout(20)->get('https://us1.locationiq.com/v1/search.php', [
            'key' => $apiKey,
            'q' => $searchAddress,
            'format' => 'json',
            'limit' => 1,
            'countrycodes' => 'ye',   // مهم لتسريع النتائج في اليمن
            'accept-language' => 'ar' // <-- هذا السطر يجبر النتائج على العربية
        ]);

        $data = $response->json();

        if (!empty($data) && isset($data[0])) {
            return [
                'lat' => $data[0]['lat'],
                'lng' => $data[0]['lon'],
                'address' => $data[0]['display_name']
            ];
        }

    } catch (\Exception $e) {
        \Log::error('LocationIQ error: ' . $e->getMessage());
    }

    return ['lat' => null, 'lng' => null, 'address' => null];
}

    // دالة لجلب الموقع من API عبر AJAX
 public function getLocationApi(Request $request)
{
    $address = $request->query('address');

    if (empty($address)) {
        return response()->json(['error' => 'العنوان مطلوب'], 400);
    }

    $location = $this->getLocationFromAddress($address);

    if ($location['lat'] && $location['lng']) {
        return response()->json([
            'success' => true,
            'lat' => $location['lat'],
            'lng' => $location['lng'],
            'address' => $location['address'],
            'search_query' => $address // لإظهار النص الذي بحثت عنه
        ]);
    }

    return response()->json([
        'success' => false,
        'error' => 'لم يتم العثور على الموقع'
    ], 404);
}

    // ==================== إدارة الجهات ====================
    public function governments(Request $request)
    {
        $query = Government::with('category', 'services');

        // البحث حسب الاسم
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // فلترة حسب التصنيف
        if ($request->has('category_id') && $request->category_id) {
            $query->where('government_category_id', $request->category_id);
        }

        $governments = $query->orderBy('created_at', 'desc')->paginate(15);
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
            'phone' => 'nullable|string|max:20',
            'work_hours' => 'nullable|string|max:50',
            'category_id' => 'required|exists:government_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'services' => 'nullable|array',
            'services.*.id' => 'exists:offer_services,id',
            'services.*.description' => 'nullable|string',
            'services.*.contact_number' => 'nullable|string|max:20',
            'services.*.work_hours' => 'nullable|string|max:50',
            'services.*.price' => 'nullable|string|max:100',
            'location_lat' => 'nullable|string',
            'location_long' => 'nullable|string',
            'formatted_address' => 'nullable|string',
        ]);

        $governmentData = [
            'name' => $request->name,
            'description' => $request->description,
            'contact_number' => $request->phone,
            'work_hours' => $request->work_hours,
            'government_category_id' => $request->category_id,
        ];

        // استخدام الحقول المخفية أولاً
        if ($request->filled('location_lat') && $request->filled('location_long')) {
            $governmentData['location_lat'] = $request->location_lat;
            $governmentData['location_long'] = $request->location_long;
            $governmentData['address'] = $request->formatted_address ?? $request->search_address;
        } elseif ($request->filled('search_address')) {
            $location = $this->getLocationFromAddress($request->search_address);
            $governmentData['location_lat'] = $location['lat'];
            $governmentData['location_long'] = $location['lng'];
            $governmentData['address'] = $location['address'];
        }

        if ($request->hasFile('images')) {
            $path = 'governments/' . time();
            $governmentData['images'] = $this->uploadImages($request->file('images'), $path);
        }

        $government = Government::create($governmentData);

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
        }

        return redirect()->route('admin.governments')->with('success', 'تم إضافة الجهة بنجاح');
    }

    public function updateGovernment(Request $request, $id)
    {
        $government = Government::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'search_address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
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
            'location_lat' => 'nullable|string',
            'location_long' => 'nullable|string',
            'formatted_address' => 'nullable|string',
        ]);

        $governmentData = [
            'name' => $request->name,
            'description' => $request->description,
            'contact_number' => $request->phone,
            'work_hours' => $request->work_hours,
            'government_category_id' => $request->category_id,
        ];

        if ($request->filled('location_lat') && $request->filled('location_long')) {
            $governmentData['location_lat'] = $request->location_lat;
            $governmentData['location_long'] = $request->location_long;
            $governmentData['address'] = $request->formatted_address ?? $request->search_address;
        } elseif ($request->filled('search_address')) {
            $location = $this->getLocationFromAddress($request->search_address);
            $governmentData['location_lat'] = $location['lat'];
            $governmentData['location_long'] = $location['lng'];
            $governmentData['address'] = $location['address'];
        } else {
            $governmentData['location_lat'] = $government->location_lat;
            $governmentData['location_long'] = $government->location_long;
            $governmentData['address'] = $government->address;
        }

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
    public function services(Request $request)
    {
        $query = OfferService::with('category');

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('government_category_id', $request->category_id);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(15);
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
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role) {
            if ($request->role === 'admin') {
                $query->whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                });
            } elseif ($request->role === 'user') {
                $query->whereDoesntHave('roles', function($q) {
                    $q->where('name', 'admin');
                });
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

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

    // ==================== إدارة التقييمات ====================
    public function reviews(Request $request)
    {
        $query = Review::with(['user', 'government']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('user_name', 'like', "%{$search}%");
                })->orWhereHas('government', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.reviews', compact('reviews'));
    }

    public function destroyReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews')->with('success', 'تم حذف التقييم بنجاح');
    }
}
