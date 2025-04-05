@extends('layouts.default')

@section('content')
    <div class="pt-16 bg-gray-100 w-full min-h-screen mx-auto">
        <div class="container mx-auto px-4">
            <div class="w-full max-w-full mx-auto mt-10 bg-white rounded-2xl shadow-md border border-gray-200">

                {{-- หัวข้อ --}}
                <div class="flex justify-between items-center mb-4 p-6">
                    <h2 class="text-lg font-semibold">พนักงานที่เพิ่มสาขามากที่สุด</h2>
                    <button class="flex items-center px-4 py-2 bg-white rounded-lg shadow-md border border-gray-300">
                        จำนวนสาขา
                        <i class="fa-solid fa-repeat ml-2" style="color: #000000;"></i>
                    </button>
                </div>

                {{-- อันดับ 1 --}}
                @if (count($topUsers) > 0)
                    <div class="w-full bg-[#4D55A0] text-white flex items-center shadow-md min-h-[130px] px-6">
                        <img src="{{ $topUsers[0]->us_image }}" class="w-12 h-12 rounded-full border-2 border-white"
                            alt="user">
                        <div class="ml-4">
                            <p class="font-semibold">{{ $topUsers[0]->us_fname }} {{ $topUsers[0]->us_lname }}</p>
                            <p class="text-sm">
                                <span
                                    class="px-2 mt-1 border rounded-full text-xs bg-white
                            @if ($topUsers[0]->us_role == 'CEO') border-yellow-700 text-yellow-700
                            @elseif ($topUsers[0]->us_role == 'Sales Supervisor') border-purple-500 text-purple-500
                            @else border-blue-300 text-blue-300 @endif">
                                    {{ $topUsers[0]->us_role }}
                                </span>
                            </p>
                            <p class="text-xs">{{ $topUsers[0]->us_email }}</p>
                        </div>
                        <div class="ml-auto flex flex-col items-center justify-center relative">
                            <div class="relative">
                                <i class="fa-solid fa-crown text-7xl" style="color: #FFD43B"></i>
                                <span
                                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-xl text-white">
                                    1
                                </span>
                            </div>
                            <p class="text-lg mt-1">{{ number_format($topUsers[0]->branch_count) }} สาขา</p>
                        </div>
                    </div>
                @endif

                {{-- อันดับ 2-5 --}}
                @if (count($topUsers) > 1)
                    <div class="mt-4 space-y-3 p-6">
                        @for ($i = 1; $i < count($topUsers); $i++)
                            <div class="p-4 rounded-xl bg-white flex items-center shadow-md border border-gray-300">
                                <img src="{{ $topUsers[$i]->us_image }}"
                                    class="w-12 h-12 rounded-full border border-gray-300" alt="user">
                                <div class="ml-4">
                                    <p class="font-semibold">{{ $topUsers[$i]->us_fname }} {{ $topUsers[$i]->us_lname }}</p>
                                    <p class="text-sm">
                                        <span
                                            class="px-2 mt-1 border rounded-full text-xs bg-white
                                        @if ($topUsers[$i]->us_role == 'CEO') border-yellow-700 text-yellow-700
                                        @elseif ($topUsers[$i]->us_role == 'Sales Supervisor') border-purple-500 text-purple-500
                                        @else border-blue-300 text-blue-300 @endif">
                                            {{ $topUsers[$i]->us_role }}
                                        </span>
                                    </p>
                                    <p class="text-xs">{{ $topUsers[$i]->us_email }}</p>
                                </div>
                                <div class="ml-auto flex flex-col items-center justify-center relative">
                                    @if ($i < 3)
                                        <div class="relative">
                                            <i class="fa-solid fa-crown text-5xl"
                                                style="color: {{ $i == 1 ? '#D2CFC6' : '#CD7F32' }}"></i>
                                            <span
                                                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-xl text-white">
                                                {{ $i + 1 }}
                                            </span>
                                        </div>
                                    @endif
                                    <p class="text-lg mt-1">{{ number_format($topUsers[$i]->branch_count) }} สาขา</p>
                                </div>
                            </div>
                        @endfor
                    </div>
                @else
                    <div class="text-center py-8">
                        <p>ไม่พบข้อมูลของพนักงาน</p>
                    </div>
                @endif
            </div>
        </div>
    @endsection
