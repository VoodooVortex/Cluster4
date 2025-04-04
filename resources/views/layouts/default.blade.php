<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Map</title>
    <link rel="icon" type="image/png" href="/public/assets/image/logo-mymap.png">

    <!-- Icon FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <!-- Tailwindcss -->
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Mapbox --}}
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.js'></script>

    {{-- Dependencies AutoFill --}}
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript"
        src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
    <script type="text/javascript"
        src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>

    {{-- jquery.Thailand.js --}}
    <link rel="stylesheet"
        href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <script type="text/javascript"
        src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>


    {{-- Import CSS form --}}
    <link rel="stylesheet" href="/resources/css/style.css">

    @yield('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full bg-white shadow-md z-10 flex justify-between items-center px-4 py-2">
        <!-- Hamburger Menu Button -->
        <button id="menuToggle" class="text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Profile Icon -->
        <div class="rounded-full border p-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A9 9 0 1118.879 17.804M12 12a4 4 0 100-8 4 4 0 000 8z" />
            </svg>
        </div>
    </nav>

    <!-- Sidebar -->
    @include('components.sitebar')

    <!-- Main Content Wrapper -->
    <div class="min-h-screen overflow-y-auto">
        @yield('content')
    </div>

    @yield('scripts')
    @livewireScripts

    <!-- Script เปิด/ปิดเมนู -->
    <script>
        $(document).ready(function() {
            $("#menuToggle").click(function() {
                $("#sidebar").removeClass("-translate-x-full");
                $("#overlay").removeClass("hidden");
            });

            $("#closeMenu, #overlay").click(function() {
                $("#sidebar").addClass("-translate-x-full");
                $("#overlay").addClass("hidden");
            });
        });
    </script>
</body>

</html>
