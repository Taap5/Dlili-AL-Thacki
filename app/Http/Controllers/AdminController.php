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
            'short_description' => 'nullable|string|max:255', // جديد
            'search_address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255', // جديد
            'whatsapp_number' => 'nullable|string|max:20', // جديد
            'work_hours' => 'nullable|string|max:50',
            'location_description' => 'nullable|string', // جديد
            'facebook_url' => 'nullable|url|max:255', // جديد
            'telegram_url' => 'nullable|url|max:255', // جديد
            'keywords' => 'nullable|string|max:500', // جديد
            'category_id' => 'required|exists:government_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'services' => 'nullable|array',
            'services.*.id' => 'exists:offer_services,id',
            'services.*.description' => 'nullable|string',
            'services.*.contact_number' => 'nullable|string|max:20',
            'services.*.work_hours' => 'nullable|string|max:50',
            'services.*.price' => 'nullable|string|max:100',
            // الحقول الجديدة للخدمات
            'services.*.processing_time' => 'nullable|string|max:100',
            'services.*.office_location' => 'nullable|string|max:255',
            'services.*.required_documents' => 'nullable|string',
            'services.*.steps' => 'nullable|string',
            'services.*.conditions' => 'nullable|string',
            'services.*.notes' => 'nullable|string',
            'services.*.requires_appointment' => 'nullable|boolean',
            'services.*.appointment_phone' => 'nullable|string|max:20',
            'services.*.doctor_specialist' => 'nullable|string|max:255',
            'services.*.hospital_stay_duration' => 'nullable|string|max:100',
            'services.*.emergency_notes' => 'nullable|string',
            'location_lat' => 'nullable|string',
            'location_long' => 'nullable|string',
            'formatted_address' => 'nullable|string',
        ]);

        $governmentData = [
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'contact_number' => $request->phone,
            'email' => $request->email,
            'whatsapp_number' => $request->whatsapp_number,
            'work_hours' => $request->work_hours,
            'work_hours_json' => $request->work_hours_json,
            'location_description' => $request->location_description,
            'facebook_url' => $request->facebook_url,
            'telegram_url' => $request->telegram_url,
            'keywords' => $request->keywords,
            'government_category_id' => $request->category_id,
            'is_active' => true,
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
                        // الحقول الجديدة
                        'processing_time' => $details['processing_time'] ?? null,
                        'office_location' => $details['office_location'] ?? null,
                        'required_documents' => $details['required_documents'] ?? null,
                        'steps' => $details['steps'] ?? null,
                        'conditions' => $details['conditions'] ?? null,
                        'notes' => $details['notes'] ?? null,
                        'requires_appointment' => $details['requires_appointment'] ?? false,
                        'appointment_phone' => $details['appointment_phone'] ?? null,
                        'doctor_specialist' => $details['doctor_specialist'] ?? null,
                        'hospital_stay_duration' => $details['hospital_stay_duration'] ?? null,
                        'emergency_notes' => $details['emergency_notes'] ?? null,
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
            'short_description' => 'nullable|string',
            'search_address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'work_hours' => 'nullable|string|max:50',
            'location_description' => 'nullable|string',
            'facebook_url' => 'nullable|url|max:255',
            'telegram_url' => 'nullable|url|max:255',
            'keywords' => 'nullable|string|max:500',
            'category_id' => 'required|exists:government_categories,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'remove_images' => 'nullable|array',
            'services' => 'nullable|array',
            'services.*.id' => 'exists:offer_services,id',
            'services.*.description' => 'nullable|string',
            'services.*.contact_number' => 'nullable|string|max:20',
            'services.*.work_hours' => 'nullable|string',
            'services.*.price' => 'nullable|string',
            'services.*.processing_time' => 'nullable|string',
            'services.*.office_location' => 'nullable|string',
            'services.*.required_documents' => 'nullable|string',
            'services.*.steps' => 'nullable|string',
            'services.*.conditions' => 'nullable|string',
            'services.*.notes' => 'nullable|string',
            'services.*.requires_appointment' => 'nullable|boolean',
            'services.*.appointment_phone' => 'nullable|string',
            'services.*.doctor_specialist' => 'nullable|string',
            'services.*.hospital_stay_duration' => 'nullable|string',
            'services.*.emergency_notes' => 'nullable|string',
            'location_lat' => 'nullable|string',
            'location_long' => 'nullable|string',
            'formatted_address' => 'nullable|string',
        ]);

        $governmentData = [
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'contact_number' => $request->phone,
            'email' => $request->email,
            'whatsapp_number' => $request->whatsapp_number,
            'work_hours' => $request->work_hours,
            'work_hours_json' => $request->work_hours_json ? json_decode($request->work_hours_json, true) : null,
            'location_description' => $request->location_description,
            'facebook_url' => $request->facebook_url,
            'telegram_url' => $request->telegram_url,
            'keywords' => $request->keywords,
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

        // معالجة الصور (كما هي)
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

        // ✅ معالجة الخدمات المحذوفة أولاً
        if ($request->has('deleted_services')) {
            foreach ($request->deleted_services as $deletedId) {
                $government->services()->detach($deletedId);
            }
        }

        // ✅ ثم معالجة الخدمات الموجودة
        if ($request->has('services')) {
            $servicesData = [];
            foreach ($request->services as $serviceId => $details) {
                if (isset($details['id'])) {
                    $servicesData[$details['id']] = [
                        'description' => $details['description'] ?? null,
                        'contact_number' => $details['contact_number'] ?? null,
                        'work_hours' => $details['work_hours'] ?? null,
                        'price' => $details['price'] ?? null,
                        'processing_time' => $details['processing_time'] ?? null,
                        'office_location' => $details['office_location'] ?? null,
                        'required_documents' => $details['required_documents'] ?? null,
                        'steps' => $details['steps'] ?? null,
                        'conditions' => $details['conditions'] ?? null,
                        'notes' => $details['notes'] ?? null,
                        'requires_appointment' => $details['requires_appointment'] ?? false,
                        'appointment_phone' => $details['appointment_phone'] ?? null,
                        'doctor_specialist' => $details['doctor_specialist'] ?? null,
                        'hospital_stay_duration' => $details['hospital_stay_duration'] ?? null,
                        'emergency_notes' => $details['emergency_notes'] ?? null,
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
            'group_name' => 'nullable|string|max:100',
            'service_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:government_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'icon_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048', // أضف هذا
        ]);

        // دمج الحقلين
        $finalName = trim($request->service_name);
        if ($request->filled('group_name')) {
            $finalName = trim($request->group_name) . ' - ' . trim($request->service_name);
        }

        $serviceData = [
            'name' => $finalName,
            'description' => $request->description,
            'government_category_id' => $request->category_id,
        ];

        // رفع الصور المتعددة
        if ($request->hasFile('images')) {
            $path = 'services/' . time();
            $serviceData['images'] = $this->uploadImages($request->file('images'), $path);
        }

        // رفع أيقونة الخدمة (صورة واحدة)
        if ($request->hasFile('icon_image')) {
            $iconPath = $request->file('icon_image')->store('services/icons', 'public');
            $serviceData['icon_image'] = $iconPath;
        }

        OfferService::create($serviceData);

        return redirect()->route('admin.services')->with('success', 'تم إضافة الخدمة بنجاح');
    }
    public function updateService(Request $request, $id)
    {
        $service = OfferService::findOrFail($id);

        $request->validate([
            'group_name' => 'nullable|string|max:100',
            'service_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:government_categories,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'remove_images' => 'nullable|array',
            'icon_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048', // أضف هذا
            'remove_icon' => 'nullable|boolean', // أضف هذا
        ]);

        // دمج الحقلين
        $finalName = trim($request->service_name);
        if ($request->filled('group_name')) {
            $finalName = trim($request->group_name) . ' - ' . trim($request->service_name);
        }

        $serviceData = [
            'name' => $finalName,
            'description' => $request->description,
            'government_category_id' => $request->category_id,
        ];

        // معالجة الصور المتعددة
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

        // ========== معالجة أيقونة الخدمة ==========

        // حذف الأيقونة إذا طلب المستخدم
        if ($request->has('remove_icon') && $request->remove_icon == 1) {
            if ($service->icon_image) {
                Storage::disk('public')->delete($service->icon_image);
            }
            $serviceData['icon_image'] = null;
        }

        // رفع أيقونة جديدة (تحل محل القديمة)
        if ($request->hasFile('icon_image')) {
            // حذف الأيقونة القديمة إن وجدت
            if ($service->icon_image) {
                Storage::disk('public')->delete($service->icon_image);
            }
            $iconPath = $request->file('icon_image')->store('services/icons', 'public');
            $serviceData['icon_image'] = $iconPath;
        }

        $service->update($serviceData);

        return redirect()->route('admin.services')->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroyService($id)
    {
        $service = OfferService::findOrFail($id);

        // حذف الصور المتعددة
        if ($service->images) {
            foreach ($service->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // حذف أيقونة الخدمة
        if ($service->icon_image) {
            Storage::disk('public')->delete($service->icon_image);
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
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role) {
            if ($request->role === 'admin') {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                });
            } elseif ($request->role === 'user') {
                $query->whereDoesntHave('roles', function ($q) {
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
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('user_name', 'like', "%{$search}%");
                })->orWhereHas('government', function ($q2) use ($search) {
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
