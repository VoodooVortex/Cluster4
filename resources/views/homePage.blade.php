@extends('layouts.default')

@section('content')
    <div class="bg-white shadow-md rounded-2xl p-6 w-full max-w-none mt-20 border border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">ยอดขายทั้งหมดในปีนี้</p>
                <h2 class="text-3xl font-bold">{{ number_format($totalSales) }} <span class="text-lg">ชิ้น</span>
                    <span class="text-green-500 text-sm font-semibold">{{ number_format($growthPercentage) }}%</span>
                </h2>
                <p class="text-gray-400 text-xs">ค่าเฉลี่ยรายเดือนอยู่ที่ <span
                        class="font-semibold">{{ number_format($averageSales) }}</span> ชิ้น</p>
            </div>
            <div>
                <i class="fa-solid fa-box fa-2xl scale-150" style="color: #4d55a0;"></i>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-2xl p-6 w-full max-w-none mt-10 border border-gray-200">
        <p class="text-lg font-bold text-gray-800 mb-2">ยอดขายในปีนี้</p>

        <div class="w-full h-[400px] sm:h-[500px] relative">
            <canvas id="salesChart" class="w-full h-full"></canvas>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4 w-full">
            <div class="bg-white p-4 rounded-lg shadow w-full text-left border border-gray-200">
                <p class="text-sm font-semibold">Min :</p>
                <p class="text-lg font-bold" id="minValue">-</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow w-full text-left border border-gray-200">
                <p class="text-sm font-semibold">Max :</p>
                <p class="text-lg font-bold" id="maxValue">-</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlySales = @json($monthlySales);
        const labels = @json($labels); // ชื่อเดือน
        const ctx = document.getElementById('salesChart').getContext('2d');

        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // ชื่อเดือนที่ส่งมาจาก Controller
                datasets: [{
                        label: 'ยอดขายของสาขา',
                        data: Object.values(monthlySales), // ยอดขายแต่ละเดือนที่ส่งมาจาก Controller
                        backgroundColor: 'rgba(54, 79, 199, 0.8)',
                        borderRadius: 4
                    },
                    {
                        label: 'ค่ามัธยฐาน',
                        type: 'line',
                        data: [3000, 5000, 4000, 12000, 20000, 25000, 40000, 50000, 30000, 45000, 70000,
                            80000
                        ], // ข้อมูลมัธยฐานตัวอย่าง
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        pointRadius: 4,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        type: 'logarithmic',
                        min: 0,
                        max: 1000000,
                        ticks: {
                            autoSkip: false,
                            stepSize: 1000,
                            callback: function(value) {
                                const allowedTicks = [0, 1000, 10000, 100000, 1000000];
                                if (allowedTicks.includes(value)) {
                                    return value.toLocaleString(); // แสดงตัวเลขพร้อมคอมม่า
                                }
                                return '';
                            }
                        },
                        grid: {
                            drawTicks: true,
                            drawOnChartArea: true,
                            color: function(context) {
                                const tickValue = context.tick.value;
                                const allowedTicks = [0, 1000, 10000, 100000, 1000000];
                                if (allowedTicks.includes(tickValue)) {
                                    return 'rgba(0, 0, 0, 0.1)';
                                }
                                return 'transparent';
                            }
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: false
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
