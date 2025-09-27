@extends('layouts.admin', ['subtitle' => "Order", 'title' => "Order Details"])

@section('content')

<div class="rounded-lg border border-default-200">
    <div class="p-6 flex flex-wrap items-center gap-3 border-b border-default-200">
        <h4 class="text-xl font-medium text-default-900">Order #202347</h4>
        <div class="flex flex-wrap items-center gap-3">
            <i data-lucide="dot"></i>
            <h4 class="text-sm text-default-600">September 23, 2023</h4>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <i data-lucide="dot"></i>
            <h4 class="text-sm text-default-600">3 Products</h4>
        </div>

        <a href="{{ route('second', ['orders', 'list']) }}" class="ms-auto text-base font-medium text-primary">Back to List</a>
    </div>

    <div class="p-6">
        <div class="grid xl:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6">
            <div class="rounded-lg border border-default-200">
                <div class="p-4 border-b border-default-200">
                    <h4 class="text-sm font-medium text-default-800">Billing Address</h4>
                </div>

                <div class="p-4">
                    <h4 class="text-base font-medium text-default-800 mb-1">Jaylon Calzoni</h4>
                    <p class="text-sm text-default-600 mb-4">2123 Parker st. Allentown, New Mexico 123456</p>

                    <h4 class="text-base font-medium text-default-800 mb-1">Email</h4>
                    <p class="text-sm text-default-600 mb-4">jaylon.calzoni@mail.com</p>

                    <h4 class="text-base font-medium text-default-800 mb-1">Phone</h4>
                    <p class="text-sm text-default-600 mb-4">(123) 456-7890</p>
                </div>
            </div>

            <div class="rounded-lg border border-default-200">
                <div class="p-4 border-b border-default-200">
                    <h4 class="text-sm font-medium text-default-800">Shipping Address</h4>
                </div>

                <div class="p-4">
                    <h4 class="text-base font-medium text-default-800 mb-1">Ryan Westervelt</h4>
                    <p class="text-sm text-default-600 mb-4">2123 Parker st. Allentown, New Mexico 123456</p>

                    <h4 class="text-base font-medium text-default-800 mb-1">Email</h4>
                    <p class="text-sm text-default-600 mb-4">ryanwestenvelt@mail.com</p>

                    <h4 class="text-base font-medium text-default-800 mb-1">Phone</h4>
                    <p class="text-sm text-default-600 mb-4">(123) 456-7890</p>
                </div>
            </div>

            <div>
                <div class="rounded-lg border border-default-200">
                    <div class="p-4 border-b border-default-200">
                        <h4 class="text-sm font-medium text-default-800">Total Payment :</h4>
                    </div>

                    <div class="px-4">
                        <div class="py-4 flex justify-between border-b border-default-200">
                            <h4 class="text-sm text-default-700">Subtotal :</h4>
                            <h4 class="text-sm font-medium text-default-800">$365.00</h4>
                        </div>
                        <div class="py-4 flex justify-between border-b border-default-200">
                            <h4 class="text-sm text-default-700">Discount :</h4>
                            <h4 class="text-sm font-medium text-default-800">20%</h4>
                        </div>
                        <div class="py-4 flex justify-between border-b border-default-200">
                            <h4 class="text-sm text-default-700">Shipping :</h4>
                            <h4 class="text-sm font-medium text-default-800">Free</h4>
                        </div>
                        <div class="py-4 flex justify-between">
                            <h4 class="text-lg text-default-700">Total :</h4>
                            <h4 class="text-lg font-medium text-default-800">$84.00</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="rounded-lg border border-default-200">
                    <div class="p-4 border-b border-default-200">
                        <h4 class="text-sm font-medium text-default-800">Logistics Details</h4>
                    </div>

                    <div class="p-6 text-center">
                        <img src="/images/icons/truck.png" class="flex mx-auto mb-3">

                        <h4 class="text-base font-medium text-default-800 mb-2">Jay Logistics</h4>
                        <p class="text-base font-medium text-default-700 mb-2">ID: JLST2023477890</p>
                        <p class="text-base text-default-700">Payment Mode: Prepaid (Debit Card)</p>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-3 md:col-span-2">
                <div class="relative my-10">
                    <div class="md:flex hidden mx-20 -mb-6">
                        <div class="flex w-full h-1.5 bg-default-200 rounded-full overflow-hidden">
                            <div class="w-1/2 flex flex-col justify-center overflow-hidden bg-primary rounded-full"></div>
                        </div>
                    </div>

                    <div class="absolute inset-y-0 start-1/2 -translate-x-1/2 md:hidden flex">
                        <div class="absolute inset-y-0 start-1/2 -translate-x-1/2 flex h-full w-1.5 bg-default-200 rounded-full overflow-hidden">
                            <div class="absolute top-0 bottom-1/2 start-1/2 -translate-x-1/2 w-1.5 flex flex-col justify-center overflow-hidden bg-primary rounded-full"></div>
                        </div>
                    </div>

                    <div class="flex md:flex-row flex-col justify-between items-center gap-8 relative z-10 mx-10">
                        <div class="flex flex-col justify-center items-center">
                            <div class="bg-primary rounded-full text-white w-10 h-10 flex justify-center items-center">
                                <i data-lucide="check"></i>
                            </div>
                            <h4 class="text-sm text-default-800 mt-3 p-2 md:bg-transparent bg-default-100 md:shadow-none shadow rounded-lg">Order received</h4>
                        </div>

                        <div class="flex flex-col justify-center items-center">
                            <div class="bg-primary rounded-full w-10 h-10 flex justify-center items-center">
                                <span class="text-sm font-medium text-white">02</span>
                            </div>
                            <h4 class="text-sm text-default-800 mt-3 p-2 md:bg-transparent bg-default-100 md:shadow-none shadow rounded-lg">Processing</h4>
                        </div>

                        <div class="flex flex-col justify-center items-center">
                            <div class="backdrop-blur-sm border border-dashed border-primary rounded-full w-10 h-10 flex justify-center items-center">
                                <span class="text-sm font-medium text-primary">03</span>
                            </div>
                            <h4 class="text-sm text-default-800 mt-3 p-2 md:bg-transparent bg-default-100 md:shadow-none shadow rounded-lg">On the way</h4>
                        </div>

                        <div class="flex flex-col justify-center items-center">
                            <div class="backdrop-blur-sm border border-dashed border-primary rounded-full w-10 h-10 flex justify-center items-center">
                                <span class="text-sm font-medium text-primary">04</span>
                            </div>
                            <h4 class="text-sm text-default-800 mt-3 p-2 md:bg-transparent bg-default-100 md:shadow-none shadow rounded-lg">Delivered</h4>
                        </div>
                    </div>
                </div>

                <div class="border border-default-200 rounded-lg overflow-hidden">
                    <div class="relative overflow-x-auto">
                        <div class="min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-default-200">
                                    <thead class="bg-default-400/10">
                                        <tr>
                                            <th scope="col" class="min-w-[14rem] px-5 py-3 text-start text-xs font-medium text-default-500 whitespace-nowrap uppercase">Products</th>
                                            <th scope="col" class="px-5 py-3 text-start text-xs font-medium text-default-500 whitespace-nowrap uppercase">Price</th>
                                            <th scope="col" class="px-5 py-3 text-start text-xs font-medium text-default-500 whitespace-nowrap uppercase">Quantity</th>
                                            <th scope="col" class="px-5 py-3 text-center text-xs font-medium text-default-500 whitespace-nowrap uppercase">Sub-Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-default-200">
                                        <tr>
                                            <td class="px-5 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <img src="/images/dishes/onion-rings.png" class="h-18 w-18">
                                                    <h4 class="text-sm font-medium text-default-800">Red Capsicum</h4>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-default-800">$20</td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-default-800">x5</td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-center text-default-800">$100</td>
                                        </tr>

                                        <tr>
                                            <td class="px-5 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <img src="/images/dishes/burrito-bowl.png" class="h-18 w-18">
                                                    <h4 class="text-sm font-medium text-default-800">Green Capsicum</h4>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-default-800">$20.50</td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-default-800">x4</td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-center text-default-800">$82</td>
                                        </tr>

                                        <tr>
                                            <td class="px-5 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <img src="/images/dishes/garlic-herb-bread.png" class="h-18 w-18">
                                                    <h4 class="text-sm font-medium text-default-800">Green Chili</h4>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-default-800">$25.00</td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-default-800">x 2</td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-center text-default-800">$50</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 md:col-span-2">
                <div class="rounded-lg border border-default-200">
                    <div class="p-4 border-b border-default-200">
                        <h4 class="text-sm font-medium text-default-800">Logistics Details</h4>
                    </div>

                    <div class="px-4">
                        <div class="py-4 flex justify-between border-b border-default-200">
                            <h4 class="text-sm text-default-700">Transaction ID :</h4>
                            <h4 class="text-sm font-medium text-default-800">#20234567213</h4>
                        </div>
                        <div class="py-4 flex justify-between border-b border-default-200">
                            <h4 class="text-sm text-default-700">Payment Method :</h4>
                            <h4 class="text-sm font-medium text-default-800">#20234567213</h4>
                        </div>
                        <div class="py-4 flex justify-between border-b border-default-200">
                            <h4 class="text-sm text-default-700">Card Holder Name :</h4>
                            <h4 class="text-sm font-medium text-default-800">Jaylon Calzoni</h4>
                        </div>
                        <div class="py-4 flex justify-between">
                            <h4 class="text-sm text-default-700">Card Number :</h4>
                            <h4 class="text-sm font-medium text-default-800">1234 4354 4564</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection