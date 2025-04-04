{{--
    @title : ทำหน้าจัดการบัญชีผู้ใช้ ฟังก์ชันในแผนที่ทั้งหมด
    @author : ธนภัทร จันทร์งาม 66160226, นนทพัทธ์ ศิลธรรม 66160104
    @create date : 04/04/2568
--}}

@extends('layouts.default')

@section('content')
    <div class="pt-16 bg-white-100 w-full">
        {{-- ปุ่มย้อนกลับและหัวข้อ --}}
        <div class="mb-4 px-4">
            <a href="{{ url('/manage-user') }}"
               class="text-white border-[#4D55A0] text-2xl font-extrabold px-4 py-3 rounded-2xl flex items-center w-full"
                style="background-color: #4D55A0;">
                จัดการบัญชีผู้ใช้
            </a>
        </div>

        {{-- ช่องค้นหา + ปุ่มเพิ่มบัญชี --}}
        <div class="flex space-x-2 mb-4 px-4">
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-300"></i>
                <input type="text" placeholder="ค้นหาบัญชี"
                    class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <a href="{{ url('/add-user') }}" class="bg-indigo-600 text-white whitespace-nowrap px-5 py-2 rounded-2xl"
                style="background-color: #4D55A0;">เพิ่มบัญชี</a>
        </div>
        {{-- หมวดหมู่บัญชี --}}
        <div class="bg-white px-3 rounded-lg px-4">
            <p class="font-semibold text-2xl">บัญชีทั้งหมด {{ count($users) }}</p>
        </div>

        {{-- รายชื่อบัญชี --}}
        <div id="user-list px-4">
            <div class="bg-white mt-4 px-3 rounded-lg">
                <div class="flex items-center justify-between bg-gray-200 rounded-lg py-2"
                    style="background-color: #f0f0f0;">
                    <div class="flex items-center pl-2">
                        <input type="checkbox" id="selectAll" class="mr-2 w-6 h-6" onclick="toggleAllCheckboxes()">
                        <span class="text-gray-700">เลือกทั้งหมด</span>
                    </div>

                    <button id="deleteButton"
                        class="hidden bg-[#DB4B46] text-white px-2 py-1 rounded-lg text-sm hover:bg-red-600 mr-3"
                        onclick="deleteUsers()">
                        <i class="fa-solid fa-trash"></i>
                        ลบ (<span id="selectedCount">0</span>)

                    </button>
                </div>

                <div class="bg-white p-3 rounded-lg">
                    <div class="flex space-x-1.5 mt-2">
                        <button
                            class="filter-btn px-2 py-1 border border-black-1000 text-black-700 rounded-full text-xs active border-[#4D55A0] text-[#4D55A0]"
                            value="all">
                            ทั้งหมด (<span id="count-all">0</span>)</button>
                        <button
                            class="filter-btn px-2 py-1 border border-black-1000 text-black-700 rounded-full text-xs"
                            value="Sales">
                            Sales (<span id="count-sales">0</span>)</button>

                        <button
                            class="filter-btn px-2 py-1 border border-black-1000 text-black-700 rounded-full text-xs"
                            value="Sales Supervisor">
                            Sales Supervisor (<span id="count-supervisor">0</span>)</button>

                        <button
                            class="filter-btn px-2 py-1 border border-black-1000 text-black-700 rounded-full text-xs"
                            value="CEO">
                            CEO (<span id="count-ceo">0</span>)
                        </button>
                    </div>
                </div>

                <ul>
                    @foreach ($users as $user)
                        <li class="user-item flex items-center justify-between p-2 border-b" value="{{ $user->us_role }}">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" class="user-checkbox mr-2 w-6 h-6" onclick="toggleDeleteButton()">
                                <img src="{{ $user->us_image }}" class="w-10 h-10 rounded-full" alt="User Image">
                                <div>
                                    <p class="font-semibold">{{ $user->us_fname }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->us_email }}</p>
                                    <span
                                        class="px-2 mt-1 border rounded-full text-xs bg-white
                                        @if ($user->us_role == 'CEO')
                                            border-yellow-700 text-yellow-700
                                        @elseif ($user->us_role == 'Sales Supervisor')
                                            border-purple-500 text-purple-500
                                        @else
                                            border-blue-300 text-blue-300 @endif">
                                        {{ $user->us_role }}
                                    </span>
                                </div>
                            </div>
                            {{-- <a href="{{ url('/user/' . $user->id) }}" class="text-indigo-600">Edit</a> --}}
                            <a href="{{ url('/edit-user/' . $user->us_id) }}">
                                <button class="btn btn-warning text-[#4D55A0]">Edit</button>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".filter-btn");
            const userItems = document.querySelectorAll(".user-item");

            // นับจำนวนผู้ใช้แต่ละประเภท
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

                // อัปเดตจำนวนในปุ่มตัวกรอง
                document.getElementById("count-all").textContent = countAll;
                document.getElementById("count-sales").textContent = countSales;
                document.getElementById("count-supervisor").textContent = countSupervisor;
                document.getElementById("count-ceo").textContent = countCEO;
            }

            countUsers(); // เรียกใช้งานเมื่อโหลดหน้าเว็บ

            // ฟังก์ชันกรองผู้ใช้ตามประเภท
            filterButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const role = this.getAttribute("value");

                    // ลบ active ออกจากทุกปุ่ม
                    filterButtons.forEach(btn => btn.classList.remove("border-[#4D55A0]", "text-[#4D55A0]"));
                    this.classList.add("border-[#4D55A0]", "text-[#4D55A0]");

                    // แสดงหรือซ่อนบัญชี
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

        function toggleAllCheckboxes() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            toggleDeleteButton();
        }

        function toggleDeleteButton() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const deleteButton = document.getElementById('deleteButton');
            const selectedCount = document.getElementById('selectedCount');

            let count = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    count++;
                }
            });

            if (count > 0) {
                deleteButton.classList.remove('hidden');
            } else {
                deleteButton.classList.add('hidden');
            }

            selectedCount.textContent = count;
        }

        function deleteUsers() {
            alert("ลบบัญชีที่เลือกแล้ว!");
            location.reload();
        }
    </script>
@endsection
