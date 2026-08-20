    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fa-solid fa-paw text-secondary mr-2"></i> PETSHOP
                    </h3>
                    <p class="text-gray-300">Hệ thống chăm sóc thú cưng toàn diện. Đặt lịch khám, mua sắm và tư vấn sức khỏe bằng AI 24/7.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên kết</h3>
                    <ul class="space-y-2">
                        <li><a href="<?php echo URLROOT; ?>/product" class="text-gray-300 hover:text-white transition">Cửa hàng Online</a></li>
                        <li><a href="<?php echo URLROOT; ?>/service" class="text-gray-300 hover:text-white transition">Đặt lịch dịch vụ</a></li>
                        <li><a href="<?php echo URLROOT; ?>/ai" class="text-gray-300 hover:text-white transition">Tư vấn sức khỏe AI</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên hệ</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>
                            <i class="fa-solid fa-location-dot mr-2"></i> 
                            <a href="https://maps.google.com/?q=Số+3,+Vũ+Công+Đán,+P.Tứ+Minh,+Hải+Phòng" target="_blank" class="hover:text-white hover:underline transition">Số 3, Vũ Công Đán, P.Tứ Minh, Hải Phòng</a>
                        </li>
                        <li><i class="fa-solid fa-phone mr-2"></i> <a href="tel:0947647052" class="hover:text-white hover:underline transition">0947647052</a></li>
                        <li><i class="fa-solid fa-envelope mr-2"></i> <a href="mailto:nmtvp11223311@gmail.com" class="hover:text-white hover:underline transition">nmtvp11223311@gmail.com</a></li>
                    </ul>
                </div>
                <!-- Cột Bản đồ -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Bản đồ</h3>
                    <div class="w-full h-40 rounded-xl overflow-hidden border border-gray-700 shadow-inner">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3725.302322316239!2d106.29749591493181!3d20.94038448604618!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31359b3bc9190173%3A0x64e62ad783516086!2zMyBWxakgQ8O0bmcgxJDDoW4sIFThu6kgTWluaCwgSOG6o2kgRMawxqFuZywgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> PETSHOP. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JS Scripts -->
    <script src="<?php echo URLROOT; ?>/public/js/main.js"></script>
    
    <!-- Scroll Reveal Script moved to footer -->
    <script>
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 100;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }
        window.addEventListener("scroll", reveal);
        window.addEventListener("resize", reveal);
        // Trigger ngay lập tức và sau khi DOM load
        reveal(); 
        document.addEventListener("DOMContentLoaded", reveal);
    </script>

    <!-- Social Live Chat Widgets -->
    <div class="fixed bottom-6 left-6 z-[9998] flex flex-col gap-3">
        <!-- Zalo Button -->
        <a href="https://zalo.me/0947647052" target="_blank" rel="noopener noreferrer" 
           class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-[0_0_15px_rgba(59,130,246,0.6)] hover:scale-110 active:scale-95 transition-all duration-300 group relative">
            <span class="absolute right-full mr-3 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap shadow-md">Chat Zalo</span>
            <div class="font-bold text-lg leading-none tracking-tighter w-full text-center mt-[-2px]">Zalo</div>
            <span class="absolute inset-0 rounded-full bg-blue-500 animate-ping opacity-30"></span>
        </a>
        
        <!-- Messenger Button -->
        <a href="https://www.facebook.com/nmtuan2004" target="_blank" rel="noopener noreferrer" 
           class="w-12 h-12 bg-gradient-to-tr from-blue-600 to-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-[0_0_15px_rgba(79,70,229,0.6)] hover:scale-110 active:scale-95 transition-all duration-300 group relative">
            <span class="absolute right-full mr-3 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap shadow-md">Messenger</span>
            <i class="fa-brands fa-facebook-messenger text-2xl"></i>
        </a>
    </div>

    <!-- Global AI Assistant Chat Widget (Always visible) -->
    <div x-data="aiChatbot(<?php echo isLoggedIn() ? 'true' : 'false'; ?>)" class="fixed bottom-6 right-6 z-[9999]">
        
        <!-- Toggle Button with Pulsing Glow -->
        <button @click="toggleChat()" 
                class="w-14 h-14 bg-gradient-to-r from-primary to-indigo-600 text-white rounded-full flex items-center justify-center shadow-[0_0_20px_rgba(79,70,229,0.4)] hover:shadow-[0_0_30px_rgba(79,70,229,0.7)] hover:scale-110 active:scale-95 transition-all duration-300 relative group">
            <span class="absolute inset-0 rounded-full bg-primary/20 animate-ping opacity-75"></span>
            
            <!-- Chat icon (shows when closed) -->
            <span x-show="!open" class="text-xl relative z-10 transition duration-300">
                <i class="fa-solid fa-paw"></i>
            </span>
            
            <!-- Close icon (shows when open) -->
            <span x-show="open" class="text-xl relative z-10 transition duration-300" x-cloak>
                <i class="fa-solid fa-xmark"></i>
            </span>
            
            <!-- Tooltip -->
            <span class="absolute right-16 bg-slate-900 text-white text-[10px] font-black tracking-wider uppercase px-3 py-1.5 rounded-xl shadow-lg border border-slate-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none">
                Chat Với Pawsy 🐾
            </span>
        </button>

        <!-- Chat Window (Glassmorphic & Sleek Dark theme) -->
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="absolute bottom-[72px] right-0 w-[340px] h-[450px] bg-slate-950/95 backdrop-blur-xl border border-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col z-50">
            
            <!-- Header -->
            <div class="px-5 py-4 bg-slate-900/80 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full absolute -top-0.5 -right-0.5 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-r from-primary to-indigo-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-robot text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-white tracking-tight">Trợ Lý Ảo Pawsy</h4>
                        <p class="text-[9px] text-emerald-400 font-bold tracking-wider uppercase flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block animate-ping"></span> Trực tuyến
                        </p>
                    </div>
                </div>
                <button @click="open = false" class="text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-minus text-sm"></i>
                </button>
            </div>

            <!-- Active Chat Mode (Visible if logged in) -->
            <div x-show="loggedIn" class="flex-1 flex flex-col overflow-hidden">
                <!-- Messages Area -->
                <div id="ai-chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 scroll-smooth">
                    <!-- Welcome Message -->
                    <div class="flex items-start gap-2.5 max-w-[85%]">
                        <div class="w-7 h-7 rounded-lg bg-indigo-950 border border-indigo-800 text-indigo-400 flex-shrink-0 flex items-center justify-center text-[10px]">
                            <i class="fa-solid fa-paw"></i>
                        </div>
                        <div class="bg-slate-900 text-slate-200 text-[11px] py-2 px-3 rounded-2xl rounded-tl-none border border-slate-800 leading-relaxed font-medium">
                            Dạ thưa Sen! Em là Pawsy 🐾 - trợ lý ảo AI của PetShop đây ạ. Sen cần em tư vấn dịch vụ Spa, khách sạn thú cưng hay mua thức ăn hạt gì không ạ?
                        </div>
                    </div>

                    <template x-for="(msg, index) in messages" :key="index">
                        <div class="flex items-start gap-2.5 max-w-[85%]" 
                             :class="msg.sender === 'user' ? 'ml-auto justify-end flex-row-reverse' : ''">
                            
                            <!-- Avatar icon -->
                            <div class="w-7 h-7 rounded-lg flex-shrink-0 flex items-center justify-center text-[10px]"
                                 :class="msg.sender === 'user' ? 'bg-indigo-600 text-white' : 'bg-indigo-950 border border-indigo-800 text-indigo-400'">
                                <i class="fa-solid" :class="msg.sender === 'user' ? 'fa-user' : 'fa-paw'"></i>
                            </div>
                            
                            <!-- Message bubble -->
                            <div class="text-[11px] py-2 px-3 rounded-2xl leading-relaxed font-medium [&_a]:text-blue-300 [&_a]:underline [&_a]:font-bold hover:[&_a]:text-blue-200"
                                 :class="msg.sender === 'user' ? 'bg-gradient-to-r from-primary to-indigo-600 text-white rounded-tr-none' : 'bg-slate-900 text-slate-200 rounded-tl-none border border-slate-800'">
                                <span x-html="formatMessage(msg.text)"></span>
                            </div>
                        </div>
                    </template>

                    <!-- Loading Indicator -->
                    <template x-if="loading">
                        <div class="flex items-start gap-2.5 max-w-[85%]">
                            <div class="w-7 h-7 rounded-lg bg-indigo-950 border border-indigo-800 text-indigo-400 flex-shrink-0 flex items-center justify-center text-[10px]">
                                <i class="fa-solid fa-paw"></i>
                            </div>
                            <div class="bg-slate-900 border border-slate-800 text-slate-400 py-2 px-3 rounded-2xl rounded-tl-none flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0.1s"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0.2s"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0.3s"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input area -->
                <form @submit.prevent="sendMessage()" class="p-2.5 bg-slate-905 border-t border-slate-800 flex gap-2">
                    <input type="text" x-model="inputText" :disabled="loading"
                           placeholder="Hỏi Pawsy..." 
                           class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 disabled:opacity-50 transition shadow-inner">
                    <button type="submit" :disabled="loading || !inputText.trim()"
                            class="w-8 h-8 bg-gradient-to-r from-primary to-indigo-600 text-white rounded-xl flex items-center justify-center hover:scale-105 active:scale-95 disabled:opacity-50 transition shadow-md">
                        <i class="fa-solid fa-paper-plane text-[10px]"></i>
                    </button>
                </form>
            </div>

            <!-- Locked Mode (Visible if guests / not logged in) -->
            <div x-show="!loggedIn" class="flex-1 flex flex-col items-center justify-center p-6 text-center space-y-6" x-cloak>
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-primary/10 to-secondary/10 border border-slate-800/80 flex items-center justify-center text-3xl shadow-[0_0_30px_rgba(79,70,229,0.15)] animate-bounce">
                    <i class="fa-solid fa-lock text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-white tracking-tight mb-2">Chào Sen ơi! Em là Pawsy 🐾</h4>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed max-w-[240px] px-2">
                        Hãy đăng nhập hoặc đăng ký tài khoản để nhận đặc quyền trò chuyện và tư vấn sức khỏe miễn phí cùng Pawsy nhé!
                    </p>
                </div>
                <div class="flex flex-col w-full gap-2.5 px-2">
                    <a href="<?php echo URLROOT; ?>/auth/login" class="w-full py-3 bg-gradient-to-r from-primary to-indigo-600 hover:shadow-[0_0_20px_rgba(79,70,229,0.4)] text-white text-xs font-black rounded-xl transition-all duration-300 text-center flex items-center justify-center gap-2 hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i> Đăng Nhập Ngay
                    </a>
                    <a href="<?php echo URLROOT; ?>/auth/register" class="w-full py-3 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-200 hover:text-white text-xs font-black rounded-xl transition-all duration-300 text-center flex items-center justify-center gap-2 hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fa-solid fa-user-plus text-xs text-secondary animate-pulse"></i> Tạo Tài Khoản Mới
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Alpine.js AI Chatbot Script -->
    <script>
        function aiChatbot(isLoggedIn) {
            const storageKey = 'pawsy_chat_history_<?php echo session_id(); ?>';
            return {
                loggedIn: isLoggedIn,
                open: false,
                loading: false,
                inputText: '',
                messages: [],

                init() {
                    // Clean up any stale chat histories from previous sessions to guarantee a fresh start
                    for (let i = sessionStorage.length - 1; i >= 0; i--) {
                        const key = sessionStorage.key(i);
                        if (key && key.startsWith('pawsy_chat_history_') && key !== storageKey) {
                            sessionStorage.removeItem(key);
                        }
                    }

                    if (this.loggedIn) {
                        const saved = sessionStorage.getItem(storageKey);
                        if (saved) {
                            this.messages = JSON.parse(saved);
                        }
                    }
                },

                formatMessage(text) {
                    if (!text) return '';
                    let formatted = text;
                    // Replace bold
                    formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
                    // Replace links (with optional space between ] and ()
                    formatted = formatted.replace(/\[(.*?)\]\s*\((.*?)\)/g, '<a href="$2" target="_blank">$1</a>');
                    // Replace newlines
                    formatted = formatted.replace(/\r?\n/g, '<br>');
                    return formatted;
                },

                toggleChat() {
                    this.open = !this.open;
                    if (this.open && this.loggedIn) {
                        this.scrollToBottom();
                    }
                },

                async sendMessage() {
                    if (!this.loggedIn || !this.inputText.trim() || this.loading) return;

                    const userMsg = this.inputText.trim();
                    this.inputText = '';
                    this.messages.push({ sender: 'user', text: userMsg });
                    this.loading = true;
                    this.scrollToBottom();

                    try {
                        const response = await fetch('<?php echo URLROOT; ?>/ai/chat', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ 
                                message: userMsg,
                                history: this.messages 
                            })
                        });
                        const data = await response.json();
                        if (data.success && data.reply) {
                            this.messages.push({ sender: 'ai', text: data.reply });
                        } else {
                            this.messages.push({ sender: 'ai', text: 'Dạ Sen ơi, kết nối của em bị gián đoạn một chút ạ. Sen vui lòng thử lại sau nhé! 🐾' });
                        }
                    } catch (e) {
                        this.messages.push({ sender: 'ai', text: 'Dạ Sen ơi, kết nối của em bị gián đoạn một chút ạ. Sen vui lòng thử lại sau nhé! 🐾' });
                    } finally {
                        this.loading = false;
                        sessionStorage.setItem(storageKey, JSON.stringify(this.messages));
                        this.scrollToBottom();
                    }
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const el = document.getElementById('ai-chat-messages');
                        if (el) {
                            el.scrollTop = el.scrollHeight;
                        }
                    }, 50);
                }
            }
        }
    </script>

    <!-- Toast Notification System -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 pointer-events-none"></div>

    <style>
        .toast {
            min-width: 250px;
            max-width: 350px;
            pointer-events: auto;
            transform: translateX(120%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastContainer = document.getElementById('toast-container');
        
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            
            let icon = 'fa-check-circle';
            let bgClass = 'bg-white border-l-4 border-green-500 text-gray-800';
            let iconColor = 'text-green-500';
            
            if (type === 'error') {
                icon = 'fa-circle-xmark';
                bgClass = 'bg-white border-l-4 border-red-500 text-gray-800';
                iconColor = 'text-red-500';
            } else if (type === 'warning') {
                icon = 'fa-triangle-exclamation';
                bgClass = 'bg-white border-l-4 border-yellow-500 text-gray-800';
                iconColor = 'text-yellow-500';
            }

            toast.className = `toast flex items-center p-4 rounded-md shadow-lg ${bgClass}`;
            toast.innerHTML = `
                <i class="fa-solid ${icon} ${iconColor} text-xl mr-3"></i>
                <div class="font-medium text-sm flex-1 leading-snug">${message}</div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition ml-3">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            
            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            // Auto remove after 4s
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400); // Wait for transition
            }, 4000);
        }

        // Việt hóa thông báo lỗi HTML5 mặc định của trình duyệt cho tất cả các form
        document.addEventListener('invalid', (e) => {
            const target = e.target;
            if (target.tagName === 'INPUT' || target.tagName === 'SELECT' || target.tagName === 'TEXTAREA') {
                if (target.validity.valueMissing) {
                    target.setCustomValidity('Vui lòng điền vào trường này.');
                } else if (target.validity.typeMismatch && target.type === 'email') {
                    target.setCustomValidity('Vui lòng nhập đúng định dạng email (VD: ten@gmail.com).');
                } else if (target.validity.rangeUnderflow) {
                    target.setCustomValidity(`Giá trị phải lớn hơn hoặc bằng ${target.min}.`);
                } else if (target.validity.rangeOverflow) {
                    target.setCustomValidity(`Giá trị phải nhỏ hơn hoặc bằng ${target.max}.`);
                } else if (target.validity.stepMismatch) {
                    target.setCustomValidity('Giá trị không hợp lệ.');
                }
            }
        }, true);

        document.addEventListener('input', (e) => {
            e.target.setCustomValidity('');
        });
        
        document.addEventListener('change', (e) => {
            e.target.setCustomValidity('');
        });

        // Check for hidden toast elements rendered by PHP flash()
        const hiddenToasts = document.querySelectorAll('.custom-toast');
        hiddenToasts.forEach(t => {
            showToast(t.dataset.message, t.dataset.type);
            t.remove();
        });
    });
    </script>
</body>
</html>
