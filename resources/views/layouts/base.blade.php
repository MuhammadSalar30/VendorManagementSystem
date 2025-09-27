@include('layouts.shared/main')

<head>
    {{-- @include('layouts.shared/title-meta', ['title' => $title]) --}}
    @include('layouts.shared/title-meta', ['title' => $title ?? ($__env->yieldContent('title') ?? 'Default Title')])

    @yield('css')

    @include('layouts.shared/head-css')
</head>

<body>

    @yield('content')

    @include('layouts.shared/back-to-top')

    @include('layouts.shared/footer-scripts')

</body>

</html>
