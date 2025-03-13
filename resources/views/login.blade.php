@extends('layouts.default')
@section('content')

    <body class="sm:flex items-center justify-center sm:min-h-screen bg-gray-100 sm:px-4">
        <div class="w-full md:h-[586px] h-screen sm:max-w-sm bg-white p-6 sm:p-8 sm:rounded-2xl shadow-lg">
            <!-- โลโก้ -->
            <div class="flex justify-center mb-6 md:mt-36 mt-36">
                <img src="public/assets/image/logo-mymap.png" alt="Login Image" class="w-32 h-32">
            </div>

            <!-- ปุ่ม Google Login -->
            <a id="google-login" href="{{ route('redirect.google') }}"
                class="w-full flex items-center border border-gray-300 justify-center py-3 rounded-2xl bg-white hover:bg-gray-100">
                <img src="public/assets/image/icon-google.png" class="w-5 h-5 mr-3"> Log in with Google
            </a>
        </div>
    </body>
@endsection



</html>
