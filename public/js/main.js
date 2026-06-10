// Main JavaScript
document.addEventListener('DOMContentLoaded', () => {
    console.log('PETSHOP app initialized');
    
    // Global Form Submission Interceptor for "Add to Cart"
    document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (form.action && form.action.includes('/cart/add/')) {
            e.preventDefault();
            
            const url = form.action;
            const formData = new FormData(form);
            formData.append('ajax', '1');
            
            // Find submit button and save original state
            const submitBtn = form.querySelector('button[type="submit"]');
            let originalContent = '';
            if (submitBtn) {
                originalContent = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin"></i>';
            }
            
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await res.json();
                
                if (data.success) {
                    // Update cart badges
                    const badges = [
                        document.getElementById('cart-badge'),
                        document.getElementById('cart-badge-mobile')
                    ];
                    
                    badges.forEach(badge => {
                        if (badge) {
                            badge.innerText = data.cartCount;
                            if (data.cartCount === 0) {
                                badge.classList.add('hidden');
                            } else {
                                badge.classList.remove('hidden');
                                // Trigger scale/bounce animation
                                badge.classList.add('animate-bounce');
                                setTimeout(() => badge.classList.remove('animate-bounce'), 1000);
                            }
                        }
                    });
                    
                    showToast(data.message || 'Đã thêm sản phẩm vào giỏ hàng.', 'success');
                } else {
                    showToast(data.message || 'Vui lòng đăng nhập để sử dụng giỏ hàng.', 'error');
                }
            } catch (err) {
                console.error('Add to cart error:', err);
                showToast('Không thể kết nối đến máy chủ. Vui lòng thử lại!', 'error');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }
            }
        }
    });
});

// Premium Toast Notification System
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed bottom-6 right-6 z-[99999] flex flex-col gap-3 max-w-sm w-[calc(100%-3rem)] pointer-events-none';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = 'pointer-events-auto bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-gray-100 dark:border-slate-800 rounded-2xl shadow-xl p-4 flex items-center gap-3 transform translate-y-4 opacity-0 transition-all duration-500 border-l-4 ' + 
        (type === 'success' ? 'border-l-primary' : 'border-l-rose-500');
    
    const icon = type === 'success' 
        ? '<div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-primary dark:text-indigo-400 flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-sm"></i></div>'
        : '<div class="w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-900/30 text-rose-500 dark:text-rose-400 flex items-center justify-center shrink-0"><i class="fa-solid fa-exclamation text-sm"></i></div>';
        
    toast.innerHTML = `
        ${icon}
        <div class="flex-1 min-w-0">
            <p class="text-xs font-bold text-gray-800 dark:text-white leading-snug">${message}</p>
        </div>
        <button onclick="this.closest('.pointer-events-auto').style.opacity = '0'; setTimeout(() => this.closest('.pointer-events-auto').remove(), 500)" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition shrink-0 ml-1">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Animate in
    requestAnimationFrame(() => {
        setTimeout(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 10);
    });
    
    // Auto remove
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 500);
    }, 3500);
}
