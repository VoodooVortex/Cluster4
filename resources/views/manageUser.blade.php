@extends('layouts.default')

@section('content')
    {{-- <div class="container mx-auto p-6">
        <div class="mt-4 px-3 flex justify-center"> <!-- ปุ่มจัดการบัญชีผู้ใช้ -->
            <button
                class="bg-indigo-700 text-white border-[#4D55A0] hover:bg-indigo-700 text-white text-2xl font-extrabold py-3 px- rounded-2xl flex items-center w-full max-w-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-10 mr-2" fill="none" viewBox="0 0 20 20"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                จัดการบัญชีผู้ใช้
            </button>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 flex justify-between items-center bg-white-100 border-b">
                <h5 class="text-lg font-semibold">บัญชีทั้งหมด</h5> --}}
    {{-- <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg">เพิ่มบัญชี</a> --}}
    {{-- </div>
            <div class="p-6">
                <input type="text" class="px-1 py-2 border border-gray-300 rounded-lg mb-3" placeholder="ค้นหาบัญชี"
                    id="searchUser">
                <a href="{{ url('/add-user') }}">
                    <button
                        class="px-2 py-2 text-lg bg-indigo-700 text-white border-[#4D55A0] rounded-2xl hover:scale-110 hover:bg-indigo-500">
                        เพิ่มบัญชี
                    </button>
                </a>
                <table class="w-full border-collapse border border-gray-300 mt-4">
                    <thead>
                        <tr class="bg-gray-00">
                            <th class="p-5 border"> <input type="checkbox" id="selectAll"> </th>
                            <th class="p-1 border">เลือกทั้งหมด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border">
                                <td class="p-3 border text-center"> <input type="checkbox" class="userCheckbox"> </td>
                                <td class="p-3 border">{{ $user->us_fname }}</td>
                                <td class="p-3 border">{{ $user->us_role }}</td>
                                <td class="p-3 border">{{ $user->us_email }}</td>
                                <td class="p-3 border"><span
                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded-lg">{{ $user->us_role }}</span>
                                </td>
                                <td class="p-3 border flex space-x-2"> --}}
    {{-- <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-lg">Edit</a> --}}
    {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-lg">Delete</button>
                            </form> --}}
    {{-- </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}

    <div class="max-w-lg mx-auto p-4 bg-gray-100 min-h-screen">
        {{-- ปุ่มย้อนกลับและหัวข้อ --}}
        <div class="mb-4">
            <a href="#" class="text-white bg-indigo-600 px-4 py-3 rounded-2xl flex items-left justify-left w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7m0 0l7-7" />
                </svg>
                จัดการบัญชีผู้ใช้
            </a>
        </div>

        {{-- ช่องค้นหา + ปุ่มเพิ่มบัญชี --}}
        <div class="flex space-x-2 mb-4">
            <input type="text" placeholder="ค้นหาบัญชี"
                class="w-full px-3 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <a href="{{ url('/add-user') }}"
                class="bg-indigo-600 text-white whitespace-nowrap px-10 py-1 rounded-2xl">เพิ่มบัญชี</a>
        </div>

        {{-- หมวดหมู่บัญชี --}}
        <div class="bg-white p-3 rounded-lg shadow">
            <p class="font-semibold">บัญชีทั้งหมด {{ count($users) }}</p>
            <div class="flex space-x-2 mt-2">
                {{-- <button class="px-3 py-1 border rounded-full bg-indigo-100 text-indigo-700" data-role="all">ทั้งหมด</button>
                <button class="px-3 py-1 border rounded-full" data-role="Sale">Sale</button>
                <button class="px-3 py-1 border rounded-full" data-role="Sale Supervisor">Sale Supervisor</button>
                <button class="px-3 py-1 border rounded-full" data-role="CEO">CEO</button> --}}

                <button class="filter-btn px-3 py-1 border bg-gray-200 rounded-full text-sm active"
                    data-role="all">ทั้งหมด</button>
                <button class="filter-btn px-3 py-1 border bg-gray-200 rounded-full text-sm" data-role="Sale">Sale</button>
                <button class="filter-btn px-3 py-1 border bg-gray-200 rounded-full text-sm"
                    data-role="Sales Supervisor">Sales Supervisor</button>
                <button class="filter-btn px-3 py-1 border bg-gray-200 rounded-full text-sm" data-role="CEO">CEO</button>
            </div>
        </div>

        {{-- รายชื่อบัญชี --}}
        <div id="user-list">
            <div class="bg-white mt-4 p-3 rounded-lg shadow">
                <div class="flex items-center mb-3">
                    <input type="checkbox" class="mr-2">
                    <span class="text-gray-700">เลือกทั้งหมด</span>
                </div>

                <ul>
                    @foreach ($users as $user)
                        <li class="user-item flex items-center justify-between p-2 border-b" data-role="{{ $user->us_role }}">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox">
                                <img src="{{ $user->us_image }}" class="w-10 h-10 rounded-full" alt="User Image">
                                <div>
                                    <p class="font-semibold">{{ $user->us_fname }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->us_email }}</p>
                                    <span
                                        class="text-xs px-2 py-1 rounded-full
                                    @if ($user->us_role == 'CEO') bg-yellow-200 text-yellow-800
                                    @elseif ($user->us_role == 'Sales Supervisor') bg-purple-200 text-purple-800
                                    @else bg-blue-200 text-blue-800 @endif">
                                        {{ $user->us_role }}
                                    </span>
                                </div>
                            </div>
                            <a href="#" class="text-indigo-600">Edit</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- รายการบัญชี --}}
        {{-- <div id="user-list">
            @foreach ($users as $user)
                <div class="user-item flex items-center justify-between p-4 border-b" data-role="{{ $user->us_role }}">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $user->us_image }}" class="w-10 h-10 rounded-full" alt="User Image">
                        <div>
                            <p class="font-semibold">{{ $user->us_fname }}</p>
                            <p class="text-sm text-gray-500">{{ $user->us_email }}</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 text-sm rounded-lg
                        @if ($user->us_role == 'CEO') bg-yellow-200 text-yellow-800
                        @elseif ($user->us_role == 'Sale') bg-blue-200 text-blue-800
                        @elseif ($user->us_role == 'Sale Supervisor') bg-purple-200 text-purple-800 @endif">
                        {{ $user->us_role }}
                    </span>
                </div>
            @endforeach
        </div> --}}

        {{-- Footer --}}
        <footer class="mt-6 text-center text-gray-500 text-sm">
            © mymap.com
        </footer>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".filter-btn");
            const userItems = document.querySelectorAll(".user-item");

            filterButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const role = this.getAttribute("data-role");

                    // ลบ active ออกจากทุกปุ่ม
                    filterButtons.forEach(btn => btn.classList.remove("bg-indigo-500",
                        "text-white"));
                    this.classList.add("bg-indigo-500", "text-white");

                    // แสดงหรือซ่อนบัญชี
                    userItems.forEach(item => {
                        if (role === "all" || item.getAttribute("data-role") === role) {
                            item.classList.remove("hidden");
                        } else {
                            item.classList.add("hidden");
                        }
                    });
                });
            });
        });
    </script>
@endsection
