@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Welcome Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="welcome-content">
                    <h1 class="welcome-title">
                        <i class="fas fa-hand-wave"></i>
                        Selamat Datang di SISKA
                    </h1>
                    <p class="welcome-subtitle">Sistem Informasi Karyawan</p>
                    @auth
                        <p class="user-greeting">Halo, <strong>{{ Auth::user()->nama_kry }}</strong> 👋</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Clock Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="clock-card">
                <div class="clock-container">
                    <div class="date-display" id="dateDisplay"></div>
                    <div class="time-display" id="timeDisplay"></div>
                    <div class="day-display" id="dayDisplay"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- AI Chat Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="ai-card">

                {{-- Header --}}
                <div class="ai-header">
                    <div class="ai-header-left">
                        <div class="ai-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div>
                            <div class="ai-title">Asisten SISKA</div>
                            <div class="ai-subtitle">Tanya seputar data karyawan</div>
                        </div>
                    </div>
                    <div class="ai-status">
                        <span class="status-dot"></span>
                        <span class="status-text">Online</span>
                    </div>
                </div>

                {{-- Shortcut Buttons --}}
                <div class="ai-shortcuts">
                    @foreach ($shortcuts as $s)
                        <button class="shortcut-btn" data-tanya="{{ $s['tanya'] }}">
                            <i class="fas fa-{{ $s['icon'] }}"></i>
                            {{ $s['label'] }}
                        </button>
                    @endforeach
                </div>

                {{-- Chat Area --}}
                <div class="ai-chat-area" id="aiChatArea">
                    {{-- Pesan default --}}
                    <div class="chat-bubble bot-bubble intro-bubble">
                        <div class="bubble-avatar"><i class="fas fa-robot"></i></div>
                        <div class="bubble-content">
                            Halo! Saya asisten SISKA. Kamu bisa tanya saya tentang data karyawan,
                            jumlah per departemen, wilayah kerja, jenis kontrak, dan lainnya.
                            Silakan klik shortcut di atas atau ketik pertanyaanmu.
                        </div>
                    </div>
                </div>

                {{-- Input Area --}}
                <div class="ai-input-area">
                    <input
                        type="text"
                        id="aiInput"
                        class="ai-input"
                        placeholder="Contoh: Berapa total karyawan aktif?"
                        maxlength="500"
                        autocomplete="off"
                    />
                    <button class="ai-send-btn" id="aiSendBtn" title="Kirim">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Landscape Photo Section --}}
    <div class="row">
        <div class="col-12">
            <div class="photo-card">
                <div class="photo-container">
                    <img
                        src="{{ asset('images/hrdlandscape.png') }}"
                        alt="Landscape"
                        class="landscape-photo"
                        id="landscapePhoto"
                    >
                    <div class="photo-overlay">
                        <div class="photo-caption">
                            <i class="fas fa-mountain"></i>
                            <span>Pemandangan Inspirasi Hari Ini</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
/* ── Welcome Card ─────────────────────────────────────── */
.welcome-card {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    border-radius: 1rem;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);
    color: white;
    position: relative;
    overflow: hidden;
}
.welcome-card::before {
    content: '';
    position: absolute;
    top: -50%; right: -20%;
    width: 300px; height: 300px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}
.welcome-card::after {
    content: '';
    position: absolute;
    bottom: -30%; left: -10%;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
}
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-20px); }
}
.welcome-content { position: relative; z-index: 1; }
.welcome-title {
    font-size: 2.5rem; font-weight: 700;
    margin-bottom: 0.5rem; margin-left: -15px;
    display: flex; align-items: center; gap: 1rem;
}
.welcome-title i { animation: wave 2s ease-in-out infinite; }
@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25%       { transform: rotate(20deg); }
    75%       { transform: rotate(-20deg); }
}
.welcome-subtitle { font-size: 1.25rem; margin-bottom: 1rem; opacity: .9; }
.user-greeting    { font-size: 1.1rem; margin: 0; opacity: .95; }

/* ── Clock Card ───────────────────────────────────────── */
.clock-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
    border: 1px solid #e5e7eb;
}
.clock-container { text-align: center; }
.date-display { font-size: 1.5rem; color: #6b7280; margin-bottom: 1rem; font-weight: 500; }
.time-display {
    font-size: 4rem; font-weight: 700; color: #2563eb;
    font-family: 'Courier New', monospace;
    letter-spacing: .05em;
    text-shadow: 2px 2px 4px rgba(37,99,235,.1);
}
.day-display { font-size: 1.25rem; color: #1f2937; margin-top: .5rem; font-weight: 600; }

/* ── AI Card ──────────────────────────────────────────── */
.ai-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    animation: fadeInUp .6s ease-out .3s both;
}

/* Header */
.ai-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
}
.ai-header-left { display: flex; align-items: center; gap: .875rem; }
.ai-avatar {
    width: 44px; height: 44px;
    background: rgba(255,255,255,.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}
.ai-title    { font-weight: 700; font-size: 1rem; }
.ai-subtitle { font-size: .8rem; opacity: .85; margin-top: 1px; }
.ai-status   { display: flex; align-items: center; gap: .4rem; font-size: .8rem; opacity: .9; }
.status-dot  {
    width: 8px; height: 8px;
    background: #4ade80;
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .6; transform: scale(.85); }
}

/* Shortcuts */
.ai-shortcuts {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f3f4f6;
    background: #f9fafb;
}
.shortcut-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .4rem .875rem;
    border-radius: 2rem;
    border: 1.5px solid #2563eb;
    background: white;
    color: #2563eb;
    font-size: .8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
}
.shortcut-btn:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37,99,235,.25);
}
.shortcut-btn i { font-size: .75rem; }

/* Chat Area */
.ai-chat-area {
    padding: 1.25rem 1.5rem;
    min-height: 160px;
    max-height: 340px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: .875rem;
    scroll-behavior: smooth;
}
.ai-chat-area::-webkit-scrollbar { width: 4px; }
.ai-chat-area::-webkit-scrollbar-track { background: transparent; }
.ai-chat-area::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

/* Bubbles */
.chat-bubble {
    display: flex;
    gap: .625rem;
    align-items: flex-start;
    animation: bubbleIn .25s ease-out;
}
@keyframes bubbleIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.bubble-avatar {
    width: 30px; height: 30px; flex-shrink: 0;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
}
.bot-bubble .bubble-avatar  { background: #eff6ff; color: #2563eb; }
.user-bubble .bubble-avatar { background: #f3f4f6; color: #6b7280; order: 2; }
.bubble-content {
    padding: .625rem .875rem;
    border-radius: .75rem;
    font-size: .875rem;
    line-height: 1.55;
    max-width: 82%;
    white-space: pre-wrap;
    word-break: break-word;
}
.bot-bubble .bubble-content {
    background: #f0f4ff;
    color: #1e3a5f;
    border-bottom-left-radius: .25rem;
}
.user-bubble {
    flex-direction: row-reverse;
}
.user-bubble .bubble-content {
    background: #2563eb;
    color: white;
    border-bottom-right-radius: .25rem;
}

/* Typing indicator */
.typing-bubble .bubble-content {
    background: #f0f4ff;
    color: #6b7280;
    font-style: italic;
    display: flex;
    align-items: center;
    gap: .35rem;
}
.typing-dots span {
    display: inline-block;
    width: 6px; height: 6px;
    background: #6b7280;
    border-radius: 50%;
    animation: dot-bounce .8s ease-in-out infinite;
}
.typing-dots span:nth-child(2) { animation-delay: .15s; }
.typing-dots span:nth-child(3) { animation-delay: .3s; }
@keyframes dot-bounce {
    0%, 80%, 100% { transform: translateY(0); }
    40%            { transform: translateY(-6px); }
}

/* Input Area */
.ai-input-area {
    display: flex;
    gap: .5rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #f3f4f6;
    background: #fafafa;
}
.ai-input {
    flex: 1;
    border: 1.5px solid #e5e7eb;
    border-radius: .625rem;
    padding: .625rem 1rem;
    font-size: .875rem;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    background: white;
    color: #1f2937;
}
.ai-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}
.ai-input::placeholder { color: #9ca3af; }
.ai-send-btn {
    width: 42px; height: 42px; flex-shrink: 0;
    border: none;
    border-radius: .625rem;
    background: #2563eb;
    color: white;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
    transition: background .2s, transform .15s;
}
.ai-send-btn:hover   { background: #1d4ed8; transform: scale(1.05); }
.ai-send-btn:active  { transform: scale(.95); }
.ai-send-btn:disabled { background: #93c5fd; cursor: not-allowed; transform: none; }

/* ── Photo Card ───────────────────────────────────────── */
.photo-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
    border: 1px solid #e5e7eb;
}
.photo-container {
    position: relative; width: 100%; height: 500px; overflow: hidden;
}
.landscape-photo {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .3s ease;
}
.photo-container:hover .landscape-photo { transform: scale(1.05); }
.photo-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(0,0,0,.7), transparent);
    padding: 2rem; opacity: 0; transition: opacity .3s ease;
}
.photo-container:hover .photo-overlay { opacity: 1; }
.photo-caption {
    color: white; font-size: 1.25rem; font-weight: 600;
    display: flex; align-items: center; gap: .75rem;
}
.photo-caption i { font-size: 1.5rem; }

/* ── Animations ───────────────────────────────────────── */
.welcome-card,
.clock-card,
.photo-card { animation: fadeInUp .6s ease-out; }
.clock-card  { animation-delay: .2s; }
.photo-card  { animation-delay: .5s; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Responsive ───────────────────────────────────────── */
@media (max-width: 768px) {
    .welcome-title   { font-size: 1.75rem; }
    .welcome-subtitle{ font-size: 1rem; }
    .user-greeting   { font-size: .95rem; }
    .time-display    { font-size: 2.5rem; }
    .date-display    { font-size: 1.1rem; }
    .day-display     { font-size: 1rem; }
    .photo-container { height: 300px; }
    .welcome-card, .clock-card { padding: 1.5rem; }
    .ai-shortcuts    { padding: .75rem 1rem; }
    .ai-chat-area    { padding: 1rem; max-height: 260px; }
    .ai-input-area   { padding: .75rem 1rem; }
    .shortcut-btn    { font-size: .75rem; padding: .35rem .75rem; }
}
</style>

<script>
// ── Clock ──────────────────────────────────────────────────────────────────
function updateClock() {
    const now = new Date();
    const h   = String(now.getHours()).padStart(2, '0');
    const m   = String(now.getMinutes()).padStart(2, '0');
    const s   = String(now.getSeconds()).padStart(2, '0');
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    document.getElementById('timeDisplay').textContent = `${h}:${m}:${s}`;
    document.getElementById('dateDisplay').textContent = now.toLocaleDateString('id-ID', {
        year: 'numeric', month: 'long', day: 'numeric'
    });
    document.getElementById('dayDisplay').textContent = days[now.getDay()];
}
updateClock();
setInterval(updateClock, 1000);

// ── Photo fallback ─────────────────────────────────────────────────────────
document.getElementById('landscapePhoto').addEventListener('error', function () {
    this.src = 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1920&q=80';
});

// ── AI Chat ────────────────────────────────────────────────────────────────
const chatArea  = document.getElementById('aiChatArea');
const aiInput   = document.getElementById('aiInput');
const aiSendBtn = document.getElementById('aiSendBtn');
const csrfToken = '{{ csrf_token() }}';
const aiUrl     = '{{ route("ai.tanya") }}';

function scrollBottom() {
    chatArea.scrollTop = chatArea.scrollHeight;
}

function addBubble(teks, tipe) {
    const wrap = document.createElement('div');
    wrap.className = `chat-bubble ${tipe}-bubble`;

    const icon = tipe === 'bot'
        ? '<i class="fas fa-robot"></i>'
        : '<i class="fas fa-user"></i>';

    wrap.innerHTML = `
        <div class="bubble-avatar">${icon}</div>
        <div class="bubble-content">${teks}</div>
    `;
    chatArea.appendChild(wrap);
    scrollBottom();
    return wrap;
}

function addTyping() {
    const wrap = document.createElement('div');
    wrap.className = 'chat-bubble bot-bubble typing-bubble';
    wrap.id = 'typingIndicator';
    wrap.innerHTML = `
        <div class="bubble-avatar"><i class="fas fa-robot"></i></div>
        <div class="bubble-content">
            Sedang memproses
            <span class="typing-dots">
                <span></span><span></span><span></span>
            </span>
        </div>
    `;
    chatArea.appendChild(wrap);
    scrollBottom();
}

function removeTyping() {
    const el = document.getElementById('typingIndicator');
    if (el) el.remove();
}

async function tanya(pertanyaan) {
    pertanyaan = pertanyaan.trim();
    if (!pertanyaan) return;

    // Tampilkan bubble user
    addBubble(pertanyaan, 'user');
    aiInput.value = '';
    aiSendBtn.disabled = true;

    // Tampilkan animasi loading
    addTyping();

    try {
        const res  = await fetch(aiUrl, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ pertanyaan }),
        });

        const data = await res.json();
        removeTyping();
        addBubble(data.jawaban ?? 'Maaf, tidak ada jawaban.', 'bot');

    } catch (e) {
        removeTyping();
        addBubble('Maaf, terjadi kesalahan. Pastikan server AI aktif dan coba lagi.', 'bot');
    } finally {
        aiSendBtn.disabled = false;
        aiInput.focus();
    }
}

// Shortcut buttons
document.querySelectorAll('.shortcut-btn').forEach(btn => {
    btn.addEventListener('click', () => tanya(btn.dataset.tanya));
});

// Send button
aiSendBtn.addEventListener('click', () => tanya(aiInput.value));

// Enter key
aiInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) tanya(aiInput.value);
});
</script>
@endsection