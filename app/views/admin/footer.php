        </main>
    </div>

    <!-- AJAX Polling Script cho Thông báo Real-time -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chỉ chạy polling nếu user đăng nhập (footer này chỉ nạp ở admin)
        let lastPending = null;
        let lastPendingOrder = null;
        let lastWaiting = null;
        const userRole = '<?php echo $_SESSION["user_role"] ?? ""; ?>';

        function checkUpdates() {
            fetch('<?php echo URLROOT; ?>/admin/check_updates')
                .then(res => res.json())
                .then(data => {
                    let changed = false;
                    
                    if (lastPending === null) {
                        lastPending = data.pending_count;
                        lastPendingOrder = data.pending_order_count;
                        lastWaiting = data.waiting_pay_count;
                        
                        // Update badges on first load dynamically if elements exist
                        updateBadge('badge-pending-order', data.pending_order_count);
                        updateBadge('badge-pending-appt', data.pending_count);
                        return; // Lần đầu chỉ lưu trạng thái hiện tại
                    }

                    if (data.pending_count > lastPending) {
                        if (userRole === 'manager') {
                            showToast('Có lịch hẹn dịch vụ mới đang chờ xác nhận!');
                        }
                        changed = true;
                    }
                    if (data.pending_order_count > lastPendingOrder) {
                        if (userRole === 'manager') {
                            showToast('Có đơn đặt hàng online mới đang chờ xác nhận!');
                        }
                        changed = true;
                    }
                    if (data.waiting_pay_count > lastWaiting) {
                        if (userRole === 'cashier') {
                            showToast('Có dịch vụ mới đã hoàn thành và đang chờ thanh toán!');
                        }
                        changed = true;
                    }

                    lastPending = data.pending_count;
                    lastPendingOrder = data.pending_order_count;
                    lastWaiting = data.waiting_pay_count;

                    // Cập nhật số trên menu bên trái
                    updateBadge('badge-pending-order', data.pending_order_count);
                    updateBadge('badge-pending-appt', data.pending_count);
                })
                .catch(err => console.error('Polling error:', err));
        }

        function updateBadge(idClass, count) {
            const els = document.querySelectorAll('.' + idClass);
            els.forEach(el => {
                if (count > 0) {
                    el.textContent = count;
                    el.style.display = 'inline-flex';
                } else {
                    el.style.display = 'none';
                }
            });
        }
                .catch(err => console.error('Polling error:', err));
        }

        function showToast(msg) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-gradient-to-r from-primary to-indigo-600 text-white px-6 py-4 rounded-xl shadow-2xl z-[9999] flex items-center justify-between gap-4 animate-fade-in border border-white/20';
            toast.innerHTML = `
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-3 animate-pulse">
                        <i class="fa-solid fa-bell text-yellow-300"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm leading-tight">${msg}</p>
                        <p class="text-[10px] text-white/70 mt-0.5">Dữ liệu đã được cập nhật trên hệ thống</p>
                    </div>
                </div>
                <div class="flex flex-col gap-1 ml-4 border-l border-white/20 pl-4">
                    <button onclick="window.location.reload()" class="bg-white text-primary hover:bg-gray-100 px-3 py-1.5 rounded-lg text-xs font-black transition whitespace-nowrap shadow-sm"><i class="fa-solid fa-rotate-right mr-1"></i> Tải lại</button>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/70 hover:text-white text-xs text-right mt-1">Đóng</button>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Auto remove sau 15 giây
            setTimeout(() => { if(document.body.contains(toast)) toast.remove(); }, 15000);
        }

        // Gọi ngay lần đầu
        checkUpdates();
        
        // Polling mỗi 5 giây
        setInterval(checkUpdates, 5000);
    });
    </script>
</body>
</html>
