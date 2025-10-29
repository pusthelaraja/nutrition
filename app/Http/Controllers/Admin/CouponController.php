<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index(Request $request)
    {
        try {
            $query = Coupon::query();

            // Filter by status
            if ($request->has('status')) {
                $status = $request->get('status');
                if ($status === 'active') {
                    $query->where('is_active', true)
                          ->where(function($q) {
                              $q->whereNull('starts_at')
                                ->orWhere('starts_at', '<=', now());
                          })
                          ->where(function($q) {
                              $q->whereNull('expires_at')
                                ->orWhere('expires_at', '>=', now());
                          });
                } elseif ($status === 'expired') {
                    $query->where('expires_at', '<', now());
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->get('type'));
            }

            $coupons = $query->orderBy('created_at', 'desc')
                           ->paginate(15);

            return view('admin.coupons.index', compact('coupons'));
        } catch (\Exception $e) {
            \Log::error('Coupons listing failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load coupons.');
        }
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        try {
            $products = Product::where('is_active', true)->get();
            $categories = Category::where('is_active', true)->get();
            $users = User::all();

            return view('admin.coupons.create', compact('products', 'categories', 'users'));
        } catch (\Exception $e) {
            \Log::error('Coupon creation form failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load coupon creation form.');
        }
    }

    /**
     * Store a newly created coupon.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|max:50|unique:coupons,code',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'type' => 'required|in:percentage,fixed_amount,free_shipping',
                'value' => 'required|numeric|min:0',
                'minimum_amount' => 'nullable|numeric|min:0',
                'maximum_discount' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:1',
                'usage_limit_per_user' => 'nullable|integer|min:1',
                'starts_at' => 'nullable|date',
                'expires_at' => 'nullable|date|after:starts_at',
                'is_active' => 'boolean',
                'stackable' => 'boolean',
                'applicable_products' => 'nullable|array',
                'applicable_categories' => 'nullable|array',
                'applicable_users' => 'nullable|array'
            ]);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active');
            $data['stackable'] = $request->has('stackable');

            // Convert arrays to JSON
            if ($request->has('applicable_products')) {
                $data['applicable_products'] = json_encode($request->applicable_products);
            }
            if ($request->has('applicable_categories')) {
                $data['applicable_categories'] = json_encode($request->applicable_categories);
            }
            if ($request->has('applicable_users')) {
                $data['applicable_users'] = json_encode($request->applicable_users);
            }

            Coupon::create($data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon created successfully.');

        } catch (\Exception $e) {
            \Log::error('Coupon creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create coupon.');
        }
    }

    /**
     * Display the specified coupon.
     */
    public function show(Coupon $coupon)
    {
        try {
            return view('admin.coupons.show', compact('coupon'));
        } catch (\Exception $e) {
            \Log::error('Coupon details failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load coupon details.');
        }
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        try {
            $products = Product::where('is_active', true)->get();
            $categories = Category::where('is_active', true)->get();
            $users = User::all();

            return view('admin.coupons.edit', compact('coupon', 'products', 'categories', 'users'));
        } catch (\Exception $e) {
            \Log::error('Coupon edit form failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load coupon edit form.');
        }
    }

    /**
     * Update the specified coupon.
     */
    public function update(Request $request, Coupon $coupon)
    {
        try {
            $request->validate([
                'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'type' => 'required|in:percentage,fixed_amount,free_shipping',
                'value' => 'required|numeric|min:0',
                'minimum_amount' => 'nullable|numeric|min:0',
                'maximum_discount' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:1',
                'usage_limit_per_user' => 'nullable|integer|min:1',
                'starts_at' => 'nullable|date',
                'expires_at' => 'nullable|date|after:starts_at',
                'is_active' => 'boolean',
                'stackable' => 'boolean',
                'applicable_products' => 'nullable|array',
                'applicable_categories' => 'nullable|array',
                'applicable_users' => 'nullable|array'
            ]);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active');
            $data['stackable'] = $request->has('stackable');

            // Convert arrays to JSON
            if ($request->has('applicable_products')) {
                $data['applicable_products'] = json_encode($request->applicable_products);
            } else {
                $data['applicable_products'] = null;
            }
            if ($request->has('applicable_categories')) {
                $data['applicable_categories'] = json_encode($request->applicable_categories);
            } else {
                $data['applicable_categories'] = null;
            }
            if ($request->has('applicable_users')) {
                $data['applicable_users'] = json_encode($request->applicable_users);
            } else {
                $data['applicable_users'] = null;
            }

            $coupon->update($data);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Coupon update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update coupon.');
        }
    }

    /**
     * Remove the specified coupon.
     */
    public function destroy(Coupon $coupon)
    {
        try {
            // Check if coupon has been used
            if ($coupon->couponUsages()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete coupon that has been used. Deactivate it instead.');
            }

            $coupon->delete();

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Coupon deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete coupon.');
        }
    }

    /**
     * Toggle coupon status.
     */
    public function toggleStatus(Coupon $coupon)
    {
        try {
            $coupon->update(['is_active' => !$coupon->is_active]);

            $status = $coupon->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Coupon {$status} successfully.");

        } catch (\Exception $e) {
            \Log::error('Coupon status toggle failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to toggle coupon status.');
        }
    }

    /**
     * Get coupon usage statistics.
     */
    public function statistics(Coupon $coupon)
    {
        try {
            $stats = [
                'total_usage' => $coupon->couponUsages()->count(),
                'total_discount' => $coupon->couponUsages()->sum('discount_amount'),
                'unique_users' => $coupon->couponUsages()->select('user_id')->distinct()->count(),
                'usage_by_month' => $coupon->couponUsages()
                    ->selectRaw('DATE_FORMAT(used_at, "%Y-%m") as month, COUNT(*) as count')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Coupon statistics failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }
}
