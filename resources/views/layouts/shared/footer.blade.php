<footer class="border-t border-default-200 bg-gray-50">
    <div class="container">
        <div class="lg:py-12 py-8">
            <!-- Main Footer Content -->
            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 mb-8">
                <!-- Company Info -->
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/images/assets/logo.png" alt="Ghousia Foods Logo" class="h-12 w-auto" />
                        <span class="text-2xl font-bold text-primary-600">Ghousia Foods</span>
                    </div>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        At Ghousia Foods, we bring you authentic flavors with fresh ingredients,
                        cooked to perfection every time. Taste the tradition, love the experience!
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="tel:0335-3442171" class="flex items-center gap-2 text-gray-600 hover:text-primary transition-colors">
                            <i data-lucide="phone" class="h-5 w-5"></i>
                            <span>0335-3442171</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-4 mt-3">
                        <a href="mailto:contact@ghousiafoods.com" class="flex items-center gap-2 text-gray-600 hover:text-primary transition-colors">
                            <i data-lucide="mail" class="h-5 w-5"></i>
                            <span>contact@ghousiafoods.com</span>
                        </a>
                    </div>
                </div>
                <!-- Quick Links -->
                <div class="flex flex-col gap-4">
                    <h5 class="text-lg font-semibold text-gray-900 mb-2">Quick Links</h5>
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition-colors">Home</a>
                    <a href="{{ route('second', ['client', 'product-grid']) }}" class="text-gray-600 hover:text-primary transition-colors">Menu</a>
                    <a href="{{ route('second', ['client', 'product-list']) }}" class="text-gray-600 hover:text-primary transition-colors">Products</a>
                    <a href="{{ route('second', ['client', 'cart']) }}" class="text-gray-600 hover:text-primary transition-colors">Cart</a>
                    <a href="{{ route('second', ['client', 'wishlist']) }}" class="text-gray-600 hover:text-primary transition-colors">Wishlist</a>
                </div>

                <!-- Company -->
                <div class="flex flex-col gap-4">
                    <h5 class="text-lg font-semibold text-gray-900 mb-2">Company</h5>
                    <a href="javascript:void(0)" class="text-gray-600 hover:text-primary transition-colors">About Us</a>
                    <a href="javascript:void(0)" class="text-gray-600 hover:text-primary transition-colors">Our Team</a>
                    <a href="javascript:void(0)" class="text-gray-600 hover:text-primary transition-colors">Careers</a>
                    <a href="javascript:void(0)" class="text-gray-600 hover:text-primary transition-colors">Partner with Us</a>
                </div>

                <!-- Support & Social -->
                <div class="flex flex-col gap-4">
                    <h5 class="text-lg font-semibold text-gray-900 mb-2">Support</h5>
                    <a href="{{ route('second', ['client', 'faqs']) }}" class="text-gray-600 hover:text-primary transition-colors">FAQs</a>
                    <a href="{{ route('second', ['client', 'contact-us']) }}" class="text-gray-600 hover:text-primary transition-colors">Contact Us</a>
                    <a href="javascript:void(0)" class="text-gray-600 hover:text-primary transition-colors">Feedback</a>

                    <!-- Social Media Links -->
                    <div class="mt-4">
                        <h6 class="text-sm font-semibold text-gray-900 mb-3">Follow Us</h6>
                        <div class="flex items-center gap-4">
                            <a href="javascript:void(0)" class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-all text-gray-600 hover:text-primary">
                                <i data-lucide="facebook" class="h-5 w-5"></i>
                            </a>
                            <a href="javascript:void(0)" class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-all text-gray-600 hover:text-primary">
                                <i data-lucide="instagram" class="h-5 w-5"></i>
                            </a>
                            <a href="javascript:void(0)" class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-all text-gray-600 hover:text-primary">
                                <i data-lucide="twitter" class="h-5 w-5"></i>
                            </a>
                            <a href="javascript:void(0)" class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-all text-gray-600 hover:text-primary">
                                <i data-lucide="youtube" class="h-5 w-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-span-1">
                <div class="flex flex-col gap-3">
                    <div class="bg-primary/10 rounded-lg">
                        <div class="p-8">
                            <form class="space-y-2 mb-6">
                                <label for="subscribeEmail" class="text-lg font-medium text-default-950 mb-4">Subscribe</label>
                                <div class="flex rounded-md shadow-sm">
                                    <input type="email" id="subscribeEmail" name="subscribeEmail" class="py-3 px-4 block w-full bg-white border-default-200 rounded-s-md text-sm dark:bg-default-50" placeholder="Email address" />
                                    <button type="button" class="inline-flex flex-shrink-0 justify-center items-center h-[2.875rem] w-[2.875rem] rounded-e-md border border-transparent font-semibold bg-primary text-white hover:bg-primary-500 transition-all text-sm">
                                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                                    </button>
                                </div>
                            </form>
                            <p class="text-sm text-default-500 mb-6">A Res is a self-service shop offering a wide variety of food, beverages & household products we’re engage with their clients & their team.</p>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        <!-- Footer Bottom -->
        <div class="py-6 border-t border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-center md:text-left">
                    <p class="text-sm text-gray-600">
                        © {{ date('Y') }} <span class="font-semibold text-primary-600">ZofaTech LLC</span>. All rights reserved.
                    </p>
                    {{-- <p class="text-xs text-gray-500 mt-1">
                        Made with ❤️ for food lovers everywhere
                    </p> --}}
                </div>

                <div class="flex items-center gap-6">
                    <a href="javascript:void(0)" class="text-gray-500 hover:text-primary transition-colors text-sm">
                        Terms of Service
                    </a>
                    <a href="javascript:void(0)" class="text-gray-500 hover:text-primary transition-colors text-sm">
                        Privacy Policy
                    </a>
                    <a href="javascript:void(0)" class="text-gray-500 hover:text-primary transition-colors text-sm">
                        Cookie Policy
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

@include('layouts.shared/back-to-top')
