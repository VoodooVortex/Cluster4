@extends('layouts.default')
@section('content')
    <div class="w-full mx-auto mt-16 space-y-6 px-6">
        <div class="mb-2 px-4">
            <label
                class="bg-[#4D55A0] text-white border-[#4D55A0] text-2xl font-extrabold py-3 rounded-2xl flex items-center w-full pl-4">
                รายงานทั้งหมด
            </label>
        </div>
        <div class="flex justify-between items-center px-6">
            <!-- ฝั่งซ้าย -->
            <h1>ยอดรวมของปี {{ $selectedYear }}</h1>

            <!-- ฝั่งขวา: ปุ่ม dropdown -->
            <div class="relative inline-block text-left w-[120px]" x-data="{ open: false }">
                <button type="button" @click="open = !open"
                    class="inline-flex w-full justify-between items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50"
                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                    {{ $selectedYear }}
                    <svg class="ml-2 size-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 z-10 mt-2 w-full origin-top-right rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-none"
                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                    <div class="py-1" role="none">
                        @foreach ($allYears as $year)
                            <a href="{{ route('report_CEO', ['year' => $year]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
                                tabindex="-1">
                                {{ $year }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 px-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">ยอดรวมทั้งหมดของปี {{ $selectedYear }}</p>
                        <div class="text-3xl font-bold flex items-center">
                            <span>{{ number_format($totalAmount) }} ชิ้น</span>
                            @if ($growthPercentage > 0)
                                <span class="ml-4 text-sm text-green-600">
                                    + {{ number_format($growthPercentage) }}%
                                @elseif ($growthPercentage < 0)
                                    <span class="ml-4 text-sm text-red-600">
                                        - {{ number_format(abs($growthPercentage)) }}%
                                    @else
                                        <span class="ml-4 text-sm text-gray-600">0%</span>
                            @endif
                            </span>
                        </div>
                        <p class="text-sm">ค่าเฉลี่ยรายเดือนอยู่ที่ {{ number_format($average) }} ชิ้น</p>
                    </div>

                    <div class="p-4 rounded-full">
                        <i class="fa-solid fa-box fa-2xl" style="color: #4d55a0;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-center px-6">
            <!-- ฝั่งซ้าย -->
            <h1>กราฟยอดขายปี {{ $selectedYear }}</h1>
        </div>
        <!-- กราฟแท่ง -->
        <div class="mb-4 px-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
                <p class="font-semibold text-lg mb-4">ยอดขายในปีนี้</p>
                <div class="w-full h-[300px] sm:h-[350px] md:h-[400px] lg:h-[450px]">
                    <canvas id="salesChart" class="w-full h-full max-w-full max-h-full"></canvas>
                </div>
            </div>
        </div>
        {{-- <!-- กราฟวงกลม -->
        <div class="mb-4 px-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
                <div class="w-full h-[300px] sm:h-[350px] md:h-[400px] lg:h-[450px]">
                    <canvas id="cicleChart"></canvas>
                </div>
            </div>
        </div> --}}
        <!-- จำนวนสาขา -->
        <div class="mb-4 px-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">จำนวนสาขาทั้งหมด</p>
                        <p class="text-3xl font-bold">{{ number_format($currentYearBranches) }} สาขา</p>
                        <p class="text-green-600 text-sm">จำนวนสาขาเพิ่มขึ้นเฉลี่ย {{ $growthPercentageBranches }}%</p>
                    </div>
                    <div class="p-4 rounded-full">
                        <i class="fa-solid fa-warehouse fa-2xl" style="color: #4D55A0;"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- กราฟอัตราการเติบโตสาขา -->
        <div class="mb-4 px-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
                <div class="w-full h-[300px] sm:h-[350px] md:h-[400px] lg:h-[450px]">
                    <canvas id="branchChart" class="w-full h-full max-w-full max-h-full"></canvas>
                </div>
            </div>
        </div>
        <!-- จำนวนพนักงาน -->
        <div class="mb-4 px-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
                <p class="font-semibold text-lg mb-3">จำนวนพนักงานทั้งหมด</p>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $currentYearEmployeeCount }} คน</h2>
                    <i class="fa-solid fa-users text-indigo-600 text-2xl"></i>
                </div>
                <hr class="my-3">
                <p class="text-base text-gray-700"> Sales
                    <span class="float-right text-indigo-500 font-medium">{{ $currentYearRoleCounts['Sales'] }} คน</span>
                </p>
                <hr class="my-3">
                <p class="text-base text-gray-700"> Sales Supervisor
                    <span class="float-right text-indigo-500 font-medium">{{ $currentYearRoleCounts['Sales Supervisor'] }}
                        คน</span>
                </p>
                <hr class="my-3">
                <p class="text-base text-gray-700"> CEO
                    <span class="float-right text-indigo-500 font-medium">{{ $currentYearRoleCounts['CEO'] }} คน</span>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    {{-- กราฟแท่ง --}}
    <script>
        const ctxbar = document.getElementById('salesChart').getContext('2d');

        // ข้อมูลกราฟ: ข้อมูลยอดขายที่ส่งมาจาก Controller
        const salesData = {
            labels: @json($sales->pluck('od_month')), // แสดงชื่อเดือนจากข้อมูลที่ดึงมา
            datasets: [{
                label: 'ยอดขาย',
                data: @json($sales->pluck('total_sales')), // ยอดขายในแต่ละเดือน
                backgroundColor: '#4D55A0', // สีของแท่ง
                borderColor: '#4D55A0)', // สีของขอบแท่ง
                borderWidth: 1
            }]
        };

        const config = {
            type: 'bar', // ชนิดกราฟ
            data: salesData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.raw + ' ชิ้น'; // แสดงข้อมูลยอดขายเป็นจำนวนชิ้น
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value) {
                            return value + ' ชิ้น';
                        },
                        color: '#ff6347',
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                        }
                    },
                    y: {
                        title: {
                            display: true,
                        },
                        beginAtZero: true
                    }
                }
            },
            plugins: [ChartDataLabels]
        };
        // สร้างกราฟ
        const salesChart = new Chart(ctxbar, config);
    </script>
    {{-- กราฟวงกลม
    <script>
        const ctxcicle = document.getElementById('cicleChart').getContext('2d');

        const regionSalesData = {
            labels: @json($regions),  // ภาค
            datasets: [{
                label: 'ยอดขายตามภาค',
                data: @json($salesData),  // ยอดขายในแต่ละภาค
                backgroundColor: [
                    '#4D55A0', '#FF6F61', '#6B8E23', '#F39C12', '#2980B9', '#E74C3C', '#8E44AD', '#1ABC9C', '#F1C40F'
                ], // สีแต่ละภาค
                borderColor: '#fff',  // สีขอบของโดนัท
                borderWidth: 2
            }]
        };

        const regionSalesConfig = {
            type: 'doughnut',
            data: regionSalesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.raw + ' ชิ้น'; // แสดงยอดขายใน tooltip
                            }
                        }
                    },
                    // เพิ่มช่องว่างในกราฟ
                    doughnut: {
                        cutoutPercentage: 50  // ช่องว่างตรงกลาง
                    }
                }
            }
        };

        const regionSalesDoughnutChart = new Chart(ctxcicle, regionSalesConfig);
    </script> --}}
    <script>
        const ctxbranch = document.getElementById('branchChart').getContext('2d');

        const labels = {!! json_encode(collect($cumulativeBranches)->pluck('month')) !!};
        const rawData = {!! json_encode(collect($cumulativeBranches)->pluck('total_branches')) !!};
        const lastMonthWithNewBranch = {{ $lastMonthWithNewBranch ?? 0 }}; // Laravel ส่งมาจาก controller

        // แปลงข้อมูลให้หยุดเส้นกราฟหลังจากเดือนสุดท้ายที่มีการเพิ่มสาขา
        const modifiedData = rawData.map((val, index) => index <= (lastMonthWithNewBranch - 1) ? val : null);

        const branchChart = new Chart(ctxbranch, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนสาขาสะสม',
                    data: modifiedData,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: '#007bff',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'การเติบโตของจำนวนสาขารายเดือน ปี {{ $selectedYear }}',
                        font: {
                            size: 18
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        },
                        title: {
                            display: true,
                            text: 'จำนวนสาขา'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'เดือน'
                        }
                    }
                }
            }
        });
    </script>
@endsection

@section('styles')
@endsection
