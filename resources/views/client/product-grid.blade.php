@extends('layouts.default', ['title' => 'Product Grid'])

@section('content')

@include('layouts.shared/page-title', ['subtitle' => "Product", 'title' => "Grid"])
@php
  $tags = collect($categories ?? [])->shuffle()->take(12);
@endphp
<section class="lg:py-8 py-6">
    <div class="container">
        <div class="lg:flex gap-6">
            <div class="hs-overlay hs-overlay-open:translate-x-0 hidden max-w-xs lg:max-w-full lg:w-1/4 w-full -translate-x-full fixed top-0 start-0 transition-all transform h-full z-60 lg:z-auto bg-white lg:translate-x-0 lg:block lg:static lg:start-auto dark:bg-default-50" id="filter_Offcanvas" tabindex="-1">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200 lg:hidden">
                    <h3 class="font-medium text-default-800">
                        Filter Options
                    </h3>

                    <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-default-500 hover:text-default-700 text-sm" data-hs-overlay="#filter_Offcanvas" type="button">
                        <span class="sr-only">Close modal</span>
                        <i class="h-5 w-5" data-lucide="x"></i>
                    </button>
                </div>

                <div class="h-[calc(100vh-128px)] overflow-y-auto lg:h-auto" data-simplebar>
                    <div class="p-6 lg:p-0 divide-y divide-default-200">
                        <div>
                            <button class="hs-collapse-toggle py-4 inline-flex justify-between items-center gap-2 transition-all uppercase font-medium text-lg text-default-900 w-full open" data-hs-collapse="#all_categories" id="hs-basic-collapse" type="button">
                                Category
                            </button>
                            <div class="hs-collapse w-full overflow-hidden transition-[height] duration-300 open" id="all_categories">
                                <div class="relative flex flex-col space-y-4 mb-6">
                                    <div class="flex items-center">
                                        <input class="form-checkbox rounded-full text-primary border-default-400 bg-transparent w-5 h-5 focus:ring-0 focus:ring-transparent ring-offset-0 cursor-pointer" id="all" name="all" type="checkbox" {{ empty($selected_categories ?? []) ? 'checked' : '' }}>
                                        <label class="ps-3 inline-flex items-center text-default-600 text-sm select-none" for="all">All</label>
                                    </div>

                                    @foreach(($categories ?? []) as $cat)
                                    <div class="flex items-center">
                                        <input class="form-checkbox rounded-full text-primary border-default-400 bg-transparent w-5 h-5 focus:ring-0 focus:ring-transparent ring-offset-0 cursor-pointer" id="cat_{{ $cat->id }}" name="cat_{{ $cat->id }}" type="checkbox" {{ in_array($cat->id, (array)($selected_categories ?? [])) ? 'checked' : '' }}>
                                        <label class="ps-3 inline-flex items-center text-default-600 text-sm select-none" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div><!-- end category -->

                        <div>
                            <button class="hs-collapse-toggle py-4 inline-flex justify-between items-center gap-2 transition-all uppercase font-medium text-lg text-default-900 w-full open" data-hs-collapse="#price_range" id="hs-basic-collapse" type="button">
                                Price Range
                            </button>
                            <div class="hs-collapse w-full overflow-hidden transition-[height] duration-300 open" id="price_range">
                                <div class="relative flex flex-col space-y-5 mb-6">
                                    <div class="space-y-2 py-4">
                                        <div id="product-price-range"></div>

                                        <div class="flex flex-wrap xl:flex-nowrap gap-2 items-center !mt-4">
                                            <div class="inline-flex items-center justify-center whitespace-nowrap w-full xl:w-1/2 gap-1 border border-default-200 py-2 px-4 rounded-full">
                                                Min price (Rs):
                                                <input class="border-none p-0 w-10 bg-transparent focus:ring-0" id="minCost" type="text" value="{{ $min_price ?? 0 }}" />
                                            </div>
                                            <div class="inline-flex items-center justify-center whitespace-nowrap w-full xl:w-1/2 gap-1 border border-default-200 py-2 px-4 rounded-full">
                                                Max price (Rs):
                                                <input class="border-none p-0 w-10 bg-transparent focus:ring-0" id="maxCost" type="text" value="{{ $max_price ?? 5000 }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative flex flex-col space-y-4 mb-6">
                                        <div class="flex items-center">
                                            <input class="form-radio rounded-full text-primary border-default-400 bg-transparent w-5 h-5 focus:ring-0 focus:ring-transparent cursor-pointer" id="all_price" name="radio" type="radio">
                                            <label class="ps-3 inline-flex items-center text-default-600 text-sm select-none" for="all_price">All
                                                Price</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input class="form-radio rounded-full text-primary border-default-400 bg-transparent w-5 h-5 focus:ring-0 focus:ring-transparent cursor-pointer" id="under_500" name="radio" type="radio">
                                            <label class="ps-3 inline-flex items-center text-default-600 text-sm select-none" for="under_500">Under Rs 500</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input class="form-radio rounded-full text-primary border-default-400 bg-transparent w-5 h-5 focus:ring-0 focus:ring-transparent cursor-pointer" id="500_1500" name="radio" type="radio">
                                            <label class="ps-3 inline-flex items-center text-default-600 text-sm select-none" for="500_1500">Rs 500 to 1,500</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input class="form-radio rounded-full text-primary border-default-400 bg-transparent w-5 h-5 focus:ring-0 focus:ring-transparent cursor-pointer" id="1500_3000" name="radio" type="radio">
                                            <label class="ps-3 inline-flex items-center text-default-600 text-sm select-none" for="1500_3000">Rs 1,500 to 3,000</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input checked class="form-radio rounded-full text-primary border-default-400 bg-transparent w-5 h-5 focus:ring-0 focus:ring-transparent cursor-pointer" id="3000_5000" name="radio" type="radio">
                                            <label class="ps-3 inline-flex items-center text-default-600 text-sm select-none" for="3000_5000">Rs 3,000 to 5,000</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input class="form-radio rounded-full text-primary border-default-400 bg-transparent w-5 h-5 focus:ring-0 focus:ring-transparent cursor-pointer" id="5000_plus" name="radio" type="radio">
                                            <label class="ps-3 inline-flex items-center text-default-600 text-sm select-none" for="5000_plus">Rs 5,000+</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="hs-collapse-toggle py-4 inline-flex justify-between items-center gap-2 transition-all uppercase font-medium text-lg text-default-900 w-full open" data-hs-collapse="#popular_tags" id="hs-basic-collapse" type="button">
                                Popular tags
                            </button>
                            <div class="hs-collapse w-full overflow-hidden transition-[height] duration-300 open" id="popular_tags">
                                <div class="relative mb-6">
                                    <!-- <form id="gridSearchForm" class="mb-3 flex w-full" onsubmit="return false;">
                                        <input id="gridSearchInput" type="search" placeholder="Search for items..." value="{{ $q ?? '' }}" class="flex-1 ps-4 pe-4 py-2.5 border border-default-200 rounded-full text-sm focus:ring-0" />
                                        <button type="submit" class="ms-2 px-4 py-2 rounded-full bg-primary text-white text-sm">Search</button>
                                    </form> -->
                                    <div class="flex flex-wrap gap-1.5">
                                        @php $tags = collect($categories ?? [])->shuffle()->take(12); @endphp
                                        @forelse($tags as $tag)
                                            <a href="{{ route('second',["client","product-grid"]) }}?categories={{ $tag->id }}&min_price={{ $min_price ?? 0 }}&max_price={{ $max_price ?? 5000 }}&q={{ urlencode($q ?? '') }}" class="text-default-950 px-3 py-1 rounded-full border border-default-200 cursor-pointer hover:bg-primary/10 hover:text-primary hover:border-primary-500/60 transition-all">
                                                {{ $tag->name }}
                                            </a>
                                        @empty
                                            <div class="text-default-950 px-3 py-1 rounded-full border border-default-200">No Tags</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div><!-- end cafe & restaurant -->

                        <div class="py-6">
                            <div class="relative rounded-lg bg-[url(/images/other/offer-bg.png)] bg-opacity-5 bg-center bg-cover overflow-hidden">
                                <div class="absolute inset-0 bg-primary/10 -z-10"></div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="block lg:hidden py-4 px-4 border-t border-default-200">
                    <a class="w-full inline-flex items-center justify-center rounded border border-primary bg-primary px-6 py-2.5 text-center text-sm font-medium text-white shadow-sm transition-all hover:border-primary-700 hover:bg-primary focus:ring focus:ring-primary/50" href="javascript:void(0)">Reset</a>
                </div>
            </div>

            <div class="lg:w-3/4">
                <div class="flex flex-wrap md:flex-nowrap items-center justify-between gap-4 mb-10">
                    <div class="flex flex-wrap md:flex-nowrap items-center gap-4">
                        <button class="inline-flex lg:hidden items-center gap-4 text-sm py-2.5 px-4 xl:px-5 rounded-full text-default-950 border border-default-200 transition-all" data-hs-overlay="#filter_Offcanvas" type="button">
                            Filter <i class="h-4 w-4" data-lucide="settings-2"></i>
                        </button>

                        <!-- <h6 class="lg:flex hidden text-default-950 text-base">Showing 1–10 of 99
                            results</h6> -->
                        <form id="gridSearchForm" class="flex items-center gap-2 w-full md:w-auto" onsubmit="return false;">
                            <input id="gridSearchInput" type="search" placeholder="Search for items..." value="{{ $q ?? request('q') }}" class="flex-1 md:w-64 ps-4 pe-4 py-2.5 border border-default-200 rounded-full text-sm focus:ring-0" />
                            <button type="submit" class="px-4 py-2 rounded-full bg-primary text-white text-sm">Search</button>
                        </form>
                        <span class="text-base text-default-950 me-3">Sort By :</span>
                        @php $sort = request()->query('sort', 'latest'); @endphp
                        <select id="sortSelect" class="py-2.5 px-4 xl:px-5 rounded-full border border-default-200 text-sm" onchange="applySort()">
                            <option value="rating" {{ $sort==='rating'?'selected':'' }}>Ratings / Reviews</option>
                            <option value="latest" {{ $sort==='latest'?'selected':'' }}>Latest</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Grid/List Toggle -->
                        @php $isGrid = true; @endphp
                        <div class="inline-flex rounded-md overflow-hidden border border-default-200">
                            <a href="{{ route('second', ['client','product-grid']) }}" class="px-3 py-2 {{ $isGrid ? 'bg-primary text-white' : 'text-default-700' }}">
                                <i class="fa-solid fa-grid-2"></i>
                            </a>
                            <a href="{{ route('second', ['client','product-list']) }}" class="px-3 py-2 {{ !$isGrid ? 'bg-primary text-white' : 'text-default-700' }}">
                                <i class="fa-solid fa-list"></i>
                            </a>
                        </div>
                    </div>
                </div><!-- end flex -->

                <div class="grid xl:grid-cols-3 sm:grid-cols-2 gap-5">
@if(isset($items) && count($items))
    @foreach($items as $item)
                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto w-full h-52 rounded-lg overflow-hidden">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-all"
                                     src="{{ $item->image ? (str_starts_with($item->image, 'http') ? $item->image : asset($item->image)) : '/images/dishes/burger.png' }}"
                                     alt="{{ $item->name }}"
                                     onerror="this.src='/images/dishes/burger.png'">
                            </div>
                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1" href="{{ route('third', ['client','product-detail',$item->id]) }}">{{ $item->name }}</a>
                                    <button type="button" class="wishlist-btn h-6 w-6 transition-all relative z-10 {{ (isset($wishlist_ids) && in_array($item->id, (array)$wishlist_ids)) ? 'text-red-500 fill-red-500' : 'text-default-200 hover:text-red-500' }}" aria-label="Wishlist" onclick="toggleWishlist({{ $item->id }}, this)">
                                        <i data-lucide="heart" class="h-6 w-6 {{ (isset($wishlist_ids) && in_array($item->id, (array)$wishlist_ids)) ? 'fill-red-500' : '' }}"></i>
                                    </button>
                                </div>
                                <!-- Sizes (if available) to mirror home grid -->
                                <div class="mb-4 min-h-16">
                                    @php
                                        $sizesAssoc = [];
                                        if (!empty($item->sizes) && is_array($item->sizes)) {
                                            $sizesAssoc = $item->sizes; // name => price
                                        }
                                        $defaultSizeName = null;
                                        if (!empty($sizesAssoc)) {
                                            foreach ($sizesAssoc as $n => $p) { if (strtolower((string)$n) === 'full') { $defaultSizeName = $n; break; } }
                                            if ($defaultSizeName === null) { $defaultSizeName = array_key_first($sizesAssoc); }
                                        }
                                    @endphp
                                    @if (!empty($sizesAssoc))
                                        <label class="text-sm text-default-600 mb-2 block">Select Size:</label>
                                        <div class="flex flex-wrap gap-2 size-buttons">
                                            @foreach ($sizesAssoc as $sizeName => $sizePrice)
                                                @php $isDefault = ($sizeName === $defaultSizeName); @endphp
                                                <button type="button"
                                                    class="size-button px-3 py-1.5 rounded-full border text-sm transition-all {{ $isDefault ? 'bg-primary text-white border-primary' : 'border-default-200 text-default-800 hover:bg-primary hover:text-white' }}"
                                                    data-price="{{ $sizePrice }}"
                                                    onclick="selectSize(this, {{ $item->id }}, {{ $sizePrice }})">
                                                    {{ ucfirst($sizeName) }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div></div>
                                    @endif
                                </div>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 id="price-{{ $item->id }}" class="font-semibold text-xl text-default-900">
                                        @php
                                            $initialPrice = $item->display_price ?? ($item->min_price ?? 0);
                                            if (!empty($sizesAssoc)) {
                                                $initialPrice = $sizesAssoc[$defaultSizeName] ?? $initialPrice;
                                            }
                                        @endphp
                                        Rs {{ number_format((float) $initialPrice, 0) }}
                                    </h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button" onclick="updateQuantity({{ $item->id }}, -1)">–</button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="1" readonly type="text" value="1" id="quantity-{{ $item->id }}">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                    </div>
                                </div>
                                <button onclick="addToCart({{ $item->id }})" class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500">
                                    Add to cart
                                </button>
                            </div>
                        </div>
                    </div>
    @endforeach
@else
                    <div class="col-span-3 text-center text-default-600">No products found.</div>
@endif
                </div>

@if(false)

                <div class="grid xl:grid-cols-3 sm:grid-cols-2 gap-5">
                    <div class="xl:order-1 order-2 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/pizza.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Italian
                                        Pizza</a>
                                    <i class="h-6 w-6 text-red-500 fill-red-500 cursor-pointer" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.5</span>
                                </span>

                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">Rs 8.75</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="5">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="sm:col-span-2 xl:order-2 order-1">
                        <div class="relative rounded-lg overflow-hidden bg-cover bg-[url(/images/other/discount.png)] h-full">
                            <div class="absolute inset-0 bg-black/10"></div>
                            <div class="relative p-8 md:p-12">
                                <h4 class="text-5xl text-yellow-500 font-semibold mb-6">52% Discount</h4>
                                <p class="text-lg text-default-500 mb-6">on your first order</p>
                                <a class="md:mb-10 inline-flex items-center justify-center gap-2 rounded-full border border-primary bg-primary px-4 py-2 text-center text-sm font-medium text-white shadow-sm transition-all duration-200 hover:border-primary-700 hover:bg-primary-500" href="javascript:void(0)">
                                    Shop Now
                                    <i class="h-4 w-4" data-lucide="move-right"></i>
                                </a>
                            </div>
                        </div>
                    </div><!-- end grid-col -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/burger.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Veg Burger</a>
                                    <i class="h-6 w-6 text-default-200 cursor-pointer hover:text-red-500 hover:fill-red-500 transition-all relative z-10" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1">
                                        <i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.2</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$12.78</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="1">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/noodles.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Noodles</a>
                                    <i class="h-6 w-6 text-default-200 cursor-pointer hover:text-red-500 hover:fill-red-500 transition-all relative z-10" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.9</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$12.34</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="2">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">
                                    Add to cart
                                </a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/red-velvet-pastry.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Red
                                        Velvet Pastry</a>
                                    <i class="h-6 w-6 text-red-500 fill-red-500 cursor-pointer" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.0</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$42.25</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="4">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/spaghetti.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Spaghetti</a>
                                    <i class="h-6 w-6 text-default-200 cursor-pointer hover:text-red-500 hover:fill-red-500 transition-all relative z-10" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.9</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$12.42</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="1">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/hot-chocolate.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Hot
                                        Chocolate</a>
                                    <i class="h-6 w-6 text-default-200 cursor-pointer hover:text-red-500 hover:fill-red-500 transition-all relative z-10" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">3.9</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$15.23</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="0">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/steamed-dumpling.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Steamed
                                        Dumpling</a>
                                    <i class="h-6 w-6 text-default-200 cursor-pointer hover:text-red-500 hover:fill-red-500 transition-all relative z-10" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.6</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$52.14</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="1">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/veg-rice.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Indian
                                        Food</a>
                                    <i class="h-6 w-6 text-red-500 fill-red-500 cursor-pointer" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.4</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$25.14</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="2">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/chickpea-hummus.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Chickpea
                                        Hummus</a>
                                    <i class="h-6 w-6 text-default-200 cursor-pointer hover:text-red-500 hover:fill-red-500 transition-all relative z-10" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.8</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$21.41</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="6">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->

                    <div class="order-3 border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary hover:shadow-xl transition-all duration-300">
                        <div class="relative rounded-lg overflow-hidden divide-y divide-default-200 group">
                            <div class="mb-4 mx-auto">
                                <img class="w-full h-full group-hover:scale-105 transition-all" src="/images/dishes/butter-cookies.png">
                            </div>

                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <a class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0" href="{{ route('second', ['client', 'product-detail']) }}">Butter
                                        Cookies</a>
                                    <i class="h-6 w-6 text-red-500 fill-red-500 cursor-pointer" data-lucide="heart"></i>
                                </div>
                                <span class="inline-flex items-center gap-2 mb-4">
                                    <span class="bg-primary rounded-full p-1"><i class="h-3 w-3 text-white fill-white" data-lucide="star"></i></span>
                                    <span class="text-sm text-default-950 from-inherit">4.8</span>
                                </span>
                                <div class="flex items-end justify-between mb-4">
                                    <h4 class="font-semibold text-xl text-default-900">$30.25</h4>
                                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                                        <button class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            –
                                        </button>
                                        <input class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" max="100" min="0" readonly="" type="text" value="2">
                                        <button class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <a class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" href="{{ route('second', ['client', 'cart']) }}">Add
                                    to cart</a><!-- end btn -->
                            </div>
                        </div>
                    </div><!-- end grid-cols -->
                </div><!-- end grid -->
@endif


                <div class="flex flex-wrap justify-center md:flex-nowrap md:justify-end gap-y-6 gap-x-10 pt-6">
                    <nav>
                        <ul class="inline-flex items-center space-x-2 rounded-md text-sm">
                            @php
                              $baseUrl = route('second',["client","product-grid"]);
                              $qs = request()->query();
                            @endphp
                            @for($p = 1; $p <= max(1, min(10, $pages ?? 1)); $p++)
                                @php $qs['page'] = $p; @endphp
                                <li>
                                  <a class="inline-flex items-center justify-center h-9 w-9 {{ ($page ?? 1) == $p ? 'border border-primary text-white bg-primary' : 'bg-default-100 text-default-800 hover:bg-primary hover:border-primary hover:text-white' }} rounded-full transition-all duration-500" href="{{ $baseUrl . '?' . http_build_query($qs) }}">{{ $p }} </a>
                            </li>
                            @endfor
                        </ul><!-- end ul -->
                    </nav><!-- end nav -->

                    <nav>
                        <ul class="inline-flex items-center space-x-2 rounded-md text-sm">
                            <li>
                                <a class="inline-flex items-center justify-center h-9 w-9 bg-default-100 rounded-full transition-all duration-500 text-default-800 hover:bg-primary hover:border-primary hover:text-white" href="javascript:void(0)">
                                    <i class="h-5 w-5" data-lucide="chevron-left"></i>
                                </a>
                            </li>
                            <li>
                                <a class="inline-flex items-center justify-center h-9 w-9 bg-default-100 rounded-full transition-all duration-500 text-default-800 hover:bg-primary hover:border-primary hover:text-white" href="javascript:void(0)">
                                    <i class="h-5 w-5" data-lucide="chevron-right"></i>
                                </a>
                            </li>
                        </ul><!-- end ul -->
                    </nav><!-- end nav -->
                </div><!-- end flex -->
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
  @vite(['resources/js/product-range.js', 'resources/js/product-grid-filters.js'])
  <script>
    // Ensure price update and size selection work on this page (in case global helpers aren't loaded)
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.updatePrice !== 'function') {
            window.updatePrice = function(productId, price) {
                var el = document.getElementById('price-' + productId);
                if (el) {
                    var num = parseInt(price, 10);
                    el.textContent = 'Rs ' + (isNaN(num) ? price : num.toLocaleString());
                }
            };
        }

        if (typeof window.selectSize !== 'function') {
            window.selectSize = function(buttonEl, productId, price) {
                var group = buttonEl.closest('.size-buttons') || buttonEl.parentElement;
                if (group) {
                    group.querySelectorAll('.size-button').forEach(function(b){
                        b.classList.remove('bg-primary','text-white','border-primary');
                        b.classList.add('border-default-200','text-default-800');
                    });
                    buttonEl.classList.add('bg-primary','text-white','border-primary');
                    buttonEl.classList.remove('border-default-200','text-default-800');
                }
                window.updatePrice(productId, price);
            };
        }

        // Safety: delegated handler so clicks always work
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.size-button');
            if (!btn) return;
            var card = btn.closest('.order-3');
            if (!card) return;
            var price = btn.getAttribute('data-price');
            var priceHeading = card.querySelector('h4[id^="price-"]');
            if (priceHeading) {
                var idMatch = priceHeading.id.match(/price-(\d+)/);
                if (idMatch) {
                    window.selectSize(btn, parseInt(idMatch[1], 10), price);
                }
            }
        });

        // Search handler (preserve current filters)
        var gridSearchForm = document.getElementById('gridSearchForm');
        if (gridSearchForm) {
            gridSearchForm.addEventListener('submit', function() {
                var qVal = (document.getElementById('gridSearchInput')?.value || '').trim();
                var url = new URL(window.location.origin + '{{ route('second', ['client','product-grid']) }}');
                var params = new URLSearchParams(window.location.search);
                if (qVal) { params.set('q', qVal); } else { params.delete('q'); }
                url.search = params.toString();
                window.location.href = url.toString();
            });
        }
    });
    // Cart functionality for product grid
    function updateQuantity(itemId, change) {
        const quantityInput = document.getElementById(`quantity-${itemId}`);
        const currentQuantity = parseInt(quantityInput.value);
        const newQuantity = Math.max(1, Math.min(100, currentQuantity + change));
        quantityInput.value = newQuantity;
    }

    function addToCart(itemId) {
        const quantity = Math.max(1, parseInt(document.getElementById(`quantity-${itemId}`).value || '1'));
        // Try to capture selected size from the card
        let size = null;
        const card = document.getElementById(`quantity-${itemId}`)?.closest('.order-3');
        if (card) {
            const activeSizeBtn = card.querySelector('.size-buttons .size-button.bg-primary');
            if (activeSizeBtn) {
                size = (activeSizeBtn.textContent || '').trim();
            }
        }

        console.log('Adding to cart from grid:', { itemId, quantity, size });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            alert('CSRF token not found. Please refresh the page.');
            return;
        }

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                menu_item_id: itemId,
                quantity: quantity,
                size: size
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Instant UI bump then sync with server count
                if (typeof updateCartCount === 'function') { updateCartCount(data.cart_count); }
                if (typeof loadCartCount === 'function') { loadCartCount(); }
                if (typeof showAddToCartToast === 'function') { showAddToCartToast(); }
            } else {
                alert('Error: ' + (data.message || 'Unknown error occurred'));
            }
        })
        .catch(error => {
            console.log(error);

            console.error('Error:', error);
            alert('There was an error adding the item to cart. Please try again. Error: ' + error.message);
        });
    }

    function updateCartCount(count) {
        // Update cart count in navbar if it exists
        const cartCountElement = document.querySelector('.cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = count;
        }
    }
    function toggleWishlist(itemId, el) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showNotification('CSRF token not found. Please refresh the page.', 'error');
            return;
        }

        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ menu_item_id: itemId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Wishlist response:', data);
            if (data.success) {
                const heartIcon = el.querySelector('i[data-lucide="heart"]');

                if (data.wishlisted) {
                    // Added to wishlist
                    el.classList.remove('text-default-200');
                    el.classList.add('text-red-500', 'fill-red-500');
                    heartIcon.classList.add('fill-red-500');
                    showNotification(data.message, 'success');
                } else {
                    // Removed from wishlist
                    el.classList.remove('text-red-500', 'fill-red-500');
                    el.classList.add('text-default-200');
                    heartIcon.classList.remove('fill-red-500');
                    showNotification(data.message, 'info');
                }
            } else {
                showNotification(data.message || 'Failed to update wishlist', 'error');
            }
        })
        .catch(error => {
            console.error('Wishlist error:', error);
            showNotification('Something went wrong. Please try again.', 'error');
        });
    }

    function showNotification(message, type = 'success') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.wishlist-notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `wishlist-notification fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

        // Set colors based on type
        if (type === 'success') {
            notification.className += ' bg-green-500 text-white';
        } else if (type === 'info') {
            notification.className += ' bg-blue-500 text-white';
        } else if (type === 'error') {
            notification.className += ' bg-red-500 text-white';
        }

        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <i data-lucide="${type === 'success' ? 'heart' : type === 'info' ? 'heart-off' : 'alert-circle'}" class="h-4 w-4"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Trigger Lucide icons for the notification
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }
  </script>
 @endsection
