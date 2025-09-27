@extends('layouts.default', ['title' => 'Product Details'])

@section('content')

@include('layouts.shared/page-title', ['subtitle' => "Product", 'title' => "Details"])

<section class="lg:py-10 py-6">
    <div class="container">
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="grid grid-cols-1">
                <div>
                    <div class="swiper cart-swiper">
                        <div class="swiper-wrapper">
                            <!-- Main Product Image -->
                            <div class="swiper-slide">
                                <img src="{{ isset($data) && $data ? ($data->image ? asset($data->image) : '/images/dishes/burger.png') : '/images/dishes/burger.png' }}"
                                     class="max-w-full h-full mx-auto"
                                     alt="{{ isset($data) && $data ? $data->name : 'Product' }}"
                                     onerror="this.src='/images/dishes/burger.png'">
                            </div>

                            <!-- Additional Image 1 -->
                            @if(isset($data) && $data && $data->additional_image_1)
                            <div class="swiper-slide">
                                <img src="{{ asset($data->additional_image_1) }}"
                                     class="max-w-full h-full mx-auto"
                                     alt="{{ $data->name }} - Additional Image 1"
                                     onerror="this.src='/images/dishes/burger.png'">
                            </div>
                            @endif

                            <!-- Additional Image 2 -->
                            @if(isset($data) && $data && $data->additional_image_2)
                            <div class="swiper-slide">
                                <img src="{{ asset($data->additional_image_2) }}"
                                     class="max-w-full h-full mx-auto"
                                     alt="{{ $data->name }} - Additional Image 2"
                                     onerror="this.src='/images/dishes/burger.png'">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="swiper cart-swiper-pagination justify-center">
                    <div class="swiper-wrapper justify-center gap-2 w-full">
                        <!-- Main Product Image Thumbnail -->
                        <div class="swiper-slide cursor-pointer !w-24 !h-24 lg:!w-32 lg:!h-32">
                            <img src="{{ isset($data) && $data ? ($data->image ? asset($data->image) : '/images/dishes/burger.png') : '/images/dishes/burger.png' }}"
                                 class="w-full h-full rounded object-cover"
                                 alt="{{ isset($data) && $data ? $data->name : 'Product' }}"
                                 onerror="this.src='/images/dishes/burger.png'">
                        </div>

                        <!-- Additional Image 1 Thumbnail -->
                        @if(isset($data) && $data && $data->additional_image_1)
                        <div class="swiper-slide cursor-pointer !w-24 !h-24 lg:!w-32 lg:!h-32">
                            <img src="{{ asset($data->additional_image_1) }}"
                                 class="w-full h-full rounded object-cover"
                                 alt="{{ $data->name }} - Additional Image 1"
                                 onerror="this.src='/images/dishes/burger.png'">
                        </div>
                        @endif

                        <!-- Additional Image 2 Thumbnail -->
                        @if(isset($data) && $data && $data->additional_image_2)
                        <div class="swiper-slide cursor-pointer !w-24 !h-24 lg:!w-32 lg:!h-32">
                            <img src="{{ asset($data->additional_image_2) }}"
                                 class="w-full h-full rounded object-cover"
                                 alt="{{ $data->name }} - Additional Image 2"
                                 onerror="this.src='/images/dishes/burger.png'">
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-4xl font-medium text-default-800 mb-1">{{ isset($data) && $data ? $data->name : 'Burrito Bowl' }}</h3>
                <h5 class="text-lg font-medium text-default-600 mb-2"><span class="text-base font-normal text-default-500">by</span> Blaze Pizza</h5>

                <div class="flex items-center gap-3 mb-3">
                    <div class="flex gap-1.5">
                        <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star-half-stroke text-base text-yellow-400"></i></span>
                    </div>
                    <div class="h-4 w-px bg-default-400"></div>
                    <h5 class="text-sm text-default-500">54 Reviews</h5>
                </div>

                @if(isset($data) && $data && $data->description)
                <p class="text-sm text-default-500 mb-4">{{ $data->description }}</p>
                @endif

                <div class="flex gap-2 mb-5">
                    <div class="border border-default-200 rounded-full px-3 py-1.5 flex items-center gap-2.5">
                        <img src="/images/icons/non-veg.svg" class="w-4 h-4">
                        <span class="text-xs">Non Vegetable</span>
                    </div>

                    <div class="border border-default-200 rounded-full px-3 py-1.5 flex items-center">
                        <span class="text-xs">Mexican</span>
                    </div>

                    <div class="border border-default-200 rounded-full px-3 py-1.5 flex items-center">
                        <span class="text-xs">Breakfast</span>
                    </div>
                </div>

@php
    $sizes = [];
    if ($data->size && is_array($data->size)) {
        foreach ($data->size as $sizeOption) {
            // Handle both old format ["full"] and new format [{"name": "full", "price": 100}]
            if (is_array($sizeOption) && isset($sizeOption['name'])) {
                // New structured format
                $sizes[$sizeOption['name']] = $sizeOption['price'];
            } elseif (is_string($sizeOption)) {
                // Old simple format - use the item's main price
                $sizes[$sizeOption] = $data->price;
            }
        }
    }
@endphp
@if(!empty($sizes))
                <div class="flex items-center gap-3 mb-4">
                    <h4 class="text-sm text-default-700">Size :</h4>
                    @foreach($sizes as $sizeName => $sizePrice)
                    <div>
                        <input type="radio" name="size_option" id="size-{{ $sizeName }}" value="{{ $sizeName }}" class="peer hidden" data-price="{{ $sizePrice }}" />
                        <label for="size-{{ $sizeName }}" class="w-14 h-9 flex justify-center items-center cursor-pointer select-none rounded-full text-sm text-center bg-default-200 peer-checked:bg-primary peer-checked:text-white">{{ ucfirst($sizeName) }}</label>
                    </div>
                    @endforeach
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold text-2xl text-default-900" id="product-price" data-sizes='@json($sizes)'>
                        @if(!empty($sizes))
                            Select a size to see price
                        @else
                            Rs {{ number_format((float)($data->price ?? $data->min_price ?? 0), 0) }}
                        @endif
                    </h4>
                </div>
@endif

                <div class="flex items-center gap-2 mb-8">
                    <div class="relative z-10 inline-flex justify-between border border-default-200 p-1 rounded-full">
                        <button type="button" class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-9 w-9 text-sm inline-flex items-center justify-center" onclick="updateQuantity(-1)">–</button>
                        <input type="text" id="quantity" class="w-12 border-0 text-sm text-center focus:ring-0 p-0 bg-transparent" value="1" min="1" max="100" readonly="">
                        <button type="button" class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-9 w-9 text-sm inline-flex items-center justify-center" onclick="updateQuantity(1)">+</button>
                    </div>

                    <button onclick="addToCart()" class="inline-flex items-center justify-center rounded-full border border-primary bg-primary px-10 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500">
                        Add to Cart
                    </button>

                    <button type="button" class="wishlist-btn h-8 w-8 transition-all {{ (isset($isWishlisted) && $isWishlisted) ? 'text-red-500 fill-red-500' : 'text-default-400 hover:text-red-600' }}" aria-label="Wishlist" onclick="toggleWishlist({{ $data->id ?? 0 }}, this)">
                        <i data-lucide="heart" class="h-8 w-8 {{ (isset($isWishlisted) && $isWishlisted) ? 'fill-red-500' : '' }}"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <h4 class="text-lg font-medium text-default-700 mb-4">Nutrition Facts <span class="text-sm text-default-400">(per serving)</span></h4>

                    <div class="border border-default-200 p-3 rounded-lg">
                        <div class="grid grid-cols-4 justify-center">
                            <div class="text-center">
                                <h4 class="text-base font-medium text-default-700 mb-1">1524</h4>
                                <h4 class="text-base text-default-700">Calories</h4>
                            </div>
                            <div class="text-center">
                                <h4 class="text-base font-medium text-default-700 mb-1">56g</h4>
                                <h4 class="text-base text-default-700">Fat</h4>
                            </div>
                            <div class="text-center">
                                <h4 class="text-base font-medium text-default-700 mb-1">134g</h4>
                                <h4 class="text-base text-default-700">Carbs</h4>
                            </div>
                            <div class="text-center">
                                <h4 class="text-base font-medium text-default-700 mb-1">78g</h4>
                                <h4 class="text-base text-default-700">Protein</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <i data-lucide="eye" class="w-5 h-5 me-2 text-primary"></i>
                    <h5 class="text-sm text-default-600"><span class="text-primary font-semibold">152</span>&nbsp; People are viewing this right now</h5>
                </div>
            </div>
        </div><!-- end grid -->
    </div><!-- end container -->
</section>

<section class="lg:py-10 py-6">
    <div class="container">
        <h4 class="text-xl font-semibold text-default-800 mb-4">May you also like</h4>
        <div class="grid xl:grid-cols-4 sm:grid-cols-2 gap-5 mb-10">
@foreach(($recommendations ?? []) as $rec)
            <div class="group border border-default-200 rounded-lg p-4 overflow-hidden hover:border-primary transition-all duration-300">
                <div class="relative rounded-lg overflow-hidden divide-y divide-default-200">
                    <div class="w-56 h-52 mb-4 mx-auto">
                        <img src="{{ $rec->image ? asset($rec->image) : '/images/dishes/burger.png' }}" class="w-full h-full group-hover:scale-105 transition-all">
                    </div>
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-4">
                            <a href="{{ route('third', ['client', 'product-detail', $rec->id]) }}" class="text-default-800 text-xl font-semibold line-clamp-1 after:absolute after:inset-0">{{ $rec->name }}</a>
                            <button type="button" class="wishlist-btn h-6 w-6 text-default-200 hover:text-red-500 transition-all relative z-10" aria-label="Wishlist" onclick="toggleWishlist({{ $rec->id }}, this)">
                                <i data-lucide="heart" class="h-6 w-6"></i>
                            </button>
                        </div>
                        <div class="flex items-end justify-between mb-4">
                            <h4 class="font-semibold text-xl text-default-900">Rs {{ number_format((float)$rec->price, 2) }}</h4>
                        </div>
                        <a href="{{ route('second', ['client', 'cart']) }}" class="relative z-10 w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500">Add to cart</a>
                    </div>
                </div>
                    </div>
@endforeach
        </div><!-- end grid -->

        <h4 class="text-xl font-semibold text-default-800 mb-4">Customer Rating</h4>

        <div class="grid lg:grid-cols-4 items-center gap-5">
            <div class="bg-primary/10 py-8 rounded-lg flex flex-col items-center justify-center">
                <h1 class="text-6xl font-semibold text-default-800 mb-4">4.7</h1>

                <div class="flex gap-1.5 mb-2">
                    <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                    <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                    <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                    <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                    <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                </div><!-- end flex -->

                <h4 class="text-base font-medium text-default-700">Customer Rating <span class="font-normal text-default-500">(23,476)</span></h4>
            </div><!-- end card -->

            <div class="xl:col-span-2 md:col-span-3">
                <div class="grid md:grid-cols-12 items-center gap-2 mb-3">
                    <div class="md:col-span-3 flex gap-1.5 lg:justify-center">
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-7">
                        <div class="flex w-full h-1 bg-default-200 rounded-full overflow-hidden">
                            <div class="flex flex-col justify-center overflow-hidden bg-primary w-4/6 rounded" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-2">
                        <h4 class="inline-block text-sm font-medium text-default-700">66%</h4>
                        <span class="font-normal text-default-500">(94,532)</span>
                    </div><!-- end grid-cols -->
                </div><!-- end grid -->

                <div class="grid md:grid-cols-12 items-center gap-2 mb-3">
                    <div class="md:col-span-3 flex gap-1.5 lg:justify-center">
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-7">
                        <div class="flex w-full h-1 bg-default-200 rounded-full overflow-hidden">
                            <div class="flex flex-col justify-center overflow-hidden bg-primary w-1/4 rounded" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-2">
                        <h4 class="inline-block text-sm font-medium text-default-700">25%</h4>
                        <span class="font-normal text-default-500">(6,717)</span>
                    </div><!-- end grid-cols -->
                </div><!-- end grid -->

                <div class="grid md:grid-cols-12 items-center gap-2 mb-3">
                    <div class="md:col-span-3 flex gap-1.5 lg:justify-center">
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-7">
                        <div class="flex w-full h-1 bg-default-200 rounded-full overflow-hidden">
                            <div class="flex flex-col justify-center overflow-hidden bg-primary w-2/12 rounded" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-2">
                        <h4 class="inline-block text-sm font-medium text-default-700">16%</h4>
                        <span class="font-normal text-default-500">(714)</span>
                    </div><!-- end grid-cols -->
                </div><!-- end grid -->

                <div class="grid md:grid-cols-12 items-center gap-2 mb-3">
                    <div class="md:col-span-3 flex gap-1.5 lg:justify-center">
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-7">
                        <div class="flex w-full h-1 bg-default-200 rounded-full overflow-hidden">
                            <div class="flex flex-col justify-center overflow-hidden bg-primary w-1/12 rounded" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-2">
                        <h4 class="inline-block text-sm font-medium text-default-700">8%</h4>
                        <span class="font-normal text-default-500">(643)</span>
                    </div><!-- end grid-cols -->
                </div><!-- end grid -->

                <div class="grid md:grid-cols-12 items-center gap-2">
                    <div class="md:col-span-3 flex gap-1.5 lg:justify-center">
                        <span><i class="fa-solid fa-star text-lg text-yellow-400"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                        <span><i class="fa-solid fa-star text-lg text-default-200"></i></span>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-7">
                        <div class="flex w-full h-1 bg-default-200 rounded-full overflow-hidden">
                            <div class="flex flex-col justify-center overflow-hidden bg-primary w-[4%] rounded" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div><!-- end grid-cols -->
                    <div class="md:col-span-2">
                        <h4 class="inline-block text-sm font-medium text-default-700">4%</h4>
                        <span class="font-normal text-default-500">(152)</span>
                    </div><!-- end grid-cols -->
                </div><!-- end grid -->
            </div>
        </div><!-- end grid -->

        <!-- Add Rating Form -->
        <div class="mt-8 border border-default-200 rounded-lg p-6">
            <h4 class="text-lg font-medium text-default-800 mb-4">Add Your Review</h4>
            <form id="ratingForm" class="space-y-4">
                @csrf
                <input type="hidden" name="menu_item_id" value="{{ $data->id ?? '' }}">

                <div>
                    <label class="block text-sm font-medium text-default-700 mb-2">Rating</label>
                    <div class="flex gap-2">
                        <input type="radio" name="rating" value="1" id="star1" class="hidden">
                        <label for="star1" class="text-2xl cursor-pointer text-default-300 hover:text-yellow-400 transition-colors">★</label>

                        <input type="radio" name="rating" value="2" id="star2" class="hidden">
                        <label for="star2" class="text-2xl cursor-pointer text-default-300 hover:text-yellow-400 transition-colors">★</label>

                        <input type="radio" name="rating" value="3" id="star3" class="hidden">
                        <label for="star3" class="text-2xl cursor-pointer text-default-300 hover:text-yellow-400 transition-colors">★</label>

                        <input type="radio" name="rating" value="4" id="star4" class="hidden">
                        <label for="star4" class="text-2xl cursor-pointer text-default-300 hover:text-yellow-400 transition-colors">★</label>

                        <input type="radio" name="rating" value="5" id="star5" class="hidden">
                        <label for="star5" class="text-2xl cursor-pointer text-default-300 hover:text-yellow-400 transition-colors">★</label>
                    </div>
                </div>

                <div>
                    <label for="comment" class="block text-sm font-medium text-default-700 mb-2">Comment (Optional)</label>
                    <textarea id="comment" name="comment" rows="3" class="w-full px-3 py-2 border border-default-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Share your experience with this product..."></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-primary bg-primary px-6 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>

        <div class="pt-10">
            <h4 class="text-base font-medium text-default-800">Customer Review</h4>

            <div class="border-b border-default-200 py-5">
                <div class="flex items-center mb-3">
                    <img src="/images/avatars/avatar1.png" class="h-12 w-12 rounded-full me-4">
                    <div class="">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="text-sm font-medium text-default-800">Jaylon Botosh</h4>
                            <i class="fa-solid fa-circle text-[5px] text-default-400"></i>
                            <h4 class="text-sm font-medium text-default-400">Just now</h4>
                        </div>
                        <div class="flex gap-1.5">
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-default-200"></i></span>
                            <span><i class="fa-solid fa-star text-base text-default-200"></i></span>
                        </div>
                    </div>
                </div>
                <p class="text-default-600">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
            </div><!-- end card -->

            <div class="border-b border-default-200 py-5">
                <div class="flex items-center mb-3">
                    <img src="/images/avatars/avatar2.png" class="h-12 w-12 rounded-full me-4">
                    <div class="">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="text-sm font-medium text-default-800">Alfonso Korsgaard</h4>
                            <i class="fa-solid fa-circle text-[5px] text-default-400"></i>
                            <h4 class="text-sm font-medium text-default-400">12 hours ago</h4>
                        </div>
                        <div class="flex gap-1.5">
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-default-200"></i></span>
                        </div>
                    </div>
                </div>
                <p class="text-default-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div><!-- end card -->

            <div class="border-b border-default-200 py-5">
                <div class="flex items-center mb-3">
                    <img src="/images/avatars/avatar3.png" class="h-12 w-12 rounded-full me-4">
                    <div class="">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="text-sm font-medium text-default-800">Marcus Baptista</h4>
                            <i class="fa-solid fa-circle text-[5px] text-default-400"></i>
                            <h4 class="text-sm font-medium text-default-400">2 days ago</h4>
                        </div>
                        <div class="flex gap-1.5">
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                        </div>
                    </div>
                </div>
                <p class="text-default-600">ed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate.</p>
            </div><!-- end card -->

            <div class="border-b border-default-200 py-5">
                <div class="flex items-center mb-3">
                    <img src="/images/avatars/avatar4.png" class="h-12 w-12 rounded-full me-4">
                    <div class="">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="text-sm font-medium text-default-800">Jaxson Donin</h4>
                            <i class="fa-solid fa-circle text-[5px] text-default-400"></i>
                            <h4 class="text-sm font-medium text-default-400">5 days ago</h4>
                        </div>
                        <div class="flex gap-1.5">
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-default-200"></i></span>

                        </div>
                    </div>
                </div>
                <p class="text-default-600">Vestibulum tincidunt blandit odio vel finibus.</p>
            </div><!-- end card -->

            <div class="py-5">
                <div class="flex items-center mb-3">
                    <img src="/images/avatars/avatar5.png" class="h-12 w-12 rounded-full me-4">
                    <div class="">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="text-sm font-medium text-default-800">Hanna Aminoff</h4>
                            <i class="fa-solid fa-circle text-[5px] text-default-400"></i>
                            <h4 class="text-sm font-medium text-default-400">7 days ago</h4>
                        </div>
                        <div class="flex gap-1.5">
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-yellow-400"></i></span>
                            <span><i class="fa-solid fa-star text-base text-default-200"></i></span>
                            <span><i class="fa-solid fa-star text-base text-default-200"></i></span>
                            <span><i class="fa-solid fa-star text-base text-default-200"></i></span>
                        </div>
                    </div>
                </div>
                <p class="text-default-600">Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur.</p>
            </div><!-- end card -->
        </div><!-- end py -->
    </div>
</section>

@endsection

@section('script')
  @vite(['resources/js/product-detail.js', 'resources/js/form-input-spin.js'])
  <script>
    // Star rating functionality
    document.addEventListener('DOMContentLoaded', function() {
        const starInputs = document.querySelectorAll('input[name="rating"]');
        const starLabels = document.querySelectorAll('label[for^="star"]');

        // Handle star hover effects
        starLabels.forEach((label, index) => {
            label.addEventListener('mouseenter', () => {
                // Highlight stars up to current hover
                starLabels.forEach((l, i) => {
                    if (i <= index) {
                        l.classList.remove('text-default-300');
                        l.classList.add('text-yellow-400');
                    }
                });
            });

            label.addEventListener('mouseleave', () => {
                // Reset to selected state or default
                const selectedRating = document.querySelector('input[name="rating"]:checked');
                if (selectedRating) {
                    const selectedIndex = parseInt(selectedRating.value) - 1;
                    starLabels.forEach((l, i) => {
                        if (i <= selectedIndex) {
                            l.classList.remove('text-default-300');
                            l.classList.add('text-yellow-400');
                        } else {
                            l.classList.remove('text-yellow-400');
                            l.classList.add('text-default-300');
                        }
                    });
                } else {
                    starLabels.forEach(l => {
                        l.classList.remove('text-yellow-400');
                        l.classList.add('text-default-300');
                    });
                }
            });
        });

        // Handle star selection
        starInputs.forEach((input, index) => {
            input.addEventListener('change', () => {
                starLabels.forEach((label, i) => {
                    if (i <= index) {
                        label.classList.remove('text-default-300');
                        label.classList.add('text-yellow-400');
                    } else {
                        label.classList.remove('text-yellow-400');
                        label.classList.add('text-default-300');
                    }
                });
            });
        });
    });

    // Cart functionality
    function updateQuantity(change) {
        const quantityInput = document.getElementById('quantity');
        const currentQuantity = parseInt(quantityInput.value);
        const newQuantity = Math.max(1, Math.min(100, currentQuantity + change));
        quantityInput.value = newQuantity;
    }

    function addToCart() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const menuItemId = {{ $data->id ?? 'null' }};
        const size = document.querySelector('input[name="size_option"]:checked')?.value || null;

        console.log('Adding to cart:', { quantity, menuItemId, size });

        if (!menuItemId) {
            alert('Product information not available');
            return;
        }

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
                menu_item_id: menuItemId,
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
                // Update cart count everywhere and show bottom toast
                if (typeof updateCartCount === 'function') { updateCartCount(data.cart_count); }
                if (typeof showAddToCartToast === 'function') { showAddToCartToast(); }
            } else {
                alert('Error: ' + (data.message || 'Unknown error occurred'));
            }
        })
        .catch(error => {
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

    // Handle rating form submission
    document.getElementById('ratingForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const rating = formData.get('rating');
        const comment = formData.get('comment');
        const menuItemId = formData.get('menu_item_id');

        if (!rating) {
            alert('Please select a rating');
            return;
        }

        fetch('/ratings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                menu_item_id: menuItemId,
                rating: rating,
                comment: comment
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Thank you for your review!');
                // Reset form
                document.getElementById('ratingForm').reset();
                // Reset star display
                document.querySelectorAll('label[for^="star"]').forEach(label => {
                    label.classList.remove('text-yellow-400');
                    label.classList.add('text-default-300');
                });
                // Optionally reload page to show new rating
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error submitting your review. Please try again.');
        });
    });

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
                    el.classList.remove('text-default-200', 'text-default-400');
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
