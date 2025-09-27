@extends('layouts.default', ['title' => $category->name])

@section('content')
<section class="py-10">
    <div class="container">
        <h2 class="text-3xl font-semibold text-default-900 mb-6">{{ $category->name }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="text-center">
                <div class="text-center cursor-pointer category-item" data-category-id="{{ $category->id }}">
                    <img src="{{ $category->image ? asset($category->image) : '/images/default-category.png' }}" alt="{{ $category->name }}" class="h-24 w-24 mx-auto rounded-full object-cover">
                    <h3 class="text-lg font-medium text-default-800 mt-2">{{ $category->name }}</h3>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
