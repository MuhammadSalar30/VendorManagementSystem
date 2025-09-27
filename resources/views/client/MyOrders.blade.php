{{-- @php dd(isset($orders), $orders ?? 'No orders variable passed'); @endphp --}}

@extends('layouts.default', ['title' => 'My Orders'])

@section('content')

@include('layouts.shared/page-title', ['title' => 'My Orders'] )

<section class="lg:py-10 py-6">
    <div class="container">
        <div class="max-w-5xl mx-auto">
            @if($orders->isEmpty())
                <div class="text-center py-12">
                    <i data-lucide="package" class="w-12 h-12 mx-auto text-default-300 mb-4"></i>
                    <h4 class="text-lg font-semibold text-default-700 mb-2">No Orders Found</h4>
                    <p class="text-default-500 mb-6">You haven’t placed any orders yet.</p>
                    <a href="{{ route('second', ['client', 'product-grid']) }}"
                       class="inline-flex items-center px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                        Browse Menu
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="border border-default-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <!-- Order Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                <div class="text-sm text-default-700">
                                    <span class="font-semibold">Order #{{ $order->generateOrderNumber() }}</span>
                                    {{-- <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->status === 'completed') bg-green-500/10 text-green-600
                                        @elseif($order->status === 'pending') bg-yellow-500/10 text-yellow-600
                                        @elseif($order->status === 'cancelled') bg-red-500/10 text-red-600
                                        @else bg-gray-500/10 text-gray-600 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span> --}}
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_badge }}">
    {{ $order->status_label }}
</span>

                                </div>
                                <div class="text-sm text-default-500">
                                    {{ $order->created_at->format('M d, Y h:i A') }}
                                </div>
                            </div>

                            <!-- Items Preview -->
                            <div class="flex flex-wrap gap-4 mb-4">
                                @foreach($order->orderItems->take(3) as $item)
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $item->menuItem->image ? (str_starts_with($item->menuItem->image, 'http') ? $item->menuItem->image : asset($item->menuItem->image)) : '/images/dishes/burger.png' }}"
                                            class="h-12 w-12 rounded-lg object-cover"
                                            alt="{{ $item->item_name }}"
                                            onerror="this.src='/images/dishes/burger.png'">
                                        <div>
                                            <p class="text-sm font-medium text-default-800">{{ $item->item_name }}</p>
                                            <p class="text-xs text-default-500">{{ $item->quantity }} × {{ $item->formatted_item_price }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                @if($order->orderItems->count() > 3)
                                    <div class="text-sm text-default-500 self-center">
                                        +{{ $order->orderItems->count() - 3 }} more
                                    </div>
                                @endif
                            </div>

                            <!-- Order Footer -->
                            <div class="flex justify-between items-center border-t border-default-200 pt-4">
                                <div>
                                    <span class="text-default-600 text-sm">Total:</span>
                                    <span class="text-lg font-semibold text-default-800">{{ $order->formatted_total }}</span>
                                </div>
                                {{-- <a href="{{ route('orders.show', $order->id) }}"
                                   class="inline-flex items-center px-4 py-2 text-sm border border-default-300 rounded-lg hover:bg-default-50 transition-colors">
                                    View Details
                                </a> --}}
                                    <div class="flex items-center gap-2">
        <a href="{{ route('orders.show', $order->id) }}"
           class="inline-flex items-center px-4 py-2 text-sm border border-default-300 rounded-lg hover:bg-default-50 transition-colors">
            View Details
        </a>

        @if(in_array($order->status, ['pending', 'confirmed']))
            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="cancel-order-form">
    @csrf
    @method('PATCH')
    <button type="submit"
            class="inline-flex items-center px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
        Cancel Order
    </button>
</form>

        @endif
    </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
    $('.cancel-order-form').on('submit', function(e) {
        e.preventDefault(); // stop form submission
        let form = this; // keep reference to current form

        Swal.fire({
            title: 'Are you sure?',
            text: "This order will be cancelled and cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'Close'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // submit the form if confirmed
            }
        });
    });
});
    </script>
</section>

@endsection
