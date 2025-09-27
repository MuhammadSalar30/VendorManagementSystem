@extends('layouts.base', ['title' => 'Log In'])

@section('content')

<div class="relative md:h-screen sm:py-16 py-36 flex items-center bg-gradient-to-b from-primary/5 via-primary/5 to-primary/10">
    <div class="container">
        <div class="flex justify-center items-center lg:max-w-lg">
            <div class="flex flex-col h-full">
                <div class="shrink">
                    <div>
                        <a href="{{ route('second', ['client', 'home']) }}" class="flex items-center">
                            <img src="/images/assets/logo.png" alt="logo" class="h-12 flex dark:hidden">
                            <img src="/images/assets/logo.png" alt="logo" class="h-12 hidden dark:flex">
                        </a>
                    </div>
                    <div class="py-10">
                        <h1 class="text-3xl font-semibold text-default-800 mb-2">Login</h1>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <span class="text-red-500">{{ $error }}</span><br>
                            @endforeach
                        @endif

                        {{-- Email --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-default-900 mb-2" for="LoggingEmailAddress">Email</label>
                            <input
                                id="LoggingEmailAddress"
                                name="email"
                                value="{{ old('email') }}"
                                class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200
                                       focus:ring-transparent focus:border-default-200 dark:bg-default-50"
                                type="email"
                                placeholder="Enter your email"
                                required
                                autofocus>
                            <span data-x-field-error="email" class="text-red-500"></span>
                        </div>

                        {{-- Password --}}
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-default-900" for="form-password">Password</label>
                                <a href="{{ route('second', ['auth', 'recoverpw']) }}" class="text-xs text-default-700">Forget Password ?</a>
                            </div>
                            <div class="flex" data-x-password>
                                <input
                                    name="password"
                                    type="password"
                                    id="form-password"
                                    class="form-password block w-full rounded-s-full py-2.5 px-4 bg-white border border-default-200
                                           focus:ring-transparent focus:border-default-200 dark:bg-default-50"
                                    placeholder="Enter your password"
                                    required>
                                <button type="button" id="password-addon"
                                    class="password-toggle inline-flex items-center justify-center py-2.5 px-4 border rounded-e-full bg-white -ms-px
                                           border-default-200 dark:bg-default-50">
                                    <i class="password-eye-on h-5 w-5 text-default-600" data-lucide="eye"></i>
                                    <i class="password-eye-off h-5 w-5 text-default-600" data-lucide="eye-off"></i>
                                </button>
                            </div>
                            <span data-x-field-error="password" class="text-red-500"></span>
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-center mb-6">
                            <button type="submit"
                                class="relative inline-flex items-center justify-center px-6 py-3 rounded-full text-base bg-primary text-white
                                       capitalize transition-all hover:bg-primary-500 w-full">
                                Log In
                            </button>
                        </div>

                        {{-- Social Login --}}
                        <div class="mb-6">
                            <div class="flex items-center justify-center gap-4">
                                <a href="javascript:void(0)">
                                    <img src="/images/icons/google.svg" class="h-8 w-8">
                                </a>
                                <a href="javascript:void(0)">
                                    <img src="/images/icons/facebook.svg" class="h-8 w-8">
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="grow flex items-end justify-center mt-16">
                    <p class="text-default-950 text-center mt-auto">
                        Don’t have an account ?
                        <a href="{{ route('second', ['auth', 'register']) }}" class="text-primary ms-1">
                            <span class="font-medium">Register</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Background --}}
    <div>
        <div class="absolute top-1/2 -translate-y-1/3 start-0 end-0 w-full -z-10">
            <img src="/images/other/wawe.png" class="w-full opacity-50 flex">
        </div>

        <div class="absolute top-0 end-0 hidden xl:flex h-5/6">
            <img src="/images/other/auth-bg.png" class="w-full z-0">
        </div>
    </div>
</div>

{{-- Theme Toggle --}}
<div class="fixed lg:bottom-5 end-5 bottom-18 flex flex-col items-center bg-primary/25 rounded-full z-10">
    <button class="rounded-full h-10 w-10 bg-primary text-white flex justify-center items-center z-20">
        <i class="h-5 w-5" data-lucide="sun" id="light-theme"></i>
        <i class="h-5 w-5" data-lucide="moon" id="dark-theme"></i>
    </button>
</div>

@endsection

@section('script')
  @vite(['resources/js/auth.js'])
@endsection
