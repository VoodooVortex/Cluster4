{{--
    @title : อัตราการเติบโตของสาขาในปีนี้
    @author :
        - ธนภัทร จันทร์งาม 66160226
    @create date : 05/04/2568
--}}

@extends('layouts.default')

@section('content')
    <div class="pt-16 bg-white-100 w-full">

        {{-- กล่องข้อมูลสรุป --}}
        <div class="mb-4 px-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">จำนวนสาขาทั้งหมด</p>
                        <p class="text-3xl font-bold">{{ number_format($totalBranches) }} สาขา</p>
                        <p class="text-green-600 text-sm">จำนวนสาขาเพิ่มขึ้นเฉลี่ย {{ $growthPercentage }}%</p>
                    </div>
                    <div class="p-4 rounded-full">
                        <i class="fa-solid fa-warehouse fa-2xl" style="color: #4D55A0;"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- กล่องกราฟ --}}
        <div class="mb-8 px-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow">
                <p class="font-semibold text-lg mb-4">อัตราการเติบโตของสาขาภายในปีนี้</p>
                <div class="w-full" style="height: 200px;">
                    <canvas id="growthChart"></canvas>
                </div>
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
                labels: {!! json_encode(array_keys($growthRates)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($growthRates)) !!},
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 3,
                    pointBackgroundColor: '#3B82F6',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 2, // <-- กำหนดช่วงของตัวเลขที่จะแสดงบนแกน Y
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: '#E5E7EB'
                        },
                        suggestedMax: 8 // <-- แนะนำให้กราฟมี max อย่างน้อย 8 หรือ 10
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: '#E5E7EB'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
