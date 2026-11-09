<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\MapMarker;
use App\Models\Order;
use App\Models\UmkmMerchant;
use App\Services\OrderProcessingService;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    protected OrderProcessingService $orderService;
    protected ReservationService $reservationService;

    public function __construct(
        OrderProcessingService $orderService,
        ReservationService $reservationService
    ) {
        $this->orderService = $orderService;
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $destination = Destination::with(['attractions', 'facilities'])->first();
        $featuredProducts = Product::with(['merchant', 'category'])
            ->where('is_active', true)
            ->whereHas('merchant', fn($q) => $q->where('verification_status', 'VERIFIED'))
            ->take(6)
            ->get();
        $markersCount = MapMarker::where('is_active', true)->count();
        $umkmCount = UmkmMerchant::where('verification_status', 'VERIFIED')->count();

        return view('public.index', compact('destination', 'featuredProducts', 'markersCount', 'umkmCount'));
    }

    public function about()
    {
        $destination = Destination::with(['attractions', 'facilities', 'galleries'])->first();
        return view('public.about', compact('destination'));
    }

    public function map(Request $request)
    {
        $category = $request->query('category', 'ALL');
        $markersQuery = MapMarker::where('is_active', true);
        if ($category && $category !== 'ALL') {
            $markersQuery->where('category', strtoupper($category));
        }
        $markers = $markersQuery->get();

        return view('public.map', compact('markers', 'category'));
    }

    public function catalog(Request $request)
    {
        $categoryId = $request->query('category');
        $search = $request->query('q');

        $categories = ProductCategory::withCount('products')->get();
        $productsQuery = Product::with(['merchant', 'category'])
            ->where('is_active', true)
            ->whereHas('merchant', fn($q) => $q->where('verification_status', 'VERIFIED'));

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        if ($search) {
            $productsQuery->where('name', 'like', "%{$search}%");
        }

        $products = $productsQuery->latest()->paginate(12);

        return view('public.catalog', compact('products', 'categories', 'categoryId', 'search'));
    }

    public function productDetail($slug)
    {
        $product = Product::with(['merchant', 'category'])->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('public.product_detail', compact('product', 'relatedProducts'));
    }

    public function cart()
    {
        return view('public.cart');
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk menyelesaikan pesanan belanja.');
        }
        return view('public.checkout');
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'umkm_merchant_id' => 'required|exists:umkm_merchants,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:SIMULATION_QRIS,SIMULATION_VA,CASH_ON_LOCATION',
            'delivery_option' => 'required|in:PICKUP,DELIVERY',
            'delivery_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $order = $this->orderService->createOrder($request->all(), Auth::id());
            return redirect()->route('order.success', $order->invoice_no)->with('success', 'Pesanan Anda berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function orderSuccess($invoiceNo)
    {
        $order = Order::with(['items.product', 'payment', 'merchant'])->where('invoice_no', $invoiceNo)->firstOrFail();
        return view('public.order_success', compact('order'));
    }

    public function confirmPaymentSimulation($invoiceNo)
    {
        $order = Order::where('invoice_no', $invoiceNo)->firstOrFail();
        try {
            $this->orderService->confirmSimulationPayment($order->id, Auth::id() ?? 1);
            return back()->with('success', 'Simulasi Pembayaran Berhasil! Status pesanan kini telah berubah menjadi Lunas (PAID).');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reservationForm()
    {
        $destination = Destination::first();
        return view('public.reservation', compact('destination'));
    }

    public function storeReservation(Request $request)
    {
        $request->validate([
            'visit_date' => 'required|date|after_or_equal:today',
            'visitor_count' => 'required|integer|min:1|max:500',
            'package_name' => 'required|string',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk melakukan reservasi tiket wisata.');
        }

        try {
            $reservation = $this->reservationService->createReservation($request->all(), Auth::id());
            return redirect()->route('dashboard')->with('success', "Reservasi berhasil dengan Kode Booking: {$reservation->booking_code}!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
