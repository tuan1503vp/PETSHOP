<?php require APPROOT . '/views/admin/header.php'; ?>
 
<div class="p-6">
    <!-- Header Page -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Báo cáo Y tế & Dịch tễ</h1>
            <p class="text-sm text-gray-500 mt-1">Phân tích số liệu lâm sàng, tình hình dịch bệnh và tiêm chủng tại phòng khám.</p>
        </div>
    </div>
 
    <!-- Grid layout for Top charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Biểu đồ Vắc-xin -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-syringe text-emerald-500"></i> Top 5 Vắc-xin được tiêm nhiều nhất
                </h2>
                <div class="relative h-64 flex items-center justify-center">
                    <?php if (empty($data['topVaccines'])): ?>
                        <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50/50 rounded-2xl border border-dashed border-gray-200 p-6">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-syringe text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700">Chưa có dữ liệu tiêm phòng</p>
                            <p class="text-xs text-gray-400 text-center mt-1">Dữ liệu thống kê vắc-xin sẽ xuất hiện khi có ghi nhận ca tiêm chủng.</p>
                        </div>
                    <?php else: ?>
                        <canvas id="vaccineChart"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
 
        <!-- Biểu đồ Bệnh tật -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-virus text-rose-500"></i> Các bệnh lâm sàng phổ biến nhất
                </h2>
                <div class="relative h-64 flex items-center justify-center">
                    <?php if (empty($data['topDiseases'])): ?>
                        <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50/50 rounded-2xl border border-dashed border-gray-200 p-6">
                            <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-virus text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700">Chưa có dữ liệu bệnh án</p>
                            <p class="text-xs text-gray-400 text-center mt-1">Thống kê dịch tễ sẽ hiển thị khi có chẩn đoán bệnh từ hồ sơ y tế.</p>
                        </div>
                    <?php else: ?>
                        <canvas id="diseaseChart"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Biểu đồ Ca khám theo tháng -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-6 flex items-center gap-2">
            <i class="fa-solid fa-chart-line text-indigo-500"></i> Biểu đồ số ca khám hoàn thành trong năm
        </h2>
        <div class="relative h-80">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Cấu hình chung cho Chart.js để tăng tính thẩm mỹ
    Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
 
    // 1. Dữ liệu Vắc-xin (Doughnut Chart)
    <?php if (!empty($data['topVaccines'])): ?>
    const vacData = <?php echo json_encode($data['topVaccines']); ?>;
    new Chart(document.getElementById('vaccineChart'), {
        type: 'doughnut',
        data: {
            labels: vacData.map(item => item.vaccine_name),
            datasets: [{
                data: vacData.map(item => item.cnt),
                backgroundColor: ['#10b981', '#34d399', '#6ee7b7', '#059669', '#a7f3d0'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'right', 
                    labels: { 
                        boxWidth: 10, 
                        padding: 15,
                        font: { size: 12, weight: 'bold' } 
                    } 
                }
            },
            cutout: '75%'
        }
    });
    <?php endif; ?>
 
    // 2. Dữ liệu Bệnh (Bar Chart ngang)
    <?php if (!empty($data['topDiseases'])): ?>
    const disData = <?php echo json_encode($data['topDiseases']); ?>;
    new Chart(document.getElementById('diseaseChart'), {
        type: 'bar',
        data: {
            labels: disData.map(item => item.disease_name),
            datasets: [{
                label: 'Số ca',
                data: disData.map(item => item.cnt),
                backgroundColor: 'rgba(244, 63, 94, 0.85)', // Rose 500 với opacity
                hoverBackgroundColor: '#f43f5e',
                borderRadius: 8,
                barThickness: 18, // Giới hạn độ dày cột tránh tràn cột khi có ít dữ liệu
                maxBarThickness: 24
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                x: { 
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                },
                y: { 
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        font: { size: 12, weight: 'bold' }
                    }
                }
            }
        }
    });
    <?php endif; ?>
 
    // 3. Dữ liệu Lịch hẹn theo tháng (Line Chart toàn năm)
    const monthData = <?php echo json_encode($data['monthlyAppointments']); ?>;
    // Khởi tạo mảng 12 tháng đầy đủ để vẽ đường xu hướng mượt mà
    const allMonths = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
    const dataPoints = Array(12).fill(0);
 
    monthData.forEach(item => {
        const mIdx = parseInt(item.month) - 1;
        if (mIdx >= 0 && mIdx < 12) {
            dataPoints[mIdx] = parseInt(item.cnt);
        }
    });
 
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: allMonths,
            datasets: [{
                label: 'Số ca khám hoàn thành',
                data: dataPoints,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.05)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' }
                }
            },
            scales: {
                x: { 
                    grid: { display: false },
                    border: { display: false }
                },
                y: { 
                    border: { dash: [4, 4], display: false }, 
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });
</script>
 
<?php require APPROOT . '/views/admin/footer.php'; ?>
