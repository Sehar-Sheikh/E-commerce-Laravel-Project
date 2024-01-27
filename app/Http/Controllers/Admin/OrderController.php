<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;

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

    public function detail($orderId)
    {
        $order = Order::select('orders.*', 'countries.name as countryName')
            ->where('orders.id', $orderId)
            ->leftJoin('countries', 'countries.id', 'orders.country_id')
            ->first();

            $orderItems= OrderItem::where('order_id', $orderId)->get();

        $data['order'] = $order;
        $data['orderItems'] = $orderItems;

        return view('admin.orders.detail', $data);
    }
}
