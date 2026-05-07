<!-- Chatbot Container -->
<div class="fixed bottom-6 right-6 z-[9999]" id="chatbot-wrapper" x-data="{ open: false, minimized: true }">
    <!-- Chat Toggle Button (Visible when minimized) -->
    <button @click="open = true; minimized = false" 
            x-show="!open"
            class="w-16 h-16 bg-medical-600 rounded-2xl shadow-2xl shadow-medical-600/30 flex items-center justify-center text-white hover:bg-medical-700 hover:scale-110 transition-all duration-300 group">
        <i class="fa-solid fa-comment-medical text-2xl group-hover:rotate-12"></i>
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-accent rounded-full border-4 border-white animate-pulse"></span>
    </button>

    <!-- Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="w-[380px] h-[550px] bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 flex flex-col overflow-hidden">
        
        <!-- Header -->
        <div class="p-6 bg-medical-900 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-robot text-accent"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">HealthSys AI</h4>
                    <p class="text-[10px] font-bold text-accent uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span> Online
                    </p>
                </div>
            </div>
            <button @click="open = false" id="closeBtn" class="text-white/50 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50" id="conversationContainer">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg bg-medical-100 flex items-center justify-center text-medical-600 flex-shrink-0">
                    <i class="fa-solid fa-robot text-xs"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[80%]">
                    <p class="text-xs font-medium text-slate-600 leading-relaxed">Hello! I'm your HealthSys assistant. How can I help you with your medical journey today?</p>
                </div>
            </div>
            
            <!-- Typing Indicator -->
            <div id="typing" class="flex gap-3 hide">
                <div class="w-8 h-8 rounded-lg bg-medical-100 flex items-center justify-center text-medical-600 flex-shrink-0">
                    <i class="fa-solid fa-robot text-xs"></i>
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-slate-100">
                    <div class="flex gap-1">
                        <span class="w-1.5 h-1.5 bg-medical-400 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-medical-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-1.5 h-1.5 bg-medical-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-white border-t border-slate-100">
            <div class="relative flex items-center">
                <input type="text" 
                       id="userInput"
                       placeholder="Ask me anything..." 
                       class="w-full pl-4 pr-12 py-3 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-medical-600/20 transition-all"
                       autocomplete="off">
                <button id="sendBtn" class="absolute right-2 w-8 h-8 bg-medical-600 text-white rounded-lg flex items-center justify-center hover:bg-medical-700 transition-all">
                    <i class="fa-solid fa-paper-plane text-[10px]"></i>
                </button>
            </div>
            <p class="mt-3 text-[9px] text-center font-bold text-slate-300 uppercase tracking-widest">Powered by HealthSys Intelligence</p>
        </div>
    </div>
</div>

<style>
    .hide { display: none !important; }
    
    .bg-medical-900 { background-color: #03045E; }
    .bg-medical-700 { background-color: #0077B6; }
    .bg-medical-600 { background-color: #023E8A; }
    .bg-medical-400 { background-color: #00B4D8; }
    .bg-medical-100 { background-color: #CAF0F8; }
    .text-medical-600 { color: #023E8A; }
    .bg-accent { background-color: #00B4D8; }
    .text-accent { color: #90E0EF; }
    
    #conversationContainer::-webkit-scrollbar {
        width: 4px;
    }
    #conversationContainer::-webkit-scrollbar-track {
        background: transparent;
    }
    #conversationContainer::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>
