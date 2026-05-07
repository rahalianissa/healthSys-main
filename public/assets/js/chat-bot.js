document.addEventListener('DOMContentLoaded', () => {
    "use strict";

    // ===== GEMINI API CONFIG =====
    const GEMINI_CONFIG = {
        apiKey: 'AIzaSyBlpo0hUSD2UO8XDt0_4eoUG1e-MDVKYds', 
        apiUrl: 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
        
        systemPrompt: `You are HealthSys IA, a healthcare assistant for the HealthSys medical platform.

⚠️ CRITICAL SAFETY RULES:
1. If user mentions ANY of these: "heart attack", "chest pain", "can't breathe", "stroke", "severe bleeding", "unconscious", "suicide", "overdose" → IMMEDIATELY respond: "🚨 This is a medical emergency! Call emergency services (190) NOW or go to the nearest emergency room. Do not wait."
2. NEVER give casual advice for emergency symptoms.
3. Always remind users you are NOT a doctor.
4. For non-emergencies, provide helpful information but recommend seeing a doctor.

For normal questions:
- Be friendly, professional and empathetic.
- Keep responses concise (2-3 sentences).
- Use emojis sparingly (1-2 max).
- Always recommend professional medical consultation for persistent symptoms.`
    };

    // ===== CHATBOT ELEMENTS =====
    const els = {
        wrapper: document.getElementById('chatbot-wrapper'),
        conversation: document.getElementById('conversationContainer'),
        userInput: document.getElementById('userInput'),
        sendBtn: document.getElementById('sendBtn'),
        closeBtn: document.getElementById('closeBtn'),
        typing: document.getElementById('typing')
    };

    if (!els.userInput || !els.sendBtn) return;

    const EMERGENCY_KEYWORDS = [
        'heart attack', 'chest pain', 'can\'t breathe', 'cannot breathe', 'difficulty breathing',
        'stroke', 'severe bleeding', 'unconscious', 'suicide', 'overdose', 'poison',
        'severe allergic reaction', 'anaphylaxis', 'seizure', 'convulsion'
    ];

    const localResponses = {
        "hello": "Hello! 👋 How can I assist you with your HealthSys journey today?",
        "hi": "Hi there! 😊 What's on your mind?",
        "headache": "For a mild headache: rest, hydrate, and consider OTC pain relief. If it persists, consult a doctor. 🩺",
        "thank": "You're welcome! 😊 Let me know if you need anything else.",
        "bye": "Take care! 👋 Feel free to come back anytime.",
        "default": "I'm here to help with health questions. What would you like to know?"
    };

    // ===== ADD MESSAGE TO UI =====
    function addMessage(text, sender) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `flex gap-3 ${sender === 'user' ? 'flex-row-reverse' : ''}`;
        
        const avatar = document.createElement('div');
        avatar.className = `w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 ${sender === 'user' ? 'bg-medical-600 text-white' : 'bg-medical-100 text-medical-600'}`;
        avatar.innerHTML = `<i class="fa-solid fa-${sender === 'user' ? 'user' : 'robot'} text-xs"></i>`;
        
        const bubble = document.createElement('div');
        bubble.className = `p-4 rounded-2xl shadow-sm border border-slate-100 max-w-[80%] ${sender === 'user' ? 'bg-medical-600 text-white rounded-tr-none' : 'bg-white text-slate-600 rounded-tl-none'}`;
        
        const content = document.createElement('p');
        content.className = "text-xs font-medium leading-relaxed";
        content.innerHTML = text.replace(/\n/g, '<br>');
        
        bubble.appendChild(content);
        msgDiv.appendChild(avatar);
        msgDiv.appendChild(bubble);
        
        // Insert before typing indicator
        els.conversation.insertBefore(msgDiv, els.typing);
        els.conversation.scrollTop = els.conversation.scrollHeight;
    }

    function checkEmergency(text) {
        const lower = text.toLowerCase();
        for (const keyword of EMERGENCY_KEYWORDS) {
            if (lower.includes(keyword)) return true;
        }
        return false;
    }

    async function callGeminiAPI(userMessage, history = []) {
        if (checkEmergency(userMessage)) {
            return "🚨 **This sounds like a medical emergency!**\n\n**Call emergency services (190) IMMEDIATELY!**\n\nDo not wait. Get help now. I am an AI assistant and cannot provide emergency care.";
        }

        try {
            const contents = [
                { role: 'user', parts: [{ text: GEMINI_CONFIG.systemPrompt }] },
                ...history.map(msg => ({
                    role: msg.sender === 'user' ? 'user' : 'model',
                    parts: [{ text: msg.text }]
                })),
                { role: 'user', parts: [{ text: userMessage }] }
            ];

            const response = await fetch(`${GEMINI_CONFIG.apiUrl}?key=${GEMINI_CONFIG.apiKey}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ contents })
            });

            if (!response.ok) throw new Error('API Error');
            const data = await response.json();
            return data.candidates?.[0]?.content?.parts?.[0]?.text || localResponses.default;
        } catch (error) {
            console.error('Gemini Error:', error);
            return localResponses.default;
        }
    }

    async function handleSend() {
        const text = els.userInput.value.trim();
        if (!text) return;

        addMessage(text, 'user');
        els.userInput.value = '';
        
        els.typing.classList.remove('hide');
        els.conversation.scrollTop = els.conversation.scrollHeight;

        const history = Array.from(els.conversation.querySelectorAll('.flex.gap-3')).slice(-6).map(msg => {
            const isUser = msg.classList.contains('flex-row-reverse');
            return {
                sender: isUser ? 'user' : 'ai',
                text: msg.querySelector('p')?.innerText || ''
            };
        });

        const aiResponse = await callGeminiAPI(text, history);
        
        els.typing.classList.add('hide');
        addMessage(aiResponse, 'ai');
    }

    els.sendBtn.addEventListener('click', handleSend);
    els.userInput.addEventListener('keypress', e => { if (e.key === 'Enter') handleSend(); });
});