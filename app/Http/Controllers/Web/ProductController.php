<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ProductService;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $user = Auth::user();
        $query = Product::with(['category', 'merchant']);

        if ($user->hasRole('pelaku_umkm') && !$user->hasRole('super_admin')) {
            $merchantId = $user->umkm?->id ?? 0;
            $query->where('umkm_merchant_id', $merchantId);
        }

        $products = $query->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = ProductCategory::all();
        $merchant = $user->umkm;

        return view('admin.products.form', compact('categories', 'merchant'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'umkm_merchant_id' => 'required|exists:umkm_merchants,id',
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0', // Boundary Value Analysis protection
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'unit' => 'required|string|max:30',
            'description' => 'nullable|string',
        ]);

        try {
            $product = $this->productService->createProduct($request->all(), Auth::id());
            AuditTrailService::log('PRODUCT_CREATE', 'Product', $product->id, null, ['name' => $product->name, 'price' => $product->price]);
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan ke etalase toko!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
