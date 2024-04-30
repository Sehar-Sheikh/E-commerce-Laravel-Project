<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email');
        $orders = $orders->leftJoin('users', 'users.id', 'orders.user_id');

        if ($request->get('keyWord') != "") {
            $orders = $orders->where('users.name', 'like', '%' . $request->keyWord . '%');
            $orders = $orders->orWhere('users.email', 'like', '%' . $request->keyWord . '%');
            $orders = $orders->orWhere('orders.id', 'like', '%' . $request->keyWord . '%');
        }
        $orders = $orders->paginate(10);
        $data['orders'] = $orders;
        return view('admin.orders.list', $data);
    }

    public function deleteOrder($id, Request $request)
    {
        $order = Order::find($id);

        if (empty($order)) {

            session()->flash('error', 'Record not found.');

            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $order->delete();
        session()->flash('success', 'Order deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Order deleted successfully'
        ]);
    }

    public function detail($orderId)
    {
        $order = Order::select('orders.*', 'countries.name as countryName')
            ->where('orders.id', $orderId)
            ->leftJoin('countries', 'countries.id', 'orders.country_id')
            ->first();

        $orderItems = OrderItem::where('order_id', $orderId)->get();

        $data['order'] = $order;
        $data['orderItems'] = $orderItems;

        return view('admin.orders.detail', $data);
    }

    public function changeOrderStatus(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Order status updated successfully.';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function changePaymentStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->payment_status = $request->paymentStatus;
        $order->save();

        // Update payment_status in the payment_methods table
        $paymentMethod = PaymentMethod::where('order_id', $order->id)->first();

        if ($paymentMethod) {
            $paymentMethod->payment_status = $request->paymentStatus;
            $paymentMethod->save();
        }

        $message = 'Payment status updated successfully.';
        session()->flash('success', $message);

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function sendInvoiceEmail(Request $request, $orderId)
    {
        orderEmail($orderId, $request->userType);

        $message = 'Order email sent successfully.';

        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
