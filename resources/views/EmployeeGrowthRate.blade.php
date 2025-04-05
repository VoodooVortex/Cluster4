@extends('layouts.default')

@section('content')
    <div class="max-w-md mx-auto mt-16 px-4 space-y-6">

        {{-- การ์ดแสดงจำนวนพนักงาน --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
            <p class="font-semibold text-lg mb-3">จำนวนพนักงานทั้งหมด</p>
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-2xl font-bold text-gray-900">{{ $totalEmployees }} คน</h2>
                <i class="fa-solid fa-users text-indigo-600 text-2xl"></i>
            </div>
            <hr class="my-3">
            <p class="text-base text-gray-700"> Sales <span class="float-right text-indigo-500 font-medium">{{ $salesCount }} คน</span></p>
            <hr class="my-3">
            <p class="text-base text-gray-700"> Sales Supervisor <span class="float-right text-indigo-500 font-medium">{{ $supervisorCount }} คน</span></p>
            <hr class="my-3">
            <p class="text-base text-gray-700"> CEO <span class="float-right text-indigo-500 font-medium">{{ $ceoCount }} คน</span></p>
        </div>

        {{-- การ์ดกราฟแสดงการเติบโต --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
            <p class="font-semibold text-lg mb-4">อัตราการเติบโตของพนักงานในปีนี้</p>
            <div class="w-full" style="height: 200px;">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('growthChart').getContext('2d');
        const growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                datasets: [{
                    data: @json($growthData),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#3B82F6',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 10
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,  // สามารถเปลี่ยนเป็น false ได้หากต้องการให้เริ่มจากค่าต่ำสุดที่เหมาะสม
                        suggestedMin: 0,    // กำหนดค่าเริ่มต้นของแกน Y ให้ต่ำสุดเป็น 0
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: '#E5E7EB'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            },
                            maxRotation: 45,
                            minRotation: 30
                        },
                        grid: {
                            color: '#E5E7EB'
                        }
                    }
                }
            }
        });
    </script>
@endsection

@section('styles')
    <style>
        .dashboard-wrapper {
            max-width: 350px;
            margin: 60px auto;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .employee-card,
        .growth-card {
            padding: 24px;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .employee-card {
            width: 100%;
        }

        .employee-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .employee-card h1 {
            font-size: 15px;
            font-weight: bold;
            margin: 0;
        }

        .employee-card i.fa-users {
            font-size: 32px;
            color: #4d55a0;
        }

        .employee-card h2 {
            text-align: left;
            font-size: 20px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 10px;
        }

        .employee-card p {
            text-align: left;
            font-size: 15px;
            color: #555;
            margin: 10px 0;
        }

        .employee-card span {
            float: right;
            color: #6b71da;
        }

        .employee-card hr {
            margin: 10px 0;
        }

        .growth-card h3 {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        canvas#growthChart {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
@endsection
