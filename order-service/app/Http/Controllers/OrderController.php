<?php
namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // private $OrderService;
    public function __construct(
        private OrderService $OrderService
    ) {
        // $this->OrderService = $OrderService;
    }
    public function store(Request $request)
    {
        // dd($this->OrderService);
        $validated = $request->validate([
            'user_id'  => ['required', 'integer'],
            'product'  => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'total'    => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $order = $this->OrderService->createOrder($validated);
        } catch (UserNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        }

        return response()->json([
            'message' => 'Order created successfully',
            'order'   => $order,
        ], 201);
    }
}
