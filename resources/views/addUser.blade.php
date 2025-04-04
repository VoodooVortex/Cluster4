@extends('layouts.default')
@section('content')
    <div class="pt-16 max-w-lg mx-auto p-4 bg-white min-h-screen w-full">
        <div class="mb-4 px-4">
            <a href="{{ url('/manage-user') }}" 
               class="text-white border-[#4D55A0] text-2xl font-extrabold py-3 rounded-2xl flex items-center w-full"
               style="background-color: #4D55A0;">
                <i class="fa-solid fa-arrow-left mx-3 fa-l"></i>
                แก้ไขข้อมูล
            </a>
        </div>

        <div class="flex items-center justify-center h-auto bg-gray-100">
            <form action="{{ url('/add-user') }}" method="post" id="editForm"
            class="w-full max-w-3xl bg-white p-6 rounded-lg shadow-lg">
            @csrf
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
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
                                <input id="sales" name="role" type="radio" value="Sales"
                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="sales" class="text-sm font-medium text-gray-900">Sales</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="supervisor" name="role" type="radio" value="Sales Supervisor"
                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="supervisor" class="text-sm font-medium text-gray-900">Sales Supervisor</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="ceo" name="role" type="radio" value="CEO"
                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="ceo" class="text-sm font-medium text-gray-900">CEO</label>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="head" class="block text-sm font-medium text-gray-900">หัวหน้างาน<span
                                style="color: red"> *</span></label>
                            <select id="head" name="head" class="mt-2 block w-full rounded-md border-2 border-gray-300 p-2 text-gray-900 outline-indigo-600">
                                @foreach ($allUser as $muser)
                                    @if ( $muser->us_role == "Sales Supervisor")
                                        <option value="{{ $muser->us_id }}">{{ $muser->us_fname }} {{ $muser->us_lname }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6">
                            <label for="email" class="block text-sm font-medium text-gray-900">อีเมล</label>
                            <input id="email" name="email" type="email" autocomplete="email"
                                placeholder="กรุณาระบุอีเมล"
                                class="mt-2 block w-full rounded-md border border-slate-200 p-2 text-gray-900 outline-indigo-600">
                        </div>
                    </fieldset>
                    <div class="mt-6 flex items-center justify-between">
                        <!-- ปุ่มยกเลิก -->
                        <button type="button" onclick="window.location.href='{{ url('/manage-user') }}'"
                            class="w-[120px] bg-white text-black border border-black px-6 py-2 rounded-lg font-bold text-base">
                            ยกเลิก
                        </button>
                        <!-- ปุ่มยืนยัน -->
                        <button type="button" onclick="confirmAddUser()"
                            class="w-[120px] bg-[#4D55A0] text-white border border-transparent px-6 py-2 rounded-lg font-bold text-base">
                            ยืนยัน
                        </button>

                <!-- โหลด SweetAlert -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                function confirmAddUser() {
                    const branchAlert = '/public/alert-icon/BranchAlert.png';
                    const deleteAlert = '/public/alert-icon/DeleteAlert.png';
                    const editAlert = '/public/alert-icon/EditAlert.png';
                    const errorAlert = '/public/alert-icon/ErrorAlert.png';
                    const orderAlert = '/public/alert-icon/OrderAlert.png';
                    const successAlert = '/public/alert-icon/SuccessAlert.png';
                    const userAlert = '/public/alert-icon/UserAlert.png';
                    Swal.fire({
                        title: 'ยืนยันการเพิ่มบัญชี',
                        imageUrl: userAlert,
                        showCancelButton: true,
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก',
                        reverseButtons: true,
                        customClass: {
                            actions: 'flex gap-4 justify-center',
                            cancelButton: 'w-[120px] bg-white text-black border border-black px-6 py-2 rounded-lg font-bold text-base',
                            confirmButton:'w-[120px] bg-[#4D55A0] text-white border border-transparent px-6 py-2 rounded-lg font-bold text-base'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('editForm').submit(); // <- ต้องตรงกับ id ของ form
                        }
                    });
                }
                </script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const salesRadio = document.getElementById("sales");
                        const headSelect = document.getElementById("head");
                        const positionRadios = document.querySelectorAll('input[name="role"]'); // เปลี่ยนจาก 'position' เป็น 'role'
                    
                        function toggleHeadSelect() {
                            // ตรวจสอบว่าเลือก Sales หรือไม่
                            headSelect.disabled = !salesRadio.checked;
                            if (!salesRadio.checked) {
                                headSelect.value = ""; // รีเซ็ตค่าเมื่อไม่เลือก Sales
                            }
                        }
                    
                        toggleHeadSelect();
                        
                        // ฟังการเปลี่ยนแปลงของตำแหน่ง
                        positionRadios.forEach(radio => {
                            radio.addEventListener("change", toggleHeadSelect);
                        });
                    });
                </script>
        
@endsection
