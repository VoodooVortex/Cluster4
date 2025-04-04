<aside class="app-sidebar">
    <!-- Sidebar -->
    <div id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform z-50">
        <!-- Close Button -->
        <button id="closeMenu" class="absolute top-4 right-4 text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Sidebar Menu -->
        <ul class="pt-5 mt-4">
            <li class="px-4 py-3 hover:bg-gray-200 flex items-center text-lg font-medium">
                <i class="fa-solid fa-house mr-3" style="color: #595959;"></i> หน้าแรก
            </li>
            <li class="px-4 py-3 hover:bg-gray-200 flex items-center text-lg font-medium">
                <i class="fa-solid fa-globe mr-3" style="color: #595959;"></i> แผนที่
            </li>
            <li class="{{ Request::is('manage-user') ? 'bg-indigo-100 text-indigo-600' : '' }}">
                <a href="{{ url('/manage-user') }}" class="px-4 py-3 hover:bg-gray-200 flex items-center text-lg font-medium">
                    <i class="fa-solid fa-circle-user mr-3" style="color: #595959;"></i> จัดการบัญชี
                </a>
            </li>
            {{-- <a href="{{ url('/manage-user') }}">
                <li class="px-4 py-3 hover:bg-gray-200 flex items-center text-lg font-medium">
                    <i class="fa-solid fa-circle-user mr-3" style="color: #595959;"></i> จัดการบัญชี
                </li>
            </a> --}}
            <li class="px-4 py-3 hover:bg-gray-200 flex items-center text-lg font-medium">
                <i class="fa-solid fa-chart-column mr-3" style="color: #595959;"></i> ยอดขาย
            </li>
            <li class="px-4 py-3 hover:bg-gray-200 flex items-center text-lg font-medium">
                <i class="fa-regular fa-calendar-days mr-3" style="color: #595959;"></i> รายงาน
            </li>
            <hr class="my-2">
            <li class="px-4 py-3 hover:bg-gray-200 flex items-center text-lg font-medium">
                <i class="fa-solid fa-right-from-bracket mr-3" style="color: #595959;"></i> ออกจากระบบ
            </li>
        </ul>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black opacity-50 hidden z-40"></div>
</aside>
