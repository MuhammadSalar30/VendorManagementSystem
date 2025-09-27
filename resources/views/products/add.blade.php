@extends('layouts.admin', ['subtitle' => "Products", 'title' => "Add Products"])

@php
    $routeProductsStore = route('products.store');
    $routeProductsList = route('second', ['products', 'list']);
@endphp
@section('content')
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

<div class="grid lg:grid-cols-3 gap-6">
    <div class="p-6 rounded-lg border border-default-200">
        <div class="h-96 p-6 flex flex-col items-center justify-center rounded-lg border border-default-200 mb-4 mainImageUpload cursor-pointer text-center">
            <div class="mb-4">
                <i data-lucide="image" class="w-10 h-10 stroke-primary fill-primary/10"></i>
            </div>
            <h5 class="text-base text-primary font-medium mb-2">
                <i data-lucide="upload-cloud" class="inline-flex ms-2"></i>
                Upload Image
            </h5>
            <p class="text-sm text-default-600 mb-2">Upload a cover image for your product.</p>
            <p class="text-sm text-default-600">
                File Format
                <span class="text-default-800">jpeg, png</span>
                Recommened Size
                <span class="text-default-800">600x600 (1:1)</span>
            </p>
        </div>
        <input type="file" id="mainImageInput" class="hidden" accept="image/*" />

        <!-- Additional Images Section -->
        <h4 class="text-base font-medium text-default-800 mb-4">Additional Images</h4>
        <div class="space-y-4">

            <!-- Additional Image 1 -->
            <div class="additional-image-container">
                <label class="block text-sm font-medium text-default-700 mb-2">Additional Image 1</label>
                <div class="h-32 p-4 flex flex-col items-center justify-center rounded-lg border border-default-200">
                    <div class="additionalImage1Upload text-center cursor-pointer">
                        <div class="mb-2">
                            <i data-lucide="image" class="w-6 h-6 stroke-primary fill-primary/10"></i>
                        </div>
                        <h6 class="text-sm text-primary font-medium">
                            <i data-lucide="upload-cloud" class="inline-flex ms-1"></i>
                            Upload
                        </h6>
                    </div>
                    <input type="file" id="additionalImage1Input" class="hidden" accept="image/*" />
                    <input type="hidden" id="additionalImage1Path" value="" />
                </div>
            </div>

            <!-- Additional Image 2 -->
            <div class="additional-image-container">
                <label class="block text-sm font-medium text-default-700 mb-2">Additional Image 2</label>
                <div class="h-32 p-4 flex flex-col items-center justify-center rounded-lg border border-default-200">
                    <div class="additionalImage2Upload text-center cursor-pointer">
                        <div class="mb-2">
                            <i data-lucide="image" class="w-6 h-6 stroke-primary fill-primary/10"></i>
                        </div>
                        <h6 class="text-sm text-primary font-medium">
                            <i data-lucide="upload-cloud" class="inline-flex ms-1"></i>
                            Upload
                        </h6>
                    </div>
                    <input type="file" id="additionalImage2Input" class="hidden" accept="image/*" />
                    <input type="hidden" id="additionalImage2Path" value="" />
                </div>
            </div>

        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="p-6 rounded-lg border border-default-200">
            <div class="grid lg:grid-cols-2 gap-6 mb-6">
                <div class="space-y-6">
                    <div>
                        <input id="productName" class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="text" placeholder="Product Name">
                    </div>

                    <div>
                        <select id="productCategory" class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="text" placeholder="Select Product Category">
                            <option value="" disabled selected>Select Product Category</option>
                            @foreach (AllCategory() as $Cate)
                                <option value="{{ $Cate->id }}">{{ $Cate->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-6">
                        <div>
                            <input id="productPrice" class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="text" placeholder="Selling Price">
                        </div>

                        <div>
                            <input id="productCostPrice" class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="text" placeholder="Cost Price">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="order_type" class="form-label">Order Type</label>
                        <div class="flex gap-2">
                            <button type="button" class="order-type-toggle py-2 px-4 bg-gray-200 text-gray-800 rounded-full hover:bg-primary hover:text-white transition-all" data-value="delivery">Delivery</button>
                            <button type="button" class="order-type-toggle py-2 px-4 bg-gray-200 text-gray-800 rounded-full hover:bg-primary hover:text-white transition-all" data-value="pickup">Pickup</button>
                            <button type="button" class="order-type-toggle py-2 px-4 bg-gray-200 text-gray-800 rounded-full hover:bg-primary hover:text-white transition-all" data-value="dine_in">Dine-in</button>
                        </div>
                        <input type="hidden" id="orderTypeInput" name="order_type" value="[]"> <!-- Hidden input to store selected values -->
                    </div>

                    <div class="form-group">
                        <label for="size_selection" class="form-label">Size Options</label>
                        <div class="space-y-3">
                            <div class="flex gap-2">
                                <button type="button" class="size-toggle py-2 px-4 bg-gray-200 text-gray-800 rounded-full hover:bg-primary hover:text-white transition-all" data-value="full">Full</button>
                                <button type="button" class="size-toggle py-2 px-4 bg-gray-200 text-gray-800 rounded-full hover:bg-primary hover:text-white transition-all" data-value="half">Half</button>
                                <button type="button" class="size-toggle py-2 px-4 bg-gray-200 text-gray-800 rounded-full hover:bg-primary hover:text-white transition-all" data-value="quarter">Quarter</button>
                            </div>
                        </div>
                        <input type="hidden" id="sizeInput" name="size" value="[]">
                        <input type="hidden" id="sizePricesInput" name="size_prices" value="[]">

                        <!-- Size Price Inputs Container -->
                        <div id="sizePriceInputs" class="mt-4 space-y-2 hidden">
                            <h5 class="text-sm font-medium text-default-700 mb-2">Set Prices for Selected Sizes:</h5>
                            <!-- Dynamic price inputs will be added here -->
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <h4 class="text-sm font-medium text-default-600">Discount</h4>
                        <div class="flex items-center gap-4">
                            <label class="block text-sm text-default-600" for="addDiscount">Add Discount</label>
                            <input type="checkbox" id="addDiscount" class="relative w-[3.25rem] h-7 bg-default-200 focus:ring-0 checked:bg-none checked:!bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-transparent before:inline-block before:w-6 before:h-6 before:bg-white before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:transition before:ease-in-out before:duration-200">
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <h4 class="text-sm font-medium text-default-600">Expiry Date</h4>
                        <div class="flex items-center gap-4">
                            <label class="block text-sm text-default-600" for="addExpiryDate">Add Expiry Date</label>
                            <input type="checkbox" id="addExpiryDate" class="relative w-[3.25rem] h-7 bg-default-200 focus:ring-0 checked:bg-none checked:!bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-transparent before:inline-block before:w-6 before:h-6 before:bg-white before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:transition before:ease-in-out before:duration-200">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- <div>
                        <textarea class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" rows="5" placeholder="Short Description"></textarea>
                    </div> --}}

                    <div>
                        <label class="block text-sm font-medium text-default-900 mb-2" for="editor">Product Description</label>
                        <div id="editor" class="h-36 mb-2"></div>
                        <p class="text-sm text-default-600">Add a description for your product</p>
                    </div>

                    <div class="flex justify-between">
                        <h4 class="text-sm font-medium text-default-600">Return Policy</h4>
                        <div class="flex items-center gap-4">
                            <label class="block text-sm text-default-600" for="returnPolicy">Return Policy</label>
                            <input type="checkbox" id="returnPolicy" class="relative w-[3.25rem] h-7 bg-default-200 focus:ring-0 checked:bg-none checked:!bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-transparent before:inline-block before:w-6 before:h-6 before:bg-white before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:transition before:ease-in-out before:duration-200">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-default-500 mb-3">Date Added</p>
                        <div class="grid lg:grid-cols-2 gap-6">
                            <div>
                                <div class="relative">
                                    <span class="absolute top-1/2 start-2.5 -translate-y-1/2"><i data-lucide="calendar-days" class="h-4 w-4 text-default-700"></i></span>
                                    <span class="absolute top-1/2 end-2.5 -translate-y-1/2"><i data-lucide="chevron-down" class="h-4 w-4 text-default-700"></i></span>
                                    <input type="text" class="py-2.5 w-full px-9 block bg-default-100 rounded-md border-0 text-sm text-default-700 font-medium focus:border-default-200 focus:ring-0" id="datepicker-basic">
                                </div><!-- end relative -->
                            </div>

                            <div>
                                <div class="relative">
                                    <span class="absolute top-1/2 start-2.5 -translate-y-1/2"><i data-lucide="calendar-days" class="h-4 w-4 text-default-700"></i></span>
                                    <span class="absolute top-1/2 end-2.5 -translate-y-1/2"><i data-lucide="chevron-down" class="h-4 w-4 text-default-700"></i></span>
                                    <input type="text" class="py-2.5 w-full px-9 block bg-default-100 rounded-md border-0 text-sm text-default-700 font-medium focus:border-default-200 focus:ring-0" id="timepicker">
                                </div><!-- end relative -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div class="flex flex-wrap justify-end items-center gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                    <button type="button" class="hs-dropdown-toggle flex items-center gap-2 font-medium text-default-700 text-sm py-2.5 px-4 rounded-md bg-default-100 transition-all">
                        Save as Draft <i data-lucide="chevron-down" class="h-4 w-4"></i>
                    </button><!-- end dropdown button -->

                    <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 min-w-[200px] transition-[opacity,margin] mt-4 opacity-0 hidden z-20 bg-white shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] rounded-lg border border-default-100 p-1.5 dark:bg-default-50">
                        <ul class="flex flex-col gap-1">
                            <li><a class="flex items-center gap-3 font-normal text-default-600 py-2 px-3 transition-all hover:text-default-700 hover:bg-default-100 rounded" href="javascript:void(0)">Publish</a></li>
                            <li><a class="flex items-center gap-3 font-normal text-default-600 py-2 px-3 transition-all hover:text-default-700 hover:bg-default-100 rounded" href="javascript:void(0)">Save as Darft</a></li>
                            <li><a class="flex items-center gap-3 font-normal text-default-600 py-2 px-3 transition-all hover:text-default-700 hover:bg-default-100 rounded" href="javascript:void(0)">Discard</a></li>
                        </ul><!-- end dropdown items -->
                    </div><!-- end dropdown menu -->
                </div>
                <button class="py-2.5 px-4 inline-flex rounded-lg text-sm font-medium bg-primary text-white transition-all hover:bg-primary-500" id="saveProductBtn">Save</button>
            </div>
        </div>
    <input type="hidden" id="routeProductsStore" value="{{ $routeProductsStore }}" />
    <input type="hidden" id="routeProductsList" value="{{ $routeProductsList }}" />
    <input type="hidden" id="csrfToken" value="{{ csrf_token() }}" />
    </div>

</div>


</form>


@endsection


@section('script')
    @vite(['resources/js/admin-product-add.js'])
@endsection
