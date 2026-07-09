<?php require APPROOT . '/views/admin/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="p-6 space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Tổng quan hệ thống</h1>
            <p class="text-sm text-gray-400 mt-1"><?php echo date('l, d/m/Y'); ?></p>
        </div>
        <span class="text-xs bg-green-100 text-green-700 font-black px-3 py-1 rounded-full">● Hệ thống hoạt động bình thường</span>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/80 to-white/20 opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-11 w-11 rounded-2xl bg-indigo-100 text-primary flex items-center justify-center text-lg"><i class="fa-solid fa-coins"></i></div>
                    <span class="text-[10px] font-black text-indigo-400 bg-indigo-50 px-2 py-1 rounded-full">Hôm nay</span>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Doanh thu</p>
                <p class="text-2xl font-black text-gray-800 mt-1"><?php echo number_format($data['revenue_today'],0,',','.'); ?>đ</p>
                <p class="text-xs text-indigo-500 mt-1 font-bold">Tháng: <?php echo number_format($data['revenue_month'],0,',','.'); ?>đ</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/80 to-white/20 opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-11 w-11 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg"><i class="fa-solid fa-receipt"></i></div>
                    <span class="text-[10px] font-black text-blue-400 bg-blue-50 px-2 py-1 rounded-full">Hôm nay</span>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Đơn hàng</p>
                <p class="text-2xl font-black text-gray-800 mt-1"><?php echo $data['orders_today']; ?></p>
                <p class="text-xs text-blue-500 mt-1 font-bold">Tháng: <?php echo $data['orders_month']; ?> đơn</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-pink-50/80 to-white/20 opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="h-11 w-11 rounded-2xl bg-pink-100 text-pink-600 flex items-center justify-center text-lg"><i class="fa-solid fa-users"></i></div>
                    <span class="text-[10px] font-black text-pink-400 bg-pink-50 px-2 py-1 rounded-full">Tổng</span>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Khách hàng</p>
                <p class="text-2xl font-black text-gray-800 mt-1"><?php echo $data['total_customers']; ?></p>
                <p class="text-xs text-pink-500 mt-1 font-bold">Đã đăng ký tài khoản</p>
            </div>
        </div>

    </div>

    <?php if($data['low_stock_count'] > 0): ?>
    <!-- Low Stock Alert -->
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation text-orange-500 text-xl"></i>
        <div>
            <p class="text-sm font-black text-orange-800">Cảnh báo tồn kho thấp</p>
            <p class="text-xs text-orange-600">Có <strong><?php echo $data['low_stock_count']; ?></strong> sản phẩm có số lượng dưới 10. <a href="<?php echo URLROOT; ?>/admin/products" class="underline font-bold">Kiểm tra ngay →</a></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Biểu đồ Doanh thu 30 ngày -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-base font-black text-gray-800">Doanh thu 30 ngày gần đây</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Đơn vị: Việt Nam đồng (đ)</p>
                </div>
                <div class="h-10 w-10 rounded-2xl bg-indigo-50 text-primary flex items-center justify-center">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>

        <!-- Biểu đồ Trạng thái đơn hàng -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-base font-black text-gray-800">Trạng thái đơn hàng</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Tổng hợp tất cả</p>
                </div>
                <div class="h-10 w-10 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <canvas id="statusChart" height="180"></canvas>
            <div class="mt-4 space-y-2">
                <?php 
                $statusColors = ['pending'=>'bg-blue-400','completed'=>'bg-green-400','cancelled'=>'bg-red-400'];
                $statusLabels = ['pending'=>'Đang xử lý','completed'=>'Hoàn thành','cancelled'=>'Đã hủy'];
                foreach($data['order_status'] as $s): ?>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full <?php echo $statusColors[$s->status] ?? 'bg-gray-400'; ?>"></span>
                        <span class="text-gray-600 font-medium"><?php echo $statusLabels[$s->status] ?? $s->status; ?></span>
                    </div>
                    <span class="font-black text-gray-800"><?php echo $s->cnt; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top 5 sản phẩm bán chạy -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-base font-black text-gray-800">Top 5 sản phẩm bán chạy</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Theo số lượng đã bán</p>
                </div>
                <div class="h-10 w-10 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center">
                    <i class="fa-solid fa-trophy"></i>
                </div>
            </div>
            <?php if(!empty($data['top_products'])): ?>
            <div class="space-y-4">
                <?php 
                $maxSold = max(array_column((array)$data['top_products'], 'total_sold'));
                foreach($data['top_products'] as $i => $p): 
                    $pct = $maxSold > 0 ? round(($p->total_sold / $maxSold) * 100) : 0;
                ?>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-indigo-400 w-5">#<?php echo $i+1; ?></span>
                            <span class="text-sm font-bold text-gray-700 truncate max-w-[180px]"><?php echo $p->name; ?></span>
                        </div>
                        <span class="text-xs font-black text-gray-500"><?php echo $p->total_sold; ?> cái</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-primary rounded-full h-2 transition-all duration-500" style="width: <?php echo $pct; ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center py-10 text-gray-300">
                <i class="fa-solid fa-box-open text-4xl mb-2"></i>
                <p class="text-sm">Chưa có dữ liệu</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Doanh thu theo danh mục -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-base font-black text-gray-800">Doanh thu theo danh mục</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Từ tất cả đơn hàng hoàn thành</p>
                </div>
                <div class="h-10 w-10 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
            <canvas id="categoryChart" height="200"></canvas>
        </div>
    </div>

    <!-- Bottom Row: Recent Orders + Upcoming Appointments -->
    <div class="grid grid-cols-1 gap-6">

        <!-- Đơn hàng mới nhất -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-black text-gray-800">Đơn hàng mới nhất</h2>
                <a href="<?php echo URLROOT; ?>/admin/orders" class="text-xs font-black text-primary hover:underline">Xem tất cả →</a>
            </div>
            <?php if(!empty($data['recent_orders'])): ?>
            <div class="space-y-3">
                <?php foreach($data['recent_orders'] as $o): ?>
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-indigo-50 text-primary flex items-center justify-center font-black text-xs">
                            #<?php echo $o->id; ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-gray-800"><?php echo $o->customer_name ?? 'Khách lẻ'; ?></p>
                                <?php if(!empty($o->membership_level)): ?>
                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase
                                        <?php 
                                            $l = $o->membership_level;
                                            echo $l == 'VIP' ? 'bg-purple-100 text-purple-600' : 
                                                ($l == 'Bạch kim' ? 'bg-blue-100 text-blue-600' : 
                                                ($l == 'Vàng' ? 'bg-yellow-100 text-yellow-700' : 
                                                ($l == 'Bạc' ? 'bg-slate-100 text-slate-600' : 'bg-orange-100 text-orange-600')));
                                        ?>">
                                        <?php echo $l; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-gray-400"><?php echo date('d/m/Y H:i', strtotime($o->created_at)); ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-indigo-600"><?php echo number_format($o->total_amount,0,',','.'); ?>đ</p>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full
                            <?php echo $o->status=='completed' ? 'bg-green-100 text-green-600' : ($o->status=='cancelled' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600'); ?>">
                            <?php echo ['pending'=>'Xử lý','completed'=>'Hoàn thành','cancelled'=>'Đã hủy'][$o->status] ?? $o->status; ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center py-10 text-gray-300">
                <i class="fa-solid fa-receipt text-4xl mb-2"></i>
                <p class="text-sm">Chưa có đơn hàng</p>
            </div>
            <?php endif; ?>
        </div>


    </div>
</div>

<script>
// --- Biểu đồ doanh thu 30 ngày ---
const dailyData = <?php echo json_encode($data['daily_revenue']); ?>;
const revenueLabels = dailyData.map(d => {
    const dt = new Date(d.date); 
    return (dt.getDate()).toString().padStart(2,'0') + '/' + (dt.getMonth()+1).toString().padStart(2,'0');
});
const revenueVals = dailyData.map(d => parseFloat(d.revenue));

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Doanh thu (đ)',
            data: revenueVals,
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79,70,229,0.08)',
            borderWidth: 2.5,
            pointRadius: 3,
            pointBackgroundColor: '#4f46e5',
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: {
            callbacks: { label: ctx => new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' đ' }
        }},
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: '#f1f5f9' }, ticks: {
                font: { size: 11 },
                callback: v => new Intl.NumberFormat('vi-VN', {notation:'compact'}).format(v)
            }}
        }
    }
});

// --- Biểu đồ Trạng thái đơn hàng (Doughnut) ---
const statusData = <?php echo json_encode($data['order_status']); ?>;
const statusMap = { pending: 'Đang xử lý', completed: 'Hoàn thành', cancelled: 'Đã hủy' };
const statusColorMap = { pending: '#60a5fa', completed: '#4ade80', cancelled: '#f87171' };

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusData.map(s => statusMap[s.status] || s.status),
        datasets: [{
            data: statusData.map(s => s.cnt),
            backgroundColor: statusData.map(s => statusColorMap[s.status] || '#d1d5db'),
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + ' đơn' } } }
    }
});

// --- Biểu đồ Doanh thu theo danh mục (Bar) ---
const catData = <?php echo json_encode($data['category_revenue']); ?>;
const catColors = ['#818cf8','#34d399','#fb923c','#f472b6','#38bdf8','#a78bfa'];

new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: catData.map(c => c.category),
        datasets: [{
            label: 'Doanh thu',
            data: catData.map(c => parseFloat(c.revenue)),
            backgroundColor: catColors.slice(0, catData.length),
            borderRadius: 10,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: {
            callbacks: { label: ctx => new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' đ' }
        }},
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: '#f1f5f9' }, ticks: {
                font: { size: 11 },
                callback: v => new Intl.NumberFormat('vi-VN', {notation:'compact'}).format(v)
            }}
        }
    }
});
</script>

<?php require APPROOT . '/views/admin/footer.php'; ?>
