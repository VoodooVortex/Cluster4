@extends('layouts.default')
@section('content')
    <div class="flex justify-between items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg> <!-- ไอคอนเมนู -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg> <!-- ไอคอนโปรไฟล์ -->
    </div>
    <div class="mt-4 px-3 flex justify-center"> <!-- ปุ่มจัดการบัญชีผู้ใช้ -->
        <button
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px- rounded-2xl flex items-center w-full max-w-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-10 mr-2" fill="none" viewBox="0 0 20 20"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            แก้ไขข้อมูล
        </button>
    </div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <form class="w-full max-w-3xl bg-white p-6 rounded-lg shadow-lg">
            <div class="col-span-full">
                <div class="mt-2 flex items-center justify-left gap-x-3">
                    <svg class="size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="space-y-12">
                <div class="border- border-gray-900/10 pb-5">
                    <h1 class="text-lg font-semibold text-gray-900">สมศักดิ์ รักดี</h1>
                    <p class="mt-1 text-sm text-gray-600">somsak007@mymap.com</p>
                    <hr class="my-4 border-gray-300">
                    <div class="mt-7 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-900">ชื่อ
                                <span style="color: red">* </span></label>
                            <input type="text" name="username" id="username" placeholder="กรุณาระบุชื่อ"
                                class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                        </div>

                        <div>
                            <label for="lastname" class="block text-sm font-medium text-gray-900">นามสกุล<span
                                    style="color: red"> * </span></label></label>
                            <input type="text" name="lastname" id="lastname" placeholder="กรุณาระบุนามสกุล"
                                class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                        </div>
                    </div>

                    <fieldset class="mt-6">
                        <legend class="text-sm font-semibold text-gray-900">ตำแหน่ง<span style="color: red"> *
                            </span></label></legend>
                        <div class="mt-4 space-y-4">
                            <div class="flex items-center gap-x-3">
                                <input id="sales" name="position" type="radio" checked
                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="sales" class="text-sm font-medium text-gray-900">Sales</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="supervisor" name="position" type="radio"
                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="supervisor" class="text-sm font-medium text-gray-900">Sales Supervisor</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="ceo" name="position" type="radio"
                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="ceo" class="text-sm font-medium text-gray-900">CEO</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="mt-0">
                <label for="manager" class="block text-sm font-medium text-gray-900">หัวหน้างาน<span style="color: red"> *
                    </span></label></label>
                <select id="manager" name="manager"
                    class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                    <option>สมศักดิ์ รักดี</option>
                    <option>กรพศุตม์ นิมัสยวานิช</option>
                </select>
            </div>

            <div class="mt-6">
                <label for="email" class="block text-sm font-medium text-gray-900">อีเมล<span style="color: red"> *
                    </span></label></label>
                <input id="email" name="email" type="email" autocomplete="email" placeholder="กรุณาระบุอีเมล"
                    class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
            </div>
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="text-sm font-semibold text-gray-900">ยกเลิก</button>
                <button type="submit" class="bg-indigo-600 px-6 py-2 rounded text-white">ยืนยัน</button>
            </div>
        </form>
    </div>
    </div>
@endsection
