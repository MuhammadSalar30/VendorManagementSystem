@extends('layouts.base', ['title' => 'Register'])

@section('content')

<div class="relative md:h-screen sm:py-16 py-36 flex items-center bg-gradient-to-b from-primary/5 via-primary/5 to-primary/10">
    <div class="container">
        <div class="flex justify-center items-center lg:max-w-lg">
            <div class="flex flex-col h-full">
                <div class="shrink">
                    <div class="pb-10">
                        <a class="flex items-center" href="{{ route('second', ['client', 'home']) }}">
                            <img src="/images/assets/logo.png" alt="logo" class="h-10 flex dark:hidden">
                            <img src="/images/assets/logo.png" alt="logo" class="h-12 hidden dark:flex">
                        </a>
                    </div>
                    <div class="">
                        <h1 class="text-3xl font-semibold text-default-800 mb-2">Register</h1>
                    </div>

                    {{-- ✅ Registration Form --}}
                    <div class="pt-16">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Role Dropdown --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-default-900 mb-2" for="role">Register as</label>
                                <select id="role" name="role"
                                    class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200">
                                    <option value="customer">Customer</option>
                                    <option value="vendor">Vendor</option>
                                </select>
                            </div>

                            {{-- Common Fields --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-default-900 mb-2" for="FullName">Full Name</label>
                                <input
                                    class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200"
                                    id="FullName" name="name" placeholder="Enter your Name" type="text" required>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-default-900 mb-2" for="LoggingEmailAddress">Email</label>
                                <input
                                    class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200"
                                    id="LoggingEmailAddress" name="email" placeholder="Enter your email" type="email" required>
                            </div>


                            <div class="mb-6 relative">
                                <label class="block text-sm font-medium text-default-900 mb-2" for="form-password">Password</label>
                                <input
                                    class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200 pr-10"
                                    id="form-password" name="password" placeholder="Enter your password" type="password" required>
                                <button type="button" onclick="togglePassword('form-password', 'togglePasswordIcon')"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                                    <i data-lucide="eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-default-900 mb-2" for="password_confirmation">Confirm Password</label>
                                <input
                                    class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200"
                                    id="password_confirmation" name="password_confirmation" placeholder="Confirm your password"
                                    type="password" required>
                            </div>

                            {{-- Vendor Extra Fields (Hidden by default) --}}
                            <div id="vendorFields" class="hidden">
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-default-900 mb-2" for="vendor_phone">Phone</label>
                                    <input class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200"
                                        id="vendor_phone" name="phone" placeholder="Enter phone number" type="text">
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-default-900 mb-2" for="vendor_city">City</label>
                                    <input class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200"
                                        id="vendor_city" name="city" placeholder="Enter city" type="text">
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-default-900 mb-2" for="vendor_country">Country</label>
                                    <input class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200"
                                        id="vendor_country" name="country" placeholder="Enter country" type="text">
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-default-900 mb-2" for="vendor_address">Address</label>
                                    <input class="block w-full rounded-full py-2.5 px-4 bg-white border border-default-200"
                                        id="vendor_address" name="address" placeholder="Enter address" type="text">
                                </div>
                            </div>

                            <div class="flex justify-center mb-6">
                                <button type="submit"
                                    class="relative inline-flex items-center justify-center px-6 py-3 rounded-full text-base bg-primary text-white capitalize transition-all hover:bg-primary-500 w-full">
                                    Register
                                </button>
                            </div>
                        </form>

                        {{-- Scripts --}}
                        <script>
                            // Toggle Password
                            function togglePassword(inputId, iconId) {
                                const input = document.getElementById(inputId);
                                const icon = document.getElementById(iconId);
                                if (input.type === "password") {
                                    input.type = "text";
                                    icon.setAttribute("data-lucide", "eye-off");
                                } else {
                                    input.type = "password";
                                    icon.setAttribute("data-lucide", "eye");
                                }
                                if (window.lucide) { lucide.createIcons(); }
                            }

                            // Show Vendor Fields
                            document.getElementById('role').addEventListener('change', function () {
                                const vendorFields = document.getElementById('vendorFields');
                                if (this.value === 'vendor') {
                                    vendorFields.classList.remove('hidden');
                                } else {
                                    vendorFields.classList.add('hidden');
                                }
                            });
                        </script>

                        <div class="mb-6">
                            <div class="flex items-center justify-center gap-4">
                                <a href="javascript:void(0)">
                                    <img class="h-8 w-8" src="/images/icons/google.svg">
                                </a>
                                <a href="javascript:void(0)">
                                    <img class="h-8 w-8" src="/images/icons/facebook.svg">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grow flex items-end justify-center mt-16">
                    <p class="text-default-700 text-center mt-auto">
                        Already have an account ?
                        <a class="text-primary ms-1" href="{{ route('second', ['auth', 'login']) }}"><b>Login</b></a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="absolute top-1/2 -translate-y-1/3 start-0 end-0 w-full -z-10">
            <img class="w-full opacity-50 flex" src="/images/other/wawe.png">
        </div>
        <div class="absolute top-0 end-0 hidden xl:flex h-5/6">
            <img class="w-full z-0" src="/images/other/auth-bg.png">
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
