
@extends('layouts.default')
@section('content')
    <div class="mt-8 flex justify-center"> <!-- ปุ่มเพิ่มบัญชีผู้ใช้ -->
        <button
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-5 px-8 rounded flex items-center w-full max-w-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        เพิ่มบัญชีผู้ใช้
        </button>
    </div>

    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <form class="w-full max-w-3xl bg-white p-6 rounded-lg shadow-lg">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-900">
                            ชื่อ <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username" placeholder="กรุณาระบุชื่อ"
                            class="mt-2 block w-full rounded-md border border-slate-200 p-2 text-gray-900 outline-indigo-600">
                    </div>
                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-900">
                            นามสกุล <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="lastname" id="lastname" placeholder="กรุณาระบุนามสกุล"
                            class="mt-2 block w-full rounded-md border border-slate-200 p-2 text-gray-900 outline-indigo-600">
                    </div>
                </div>
                <fieldset class="mt-6">
                    <legend class="text-sm font-semibold text-gray-900">ตำแหน่ง</legend>
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
                    <div class="mt-6">
                        <label for="manager" class="block text-sm font-medium text-gray-900">หัวหน้างาน</label>
                        <select id="manager" name="manager"
                            class="mt-2 block w-full rounded-md border border-slate-200 p-2 text-gray-900 outline-indigo-600">
                            <option>สมศักดิ์ รักดี</option>
                            <option>กรพศุตม์ นิมัสยวานิช</option>
                        </select>
                    </div>
    
                    <div class="mt-6">
                        <label for="email" class="block text-sm font-medium text-gray-900">อีเมล</label>
                        <input id="email" name="email" type="email" autocomplete="email" placeholder="กรุณาระบุอีเมล"
                            class="mt-2 block w-full rounded-md border border-slate-200 p-2 text-gray-900 outline-indigo-600">
                    </div>
                    <div class="mt-6 flex items-center justify-between">
                        
                        <button type="submit" name="cancle"
                            class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-black hover:bg-medium gray-500 focus:outline-none focus:ring-2 focus:ring-medium gray-600">
                            ยกเลิก
                        </button>
                        <button type="submit" name="confirm"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600">
                            ยืนยัน
                        </button>
                    </div>  
                </fieldset>
            </div>
        </form>
    </div>
@endsection