@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6 text-right">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('orders.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input value="{{ Request::get('keyWord') }}" type="text" name="keyWord"
                                    class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">Order #</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Order Status</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Date Purchased</th>
                                <th>Payment Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isNotEmpty())
                                @foreach ($orders as $order)
                                    <tr>
                                        <td><a href="{{ route('orders.detail', $order->id) }}">{{ $order->id }}</a></td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->mobile }}</td>
                                        <td>
                                            @if ($order->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($order->status == 'shipped')
                                                <span class="badge bg-info">Shipped</span>
                                            @elseif ($order->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($order->grand_total, 2) }}</td>
                                        <td class="text-center">
                                            @if ($order->payment_method == 'cod')
                                                <span class="badge bg-primary">COD</span>
                                            @else
                                                <span class="badge bg-secondary">STRIPE</span>
                                            @endif
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M,Y') }}</td>
                                        <td>
                                            @if ($order->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif ($order->payment_status == 'unpaid')
                                                <span class="badge bg-danger">Unpaid</span>
                                            @else
                                                <span class="badge bg-primary">Refund</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" onclick="deleteOrder ({{ $order->id }})"
                                                class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target=""
                                                    class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path ath fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Record Not Found.</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJS')
<script>
function deleteOrder(id) {
    var url = '{{ route('orders.deleteOrder', 'ID') }}';
    var newUrl = url.replace("ID", id)

    if (confirm("Are you sure you want to delete?")) {
        $.ajax({
            url: newUrl,
            type: 'delete',
            data: {},
            dataType: 'json',
            success: function(response) {

                if (response["status"] == true) {
                    window.location.href = "{{ route('orders.index') }}";
                } else {
                    window.location.href = "{{ route('orders.index') }}";
                }
            }
        });
    }
}
</script>
@endsection
