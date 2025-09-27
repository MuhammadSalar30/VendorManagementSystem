@extends('layouts.default', ['title' => 'Home'])

@section('content')
{{-- @dump(auth()->user()->name) --}}

<section class="lg:py-8 py-4 relative overflow-hidden">

{{-- Background wrapper --}}
<div class="absolute inset-0 overflow-hidden">

    {{-- First background (light gradient) --}}
    <div id="first-bg" class="absolute inset-0 bg-gradient-to-l from-orange-600/20 via-orange-600/5 to-orange-600/0 transition-transform duration-1000 translate-x-0"></div>

    {{-- Second background (image 1) --}}
    <div id="second-bg" class="absolute inset-0 transition-transform duration-1000 translate-x-full">
        <img src="/images/assets/backgroundimage1.png" class="h-full w-full object-cover" alt="Background 1">
    </div>

    {{-- Third background (image 2) --}}
    {{-- <div id="third-bg" class="absolute inset-0 transition-transform duration-1000 translate-x-full">
        <img src="/images/assets/offer-bg2.png" class="h-full w-full object-cover" alt="Background 2">
    </div> --}}

    {{-- Dark overlay (only visible when an image is active) --}}
    <div id="dark-overlay" class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-700"></div>
</div>


    {{-- Content --}}
    <div class="container relative">
        <div class="grid lg:grid-cols-2 items-center">
            <div class="py-10 px-10">
                <div class="flex items-center justify-center lg:justify-start order-last lg:order-first z-10">
                    <div id="hero-text" class="text-center lg:text-start text-black transition-colors duration-700">
                        <span class="inline-flex py-2 px-4 text-sm rounded-full bg-primary/20 mb-8 lg:mb-2">#Special Food 🍇</span>
                        <h1 class="lg:text-6xl md:text-5xl text-3xl font-bold capitalize mb-5">
                            We Offer <span class="text-primary">Delicious</span> Food And Quick Service
                        </h1>
                        <p class="text-lg font-medium mb-8 md:max-w-md lg:mx-0 mx-auto">
                            Imagine you don’t need a diet because we provide healthy and delicious food for you!.
                        </p>
                        <div class="flex flex-wrap items-center justify-center lg:justify-normal gap-5 mt-10">
                            <a href="{{ route('second', ['client', 'product-grid']) }}" class="py-3 px-6 sm:py-4 sm:px-8 md:py-5 md:px-10 font-medium text-white bg-primary rounded-full hover:bg-primary-500 transition-all text-sm sm:text-base">Order Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center justify-center py-20">
                <img src="/images/assets/logo.png" class="mx-auto">
            </div>
        </div>
    </div>
</section>

{{-- Script for sliding effect --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let showFirst = true;
        const firstBg = document.getElementById("first-bg");
        const secondBg = document.getElementById("second-bg");
        const overlay = document.getElementById("dark-overlay");
        const heroText = document.getElementById("hero-text");

        setInterval(() => {
            if (showFirst) {
                // Switch to second background (dark)
                firstBg.classList.add("translate-x-[-100%]");
                secondBg.classList.remove("translate-x-full");
                secondBg.classList.add("translate-x-0");

                overlay.classList.remove("opacity-0");
                overlay.classList.add("opacity-100");

                heroText.classList.remove("text-black");
                heroText.classList.add("text-white");
            } else {
                // Switch to first background (light)
                firstBg.classList.remove("translate-x-[-100%]");
                firstBg.classList.add("translate-x-0");
                secondBg.classList.remove("translate-x-0");
                secondBg.classList.add("translate-x-full");

                overlay.classList.remove("opacity-100");
                overlay.classList.add("opacity-0");

                heroText.classList.remove("text-white");
                heroText.classList.add("text-black");
            }
            showFirst = !showFirst;
        }, 5000); // switch every 5s
    });
</script>
<section class="py-10">
    <div class="container">
        <h2 class="text-3xl font-semibold text-default-900 mb-6">Explore Categories</h2>
        <div class="swiper categories-swiper" style="--swiper-navigation-color:#000;">
            <div class="swiper-wrapper">
                @foreach(AllCategory() as $category)
                <div class="swiper-slide text-center category-item" data-category-id="{{ $category->id }}">
                    <img src="{{ $category->image ? asset($category->image) : '/images/default-category.png' }}" alt="{{ $category->name }}" class="h-24 w-24 mx-auto rounded-full object-cover">
                    <h3 class="text-lg font-medium text-default-800 mt-2">{{ $category->name }}</h3>
                </div>
                @endforeach
            </div>

            <!-- Navigation buttons -->
            <div class="swiper-button-next text-black"></div>
            <div class="swiper-button-prev text-black"></div>
        </div>
    </div>
</section>
<section class="py-4 bg-white">
    <div class="container">
<div class="flex justify-between items-center gap-4">
    <!-- Delivery and Pick-up Options -->
    <div class="flex items-center gap-3">
        @php $active = request('order_type'); @endphp
        <button class="order-type-button flex items-center gap-2 cursor-pointer px-4 py-2 rounded-full border transition {{ ($active === 'delivery') ? 'bg-orange-100 border-orange-300 text-orange-700' : 'border-default-200 hover:bg-default-100 text-default-700' }}" data-order-type="delivery">
            <span class="text-sm sm:text-base font-medium">Delivery</span>
        </button>
        <button class="order-type-button flex items-center gap-2 cursor-pointer px-4 py-2 rounded-full border transition {{ ($active === 'pickup') ? 'bg-orange-100 border-orange-300 text-orange-700' : 'border-default-200 hover:bg-default-100 text-default-700' }}" data-order-type="pickup">
            <i data-lucide="walk" class="w-4 h-4"></i>
            <span class="text-sm sm:text-base font-medium">Pick-up</span>
        </button>
    </div>

    <!-- Search Bar -->
    <form class="flex items-center gap-4 w-full max-w-3xl" id="homeSearchForm">
        <input type="text" class="form-input rounded-full px-6 py-4 w-full text-lg" placeholder="Search for items..." id="homeSearchInput" value="{{ request('q') }}">
        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-full px-6 py-4 text-lg transition-colors">Search</button>
    </form>
</div>
</div>
</section>

@include('components.product-grid')

@endsection

@section('script')
@vite(['resources/js/home.js'])
<script>
document.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('homeSearchForm');
  if (!form) return;
  form.addEventListener('submit', function(e){
    e.preventDefault();
    var q = document.getElementById('homeSearchInput')?.value || '';
    var url = new URL(window.location.origin + '{{ route('second', ['client','product-grid']) }}');
    var params = new URLSearchParams(window.location.search);
    if (q && q.trim().length) { params.set('q', q.trim()); } else { params.delete('q'); }
    url.search = params.toString();
    window.location.href = url.toString();
  });
});
</script>
@endsection
