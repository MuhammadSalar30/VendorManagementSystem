@extends('layouts.base', ['title' => 'Forgot Password'])

@section('content')

<div class="relative md:h-screen sm:py-16 py-36 flex items-center bg-gradient-to-b from-primary/5 via-primary/5 to-primary/10">
    <div class="container">
        <div class="flex justify-center items-center lg:max-w-lg">
            <div class="flex flex-col h-full">
                <div class="shrink">
                    <div class="pb-10">
                        <a href="{{ route('second', ['client', 'home']) }}" class="flex items-center">
                             <img src="/images/assets/logo.png" alt="logo" class="h-10 flex dark:hidden">
                            <img src="/images/assets/logo.png" alt="logo" class="h-12 hidden dark:flex">
                            {{-- <img src="/images/logo-dark.png" alt="logo" class="h-12 flex dark:hidden">
                            <img src="/images/logo-light.png" alt="logo" class="h-12 hidden dark:flex"> --}}
                        </a>
                    </div>
                    <div class="">
                        <h1 class="text-3xl font-semibold text-default-800 mb-2">Forgot Password</h1>
                        <p class="text-sm text-default-500 max-w-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod taempor.</p>
                    </div>
                    <div class="pt-16">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-default-900 mb-3" for="LoggingEmailAddress">Email</label>
                            <input id="LoggingEmailAddress" class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="email" placeholder="Enter your email">
                        </div>
                        <div class="flex flex-col justify-center gap-4 mb-6">
                            <a href="{{ route('second', ['auth', 'reset-password']) }}" class="relative inline-flex items-center justify-center px-6 py-3 rounded-full text-base bg-primary text-white capitalize transition-all hover:bg-primary-500 w-full">Reset Password</a>
                            <a href="{{ route('second',['auth', 'login']) }}" class="relative inline-flex items-center justify-center px-6 py-3 rounded-full text-base border border-primary text-primary capitalize transition-all hover:bg-primary hover:text-white w-full">Go to Login</a>
                        </div>
                        <div class="mb-6">
                            <div class="flex items-center justify-center gap-4">
                                <a href="javascript:void(0)" class="">
                                    <img src="/images/icons/google.svg" class="h-8 w-8">
                                </a>
                                <a href="javascript:void(0)" class="">
                                    <img src="/images/icons/facebook.svg" class="h-8 w-8">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grow flex items-end justify-center mt-16">
                    <p class="text-default-500 text-center mt-auto">Back to<a href="{{ route('second',['auth', 'login']) }}" class="text-primary ms-1"><span class="font-medium">Login</span></a></p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="absolute top-1/2 -translate-y-1/3 start-0 end-0 w-full -z-10">
            <img src="/images/other/wawe.png" class="w-full opacity-50 flex">
        </div>

        <div class="absolute top-0 end-0 hidden xl:flex h-5/6">
            <img src="/images/other/auth-bg.png" class="w-full z-0">
        </div>
    </div>
</div>

<div class="fixed lg:bottom-5 end-5 bottom-18 flex flex-col items-center bg-primary/25 rounded-full z-10">
    <button class="rounded-full h-10 w-10 bg-primary text-white flex justify-center items-center z-20">
        <i class="h-5 w-5" data-lucide="sun" id="light-theme"></i>
        <i class="h-5 w-5" data-lucide="moon" id="dark-theme"></i>
    </button>
</div>

@endsection
