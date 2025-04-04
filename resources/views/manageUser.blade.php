@extends('layouts.default')

@section('content')
    <div class="pt-16 bg-white-100 w-full">
        {{-- ปุ่มย้อนกลับและหัวข้อ --}}
        <div class="mb-4 px-4">
            <a href="#" class="text-white bg-indigo-600 px-4 py-3 rounded-2xl flex items-center justify-left w-full"
                style="background-color: #4D55A0;">
                <i class="fa-solid fa-arrow-left mr-5"></i>
                จัดการบัญชีผู้ใช้
            </a>
        </div>

        {{-- ช่องค้นหา + ปุ่มเพิ่มบัญชี --}}
        <div class="flex space-x-2 mb-4 px-4">
            <input type="text" placeholder="ค้นหาบัญชี"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
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
                <div class="flex items-center justify-between bg-gray-200 rounded-lg py-3"
                    style="background-color: #D9D9D9;">
                    <div class="flex items-center pl-2">
                        <input type="checkbox" id="selectAll" class="mr-2 h-15 w-10" onclick="toggleAllCheckboxes()">
                        <span class="text-gray-700">เลือกทั้งหมด</span>
                    </div>
                    <button id="deleteButton"
                        class="hidden bg-red-500 text-white px-4 py-1 rounded-lg text-sm hover:bg-red-600"
                        onclick="deleteUsers()">
                        ลบ (<span id="selectedCount">0</span>)
                    </button>
                </div>

                <div class="bg-white p-3 rounded-lg">
                    <div class="flex space-x-2 mt-2">
                        <button class="filter-btn px-3 py-1 border bg-gray-200 rounded-full text-sm active"
                            value="all">ทั้งหมด</button>
                        <button class="filter-btn px-3 py-1 border bg-gray-200 rounded-full text-sm"
                            value="Sale">Sale</button>
                        <button class="filter-btn px-3 py-1 border bg-gray-200 rounded-full text-sm"
                            value="Sales Supervisor">Sales Supervisor</button>
                        <button class="filter-btn px-3 py-1 border bg-gray-200 rounded-full text-sm"
                            value="CEO">CEO</button>
                    </div>
                </div>

                <ul>
                    @foreach ($users as $user)
                        <li class="user-item flex items-center justify-between p-2 border-b" value="{{ $user->us_role }}">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" class="user-checkbox h-5 w-5" onclick="toggleDeleteButton()">
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
                            {{-- <a href="{{ url('/user/' . $user->id) }}" class="text-indigo-600">Edit</a> --}}
                            <a href="{{ url('/edit-user/' . $user->us_id) }}">
                                <button class="btn btn-warning">Edit</button>
                            </a>

                            <!-- ลบบัญชี -->
                            <form action="{{ url('/delete-user') }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="user_id" value="{{ $user->us_id }}">
                            </form>

                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="mt-6 text-center text-gray-500 text-sm px-4">
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
                    const role = this.getAttribute("value");

                    // ลบ active ออกจากทุกปุ่ม
                    filterButtons.forEach(btn => btn.classList.remove("bg-indigo-500",
                        "text-white"));
                    this.classList.add("bg-indigo-500", "text-white");

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

        function deleteUsers(us_id) {
            const branchAlert = '/public/alert-icon/BranchAlert.png';
            const deleteAlert = '/public/alert-icon/DeleteAlert.png';
            const editAlert = '/public/alert-icon/EditAlert.png';
            const errorAlert = '/public/alert-icon/ErrorAlert.png';
            const orderAlert = '/public/alert-icon/OrderAlert.png';
            const successAlert = '/public/alert-icon/SuccessAlert.png';
            const userAlert = '/public/alert-icon/UserAlert.png';

            Swal.fire({
                title: 'ยืนยันการลบบัญชี',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true,
                imageUrl: deleteAlert,
                customClass: {
                    confirmButton: 'swal2-delete-custom',
                    cancelButton: 'swal2-cancel-custom',
                    title: 'no-padding-title',
                    actions: 'swal2-actions-gap',
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'ดำเนินการเสร็จสิ้น',
                        confirmButtonText: 'ตกลง',
                        imageUrl: successAlert,
                        customClass: {
                            confirmButton: 'swal2-success-custom',
                            title: 'no-padding-title',
                        },
                        buttonsStyling: false
                    })
                }
            });
        }
    </script>
@endsection
