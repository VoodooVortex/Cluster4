@extends('layouts.default')
@section('content')
    <div class="pt-16 max-w-lg mx-auto p-4 bg-gray-100 min-h-screen w-full">
        {{-- ปุ่มย้อนกลับและหัวข้อ --}}
        <div class="flex space-x-2 mb-4">
            <form action="{{ url('/edit-user') }}" method="post" id="editForm"
                class="w-full max-w-3xl bg-white p-6 rounded-lg shadow-lg">
                @csrf
                @method('put')
                <div class="col-span-full">
                    <div class="mt-2 flex items-center justify-left gap-x-3">
                        <img src="{{ $users->us_image }}" class="w-10 h-10 rounded-full" alt="User Image">
                    </div>
                </div>

                <div class="space-y-12">
                    <div class="border- border-gray-900/10 pb-5">
                        {{-- <h1 class="text-lg font-semibold text-gray-900">สมศักดิ์ รักดี</h1> --}}
                        <h1 class="text-lg font-semibold text-gray-900">{{ $users->us_fname }}        {{ $users->us_lname }}</h1>

                        <p class="mt-1 text-sm text-gray-600">{{ $users->us_email }}</p>
                        <hr class="my-4 border-gray-300">
                        <div class="mt-7 grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <input type="hidden" name="id" value="{{ $users->us_id }}">
                            <div>
                                <label for="fname" class="block text-sm font-medium text-gray-900">ชื่อ
                                    <span style="color: red">* </span></label>
                                <input type="text" name="fname" id="fname" value="{{ $users->us_fname }}" placeholder="กรุณาระบุชื่อ"
                                    class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                            </div>

                            <div>
                                <label for="lname" class="block text-sm font-medium text-gray-900">นามสกุล<span
                                        style="color: red"> * </span></label>
                                <input type="text" name="lname" id="lname" value="{{ $users->us_lname }}" placeholder="กรุณาระบุนามสกุล"
                                    class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                            </div>
                        </div>

                        <fieldset class="mt-6">
                            <legend class="text-sm font-semibold text-gray-900">ตำแหน่ง<span style="color: red"> *
                                </span></label></legend>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center gap-x-3">
                                    <input id="sales" name="role" type="radio" value="Sales"
                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="sales" class="text-sm font-medium text-gray-900">Sales</label>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <input id="supervisor" name="role" type="radio" value="Sales Supervisor"
                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="supervisor" class="text-sm font-medium text-gray-900">Sales
                                        Supervisor</label>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <input id="ceo" name="role" type="radio" value="CEO"
                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="ceo" class="text-sm font-medium text-gray-900">CEO</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="mt-0">
                    <label for="head" class="block text-sm font-medium text-gray-900">หัวหน้างาน<span
                            style="color: red"> *
                        </span></label></label>
                    {{-- <select id="head" name="head"
                        class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                        <option></option>
                        <option>สมศักดิ์ รักดี</option>
                        <option>กรพศุตม์ นิมัสยวานิช</option>
                    </select> --}}

                    <select id="head" name="head" class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                        @foreach ($allUser as $muser)
                            @if ( $muser->us_role == "Sales Supervisor")
                                <option value="{{ $muser->us_id }}">{{ $muser->us_fname }}   {{ $muser->us_lname }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mt-6">
                    <label for="email" class="block text-sm font-medium text-gray-900">อีเมล<span style="color: red">
                            *
                        </span></label></label>
                    <input id="email" name="email" type="email" autocomplete="email" value="{{ $users->us_email }}" placeholder="กรุณาระบุอีเมล"
                        class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                </div>
                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ url('/manage-user') }}">
                        <button type="button" class="text-sm font-semibold text-gray-900">ยกเลิก</button>
                    </a>
                    <button type="submit" class="bg-indigo-600 px-6 py-2 rounded text-white">ยืนยัน</button>
                </div>
            </form>
        </div>
    </div>
@endsection
