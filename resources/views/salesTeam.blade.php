@extends('layouts.default')

@section('content')
    <div class="pt-16 bg-white w-full min-h-screen">

        {{-- ปุ่มย้อนกลับ + หัวข้อ --}}
        <div class="mb-4 px-4">
            <div class="text-white text-2xl font-extrabold py-3 rounded-2xl flex items-center w-full" style="background-color: #4D55A0;">
                <a href="#" class="mx-3">
                    <i class="fa-solid fa-arrow-left fa-l"></i>
                </a>
                รายชื่อทีมงาน
            </div>
        </div>

        {{-- ปุ่ม Filter --}}
        <div class="px-4 mb-4">
            <div class="flex flex-wrap gap-3">
                <button
                    class="filter-btn px-4 py-1.5 border rounded-full text-sm border-[#4D55A0] text-[#4D55A0] font-semibold"
                    value="all">
                    ทั้งหมด (<span id="count-all">0</span>)
                </button>
                <button class="filter-btn px-4 py-1.5 border rounded-full text-sm text-gray-600" value="Sales">
                    Sales (<span id="count-sales">0</span>)
                </button>
                <button class="filter-btn px-4 py-1.5 border rounded-full text-sm text-gray-600" value="Sales Supervisor">
                    Sales Supervisor (<span id="count-supervisor">0</span>)
                </button>
                <button class="filter-btn px-4 py-1.5 border rounded-full text-sm text-gray-600" value="CEO">
                    CEO (<span id="count-ceo">0</span>)
                </button>
            </div>
        </div>

        {{-- รายการผู้ใช้ --}}
        <div class="px-4 space-y-2">
            @foreach ($users as $user)
                <div class="user-item flex items-center justify-between py-4 {{ $loop->last ? '' : 'border-b border-gray-200' }}"
                    value="{{ $user->us_role }}">
                    <div class="flex items-center gap-4">
                        <img src="{{ $user->us_image ?? 'https://via.placeholder.com/40' }}"
                            class="w-14 h-14 rounded-full object-cover border border-gray-300 shadow-sm" alt="avatar">
                        <div>
                            <div class="font-semibold text-lg text-gray-800">{{ $user->us_fname }} {{ $user->us_lname }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $user->us_email }}</div>
                            <span
                                class="inline-block mt-1 px-2 py-0.5 border rounded-full text-xs bg-white
                                @if ($user->us_role == 'CEO') border-yellow-700 text-yellow-700
                                @elseif ($user->us_role == 'Sales Supervisor') border-purple-500 text-purple-500
                                @else border-blue-300 text-blue-300 @endif">
                                {{ $user->us_role }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('team.detail', $user->us_id) }}" class="text-[#4D55A0] text-sm font-medium hover:underline">
                        รายละเอียด
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const filterButtons = document.querySelectorAll(".filter-btn");
            const userItems = document.querySelectorAll(".user-item");

            function countUsers() {
                let countAll = userItems.length;
                let countSales = 0,
                    countSupervisor = 0,
                    countCEO = 0;

                userItems.forEach(item => {
                    const role = item.getAttribute("value");
                    if (role === "Sales") countSales++;
                    if (role === "Sales Supervisor") countSupervisor++;
                    if (role === "CEO") countCEO++;
                });

                document.getElementById("count-all").textContent = countAll;
                document.getElementById("count-sales").textContent = countSales;
                document.getElementById("count-supervisor").textContent = countSupervisor;
                document.getElementById("count-ceo").textContent = countCEO;
            }

            countUsers();

            filterButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const role = this.getAttribute("value");

                    filterButtons.forEach(btn => {
                        btn.classList.remove("border-[#4D55A0]", "text-[#4D55A0]", "font-semibold");
                        btn.classList.add("text-gray-600");
                    });

                    this.classList.add("border-[#4D55A0]", "text-[#4D55A0]", "font-semibold");
                    this.classList.remove("text-gray-600");

                    userItems.forEach(item => {
                        if (role === "all" || item.getAttribute("value") === role) {
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
