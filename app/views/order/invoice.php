<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #<?php echo $data['type'] == 'order' ? $data['order']->id : $data['detail']->id; ?> - PETSHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .print-shadow { shadow: none; border: 1px solid #eee; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto bg-white p-10 rounded-3xl shadow-xl print-shadow">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-paw text-pink-500"></i>
                    <span class="text-indigo-600">PET</span><span class="text-gray-900">SHOP</span>
                </h1>
                <p class="text-gray-500 text-sm mt-2 font-medium">Hệ thống chăm sóc thú cưng toàn diện</p>
                <div class="mt-4 text-xs text-gray-400 leading-relaxed font-medium">
                    <p>Địa chỉ: Số 3, Vũ Công Đán, P.Tứ Minh, Hải Phòng</p>
                    <p>Hotline: 0947647052 | Email: nmtvp11223311@gmail.com</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest mb-1">Hóa đơn</h2>
                <p class="text-sm font-bold text-indigo-600 mb-2">#<?php echo $data['type'] == 'order' ? 'ORD-'.str_pad($data['order']->id, 5, '0', STR_PAD_LEFT) : 'SRV-'.str_pad($data['detail']->id, 5, '0', STR_PAD_LEFT); ?></p>
                <div class="text-xs text-gray-400 font-medium space-y-1">
                    <p>Ngày lập: <?php echo date('d/m/Y H:i'); ?></p>
                    <p>Phương thức: <?php 
                        if ($data['type'] == 'order') {
                            $pm = $data['order']->payment_method;
                            echo $pm == 'cash' ? 'Tiền mặt (COD)' : ($pm == 'vnpay' ? 'VNPay' : 'Chuyển khoản QR');
                        } else {
                            echo 'Tiền mặt';
                        }
                    ?></p>
                </div>
                <div class="mt-3">
                    <?php 
                        $status_label = 'Chưa thanh toán';
                        $status_color = 'text-red-600 border-red-200 bg-red-50';
                        
                        if ($data['type'] == 'order') {
                            $order = $data['order'];
                            if ($order->status == 'completed' || $order->status == 'shipping') {
                                $status_label = 'Đã thanh toán';
                                $status_color = 'text-green-600 border-green-200 bg-green-50';
                            } elseif ($order->payment_method == 'cash') {
                                $status_label = 'Thanh toán COD';
                                $status_color = 'text-blue-600 border-blue-200 bg-blue-50';
                            } else {
                                $status_label = 'Chờ thanh toán';
                                $status_color = 'text-orange-600 border-orange-200 bg-orange-50';
                            }
                        } else {
                            $appt = $data['detail'];
                            if ($appt->status == 'completed') {
                                $status_label = 'Đã thanh toán';
                                $status_color = 'text-green-600 border-green-200 bg-green-50';
                            } else {
                                $status_label = 'Chưa thanh toán';
                                $status_color = 'text-red-600 border-red-200 bg-red-50';
                            }
                        }
                    ?>
                    <span class="inline-block px-2.5 py-1 text-[9px] font-black uppercase tracking-wider rounded-lg border <?php echo $status_color; ?>">
                        <?php echo $status_label; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="grid grid-cols-2 gap-8 mb-10">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Khách hàng</p>
                <p class="text-base font-black text-gray-900"><?php echo $data['type'] == 'order' ? $data['order']->customer_name : $data['detail']->customer_name; ?></p>
                <p class="text-sm text-gray-500 font-medium mt-1"><?php echo $data['type'] == 'order' ? $data['order']->customer_phone : $data['detail']->customer_phone; ?></p>
                <p class="text-xs text-gray-400 font-medium mt-1 italic"><?php echo $data['type'] == 'order' ? $data['order']->customer_address : ''; ?></p>
            </div>
            <?php if($data['type'] == 'appointment'): ?>
            <div class="text-right">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Thông tin dịch vụ</p>
                <p class="text-base font-black text-gray-900"><?php echo $data['detail']->service_name; ?></p>
                <p class="text-sm text-gray-500 font-medium mt-1">Thú cưng: <?php echo $data['detail']->pet_name; ?></p>
                <p class="text-xs text-gray-400 font-medium mt-1">Bác sĩ: <?php echo $data['detail']->doctor_name; ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Details Table -->
        <div class="mb-10">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="py-4">Mô tả</th>
                        <th class="py-4 text-center">Số lượng</th>
                        <th class="py-4 text-right">Đơn giá</th>
                        <th class="py-4 text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-bold text-gray-700">
                    <?php if($data['type'] == 'order'): ?>
                        <?php foreach($data['items'] as $item): ?>
                        <tr class="border-b border-gray-50">
                            <td class="py-4"><?php echo $item->name; ?></td>
                            <td class="py-4 text-center"><?php echo $item->quantity; ?></td>
                            <td class="py-4 text-right"><?php echo number_format($item->unit_price, 0, ',', '.'); ?>đ</td>
                            <td class="py-4 text-right"><?php echo number_format($item->unit_price * $item->quantity, 0, ',', '.'); ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr class="border-b border-gray-50">
                            <td class="py-4">
                                <?php echo $data['detail']->service_name; ?>
                                <p class="text-[10px] text-gray-400 font-medium mt-1"><?php echo $data['detail']->category_name; ?></p>
                            </td>
                            <td class="py-4 text-center">
                                <?php echo $data['detail']->duration_value; ?> <?php echo $data['detail']->duration_unit == 'day' ? 'ngày' : ($data['detail']->duration_unit == 'hour' ? 'giờ' : 'lượt'); ?>
                            </td>
                            <td class="py-4 text-right"><?php echo number_format($data['detail']->final_price / $data['detail']->duration_value, 0, ',', '.'); ?>đ</td>
                            <td class="py-4 text-right"><?php echo number_format($data['detail']->final_price, 0, ',', '.'); ?>đ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="flex justify-end mb-12">
            <div class="w-64 space-y-3">
                <div class="flex justify-between text-sm text-gray-500 font-medium">
                    <span>Tạm tính</span>
                    <span><?php echo number_format($data['type'] == 'order' ? $data['order']->total_amount + ($data['order']->discount_amount??0) : $data['detail']->final_price, 0, ',', '.'); ?>đ</span>
                </div>
                <?php if ($data['type'] == 'order' && !empty($data['order']->discount_amount) && $data['order']->discount_amount > 0): ?>
                <div class="flex justify-between text-sm text-green-600 font-medium">
                    <span>Voucher (<?php echo $data['order']->voucher_code; ?>)</span>
                    <span>-<?php echo number_format($data['order']->discount_amount, 0, ',', '.'); ?>đ</span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between text-sm text-gray-500 font-medium pb-3 border-b border-gray-100">
                    <span>Giảm giá (Hội viên)</span>
                    <span>-0đ</span>
                </div>
                <div class="flex justify-between text-lg font-black text-gray-900 pt-1">
                    <span>Tổng cộng</span>
                    <span class="text-indigo-600"><?php echo number_format($data['type'] == 'order' ? $data['order']->total_amount : $data['detail']->final_price, 0, ',', '.'); ?>đ</span>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center pt-8 border-t-2 border-dashed border-gray-100">
            <p class="text-sm font-black text-gray-800 mb-1 italic">Cảm ơn bạn đã tin tưởng PETSHOP!</p>
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">Hóa đơn này có giá trị xác nhận thanh toán</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="max-w-3xl mx-auto mt-8 flex justify-center gap-4 no-print">
        <button onclick="window.print()" class="px-8 py-3 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            <i class="fa-solid fa-print mr-2"></i> In hóa đơn
        </button>
        <button onclick="window.close()" class="px-8 py-3 bg-white text-gray-700 font-black rounded-2xl hover:bg-gray-50 transition border border-gray-200">
            Đóng trang
        </button>
    </div>
</body>
</html>
