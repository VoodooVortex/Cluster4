
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
{{--
    @title : ทำลำดับสาขายอดขายทั้งหมด
    @author : นนทพัทธ์ ศิลธรรม 66160104
    @create date : 05/04/2568
--}}

    <div class="pt-16 bg-white-100 w-full">
        <div class="bg-white rounded-lg shadow m-3 pb-4">
            <div class="flex flex-row">
                <h3 id="rankTitle" class="text-sm font-bold mt-1 p-4">สาขาที่ทำยอดขายดีที่สุด</h3>
                <button id="switchRankButton"
                    class="text-sm font-bold m-3 p-2 ml-auto border border-[#CAC4D0] rounded-lg border-[0.5px]"
                    onclick="switchRank()">
                    จำนวนยอดขาย
                    <i class="fa-solid fa-repeat"></i>
                </button>
            </div>

            {{-- แสดงอันดับสาขาที่มียอดขายสูงสุด --}}
            <div id="branchRank">
                @foreach ($topBranch as $index => $alluser)
                    <div
                        class="p-4 {{ $index == 0 ? 'bg-[#4D55A0] text-white w-full shadow-[0px_4px_5px_rgba(0,0,0,0.5)]' : 'bg-white border border-[#CAC4D0] rounded-lg mx-4' }}  mb-4 pb-2 flex items-center ">
                        <div class="flex flex-col">
                            <h2 class="text-lg font-bold">สาขาที่ {{ $alluser->br_id }}</h2>
                            <div class="flex items-center my-2">
                                <img src="{{ $alluser->us_image }}" class="w-10 h-10 rounded-full mr-4">
                                <h3 class="font-semibold">{{ $alluser->us_fname }}</h3>
                            </div>
                            <p class="text-sm">รหัสสาขา : {{ $alluser->br_code }}</p>
                        </div>
                        <div class="ml-auto text-xl font-bold flex flex-col items-center">
                            @if ($index == 0)
                                <div class="relative inline-block text-center">
                                    <i class="fa-solid fa-crown text-[#FFD43B] text-6xl"></i>
                                    <span
                                        class="absolute inset-0 flex justify-center items-center text-white text-3xl font-bold pt-3">
                                        1
                                    </span>
                                </div>
                                <span class="text-lg font-semibold mt-1" id="amount{{ $index }}">{{ number_format($alluser->od_amount) }} ชิ้น</span>
                            @elseif ($index == 1)
                                <div class="relative inline-block text-center">
                                    <i class="fa-solid fa-crown text-[#D2CFC6] text-5xl"></i>
                                    <span
                                        class="absolute inset-0 flex justify-center items-center text-white text-lg font-bold pt-3">
                                        2
                                    </span>
                                </div>
                                <span class="text-lg font-semibold mt-1" id="amount{{ $index }}">{{ number_format($alluser->od_amount) }} ชิ้น</span>                            @elseif ($index == 2)
                                <div class="relative inline-block text-center">
                                    <i class="fa-solid fa-crown text-[#CD7F32] text-5xl"></i>
                                    <span
                                        class="absolute inset-0 flex justify-center items-center text-white text-lg font-bold pt-3">
                                        3
                                    </span>
                                </div>
                                <span class="text-lg font-semibold mt-1" id="amount{{ $index }}">{{ number_format($alluser->od_amount) }} ชิ้น</span>
                            @else
                            <span class="text-lg font-semibold mt-1" id="amount{{ $index }}">{{ number_format($alluser->od_amount) }} ชิ้น</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function switchRank() {
            const button = document.getElementById('switchRankButton');
            const title = document.getElementById('rankTitle');
            const branchData = document.getElementById('branchRank');
            const currentText = button.innerText.trim();

            // เช็คข้อความปัจจุบันของปุ่ม
            if (currentText === "จำนวนยอดขาย") {
                // เปลี่ยนข้อความปุ่ม
                button.innerHTML = 'จำนวนสาขา <i class="fa-solid fa-repeat"></i>';

                // เปลี่ยนหัวเรื่อง
                title.innerText = 'พนักงานที่เพิ่มสาขามากที่สุด';

                // ซ่อนข้อมูลในกล่อง
                branchData.style.display = 'none';
            } else {
                // เปลี่ยนข้อความปุ่มกลับ
                button.innerHTML = 'จำนวนยอดขาย <i class="fa-solid fa-repeat"></i>';

                // เปลี่ยนหัวเรื่องกลับ
                title.innerText = 'สาขาที่ทำยอดขายดีที่สุด';

                // แสดงข้อมูลในกล่องกลับ
                branchData.style.display = 'block';
            }

            // สำหรับทุกยอดขายที่แสดงในหน้า
            const amounts = document.querySelectorAll('[id^="amount"]');
            amounts.forEach(function(amount) {
                // ใช้ toLocaleString เพื่อแสดงผลยอดขายด้วยเครื่องหมายคั่นพัน
                amount.innerText = parseInt(amount.innerText.replace(/[^0-9]/g, '')).toLocaleString() + " ชิ้น";
            });
        }
    </script>
@endsection
