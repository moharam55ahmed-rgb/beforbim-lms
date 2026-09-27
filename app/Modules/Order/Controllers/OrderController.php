<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Display the authenticated user's purchase history.
     */
    public function index(Request $request): View
    {
        $orders = $this->orderService->getUserOrders($request->user());

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a specific order and invoice details.
     */
    public function show(Request $request, string $orderNumber): View
    {
        $order = $this->orderService->getOrderForUser($orderNumber, $request->user());

        return view('orders.show', compact('order'));
    }
}
