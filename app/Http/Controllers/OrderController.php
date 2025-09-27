<?php


namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        $orders = Order::with(['orderItems.menuItem'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('client.MyOrders', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('orderItems.menuItem');

        // You can show a dedicated "order details" view,
        // or reuse your confirmation view if it expects $order.
        return view('client.order-confirmation', compact('order'));
    }
    public function cancel(Order $order)
{
    // Only allow cancelling own orders
    if ($order->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    // Check if cancellable
    if (!in_array($order->status, ['pending', 'confirmed'])) {
        return redirect()->back()->with('error', 'This order can no longer be cancelled.');
    }

    // $order->update(['status' => 'cancelled']);
    $order->update([
    'status' => 'cancelled',
    'cancelled_by' => 'customer'
]);


    return redirect()->route('orders.index')->with('success', 'Order has been cancelled.');
}

}
