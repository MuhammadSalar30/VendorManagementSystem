<footer class="p-6 border-t border-default-200 w-full absolute lg:ms-64">
    <div class="grid lg:grid-cols-2 items-center gap-6">
        <p class="text-default-600 lg:text-start text-center">
              © {{ date('Y') }} <span class="font-semibold text-primary-600">ZofaTech LLC</span>. All rights reserved.
            {{-- <script>document.write(new Date().getFullYear())</script> Design & Crafted With ❤️  by Zofatech --}}
        </p>

        <div class="lg:flex hidden lg:justify-end text-center justify-center gap-6">
            <a href="javascript:void(0)" class="text-default-500 font-medium">
                Terms
            </a>
            <a href="javascript:void(0)" class="text-default-500 font-medium">
                Privacy
            </a>
            <a href="javascript:void(0)" class="text-default-500 font-medium">
                Cookies
            </a>
        </div>
    </div>
</footer>

@include('layouts.shared/back-to-top')
