@extends('layouts.default')

@section('content')

<div class="container mx-auto p-6">
<<<<<<< HEAD
    <div class="px-6 py-2 flex justify-between items-center bg-indigo-700 border-[#4D55A0] rounded-2xl">
        <h2 class="text-2xl font-bold mb-4 text-white">จัดการบัญชีผู้ใช้</h2>
=======
    <div class="mt-4 px-3 flex justify-center"> <!-- ปุ่มจัดการบัญชีผู้ใช้ -->
        <button class="bg-indigo-700 text-white border-[#4D55A0] hover:bg-indigo-700 text-white text-2xl font-extrabold py-3 px- rounded-2xl flex items-center w-full max-w-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-10 mr-2" fill="none" viewBox="0 0 20 20"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            จัดการบัญชีผู้ใช้
        </button>
>>>>>>> 84a6c20f8960702a2cbfd6c745b0c066bb06a7c1
    </div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 flex justify-between items-center bg-white-100 border-b">
            <h5 class="text-lg font-semibold">บัญชีทั้งหมด</h5>
            {{-- <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg">เพิ่มบัญชี</a> --}}
        </div>
<<<<<<< HEAD
        {{-- <div class="p-6">
            <label class="peer ...">
                <input type="checkbox" class="peer">Check it</input>
            </label>
            <button class="hidden peer-checked:block transition-all">
                I fade out
            </button>
        </div> --}}
        <div class="p-6">
            <input type="text" class="px-3 py-2 border border-gray-100 rounded-lg mb-3" placeholder="ค้นหาบัญชี" id="searchUser">
            <button class="px-2 py-1 bg-blue-500 text-white rouded-2x1 hover:scale-200 hover:bg-indigo-500 ...">
                เพิ่มบัญชี
            </button>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 border"> <input type="checkbox" id="selectAll"> </th>
                        <th class="p-2 border">ชื่อ</th>
=======
        <div class="p-6">
            <input type="text" class="px-1 py-2 border border-gray-300 rounded-lg mb-3" placeholder="ค้นหาบัญชี" id="searchUser">
            <button class="px-2 py-2 text-lg bg-indigo-700 text-white border-[#4D55A0] rounded-2xl hover:scale-110 hover:bg-indigo-500">
                เพิ่มบัญชี
            </button>
            <table class="w-full border-collapse border border-gray-300 mt-4">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-5 border"> <input type="checkbox" id="selectAll"> </th>
                        <th class="p-1 border">เลือกทั้งหมด</th>
>>>>>>> 84a6c20f8960702a2cbfd6c745b0c066bb06a7c1
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border">
                        <td class="p-3 border text-center"> <input type="checkbox" class="userCheckbox"> </td>
                        <td class="p-3 border">{{ $user->us_fname }}</td>
                        <td class="p-3 border">{{ $user->us_role }}</td>
                        <td class="p-3 border">{{ $user->us_email }}</td>
                        <td class="p-3 border"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-lg">{{ $user->us_role }}</span></td>
                        <td class="p-3 border flex space-x-2">
                            {{-- <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-lg">Edit</a> --}}
                            {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-lg">Delete</button>
                            </form> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<<<<<<< HEAD
=======

>>>>>>> 84a6c20f8960702a2cbfd6c745b0c066bb06a7c1
@endsection
