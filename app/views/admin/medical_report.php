<?php require APPROOT . '/views/admin/header.php'; ?>

<div class="p-6">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-800">Báo cáo Y tế & Tiêm phòng</h1>
        <p class="text-sm text-gray-500 mt-1">Thống kê dịch tễ, tình hình khám chữa bệnh và tiêm chủng của phòng khám.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Biểu đồ Vắc-xin -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-6 flex items-center gap-2">
                <i class="fa-solid fa-syringe text-emerald-500"></i> Top 5 Vắc-xin được tiêm nhiều nhất
            </h2>
            <div class="relative h-64">
                <canvas id="vaccineChart"></canvas>
            </div>
            <?php if (empty($data['topVaccines'])): ?>
                <div class="absolute inset-0 flex items-center justify-center bg-white/80">
                    <p class="text-sm text-gray-400 italic">Chưa có dữ liệu tiêm phòng.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Biểu đồ Bệnh tật -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h2 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-6 flex items-center gap-2">
                <i class="fa-solid fa-virus text-red-500"></i> Các bệnh lâm sàng phổ biến nhất
            </h2>
            <div class="relative h-64">
                <canvas id="diseaseChart"></canvas>
            </div>
            <?php if (empty($data['topDiseases'])): ?>
                <div class="absolute inset-0 flex items-center justify-center bg-white/80">
                    <p class="text-sm text-gray-400 italic">Chưa có dữ liệu khám bệnh.</p>
                </div>
            <?php endif; ?>
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
    // Config chung
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#9ca3af';

    // 1. Dữ liệu Vắc-xin (Pie/Doughnut Chart)
    const vacData = <?php echo json_encode($data['topVaccines']); ?>;
    if (vacData.length > 0) {
        new Chart(document.getElementById('vaccineChart'), {
            type: 'doughnut',
            data: {
                labels: vacData.map(item => item.vaccine_name),
                datasets: [{
                    data: vacData.map(item => item.cnt),
                    backgroundColor: ['#10b981', '#34d399', '#6ee7b7', '#059669', '#a7f3d0'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 12, padding: 15 } }
                },
                cutout: '70%'
            }
        });
    }

    // 2. Dữ liệu Bệnh (Bar Chart ngang)
    const disData = <?php echo json_encode($data['topDiseases']); ?>;
    if (disData.length > 0) {
        new Chart(document.getElementById('diseaseChart'), {
            type: 'bar',
            data: {
                labels: disData.map(item => item.disease_name),
                datasets: [{
                    label: 'Số ca',
                    data: disData.map(item => item.cnt),
                    backgroundColor: '#ef4444',
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    // 3. Dữ liệu Lịch hẹn theo tháng (Line/Bar Chart)
    const monthData = <?php echo json_encode($data['monthlyAppointments']); ?>;
    if (monthData.length > 0) {
        // Build 12 tháng gần nhất
        let labels = [];
        let dataPoints = [];
        monthData.forEach(item => {
            labels.push('Tháng ' + item.month);
            dataPoints.push(item.cnt);
        });

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số ca khám hoàn thành',
                    data: dataPoints,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { border: { dash: [4, 4] }, grid: { color: '#f3f4f6' } }
                }
            }
        });
    }
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
