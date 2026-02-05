@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
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
                        <p class="user-greeting">Halo, <strong>{{ Auth::user()->nama_kry }}
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Clock Section -->
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

    <!-- Landscape Photo Section -->
    <div class="row">
        <div class="col-12">
            <div class="photo-card">
                <div class="photo-container">
                    <img src="{{ asset('images/hrdlandscape.png') }}" alt="Landscape" class="landscape-photo" id="landscapePhoto">
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
    /* Welcome Card Styles */
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
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .welcome-title {
        font-size: 2.5rem;
        margin-left: -15px;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .welcome-title i {
        animation: wave 2s ease-in-out infinite;
    }

    @keyframes wave {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(20deg); }
        75% { transform: rotate(-20deg); }
    }

    .welcome-subtitle {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .user-greeting {
        font-size: 1.1rem;
        margin: 0;
        opacity: 0.95;
    }

    /* Clock Card Styles */
    .clock-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .clock-container {
        text-align: center;
    }

    .date-display {
        font-size: 1.5rem;
        color: #6b7280;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .time-display {
        font-size: 4rem;
        font-weight: 700;
        color: #2563eb;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.05em;
        text-shadow: 2px 2px 4px rgba(37, 99, 235, 0.1);
    }

    .day-display {
        font-size: 1.25rem;
        color: #1f2937;
        margin-top: 0.5rem;
        font-weight: 600;
    }

    /* Photo Card Styles */
    .photo-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .photo-container {
        position: relative;
        width: 100%;
        height: 500px;
        overflow: hidden;
    }

    .landscape-photo {
        width: 100%;
        height: 100%;
        object-fit: fit;
        transition: transform 0.3s ease;
    }

    .photo-container:hover .landscape-photo {
        transform: scale(1.05);
    }

    .photo-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        padding: 2rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .photo-container:hover .photo-overlay {
        opacity: 1;
    }

    .photo-caption {
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .photo-caption i {
        font-size: 1.5rem;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .welcome-title {
            font-size: 1.75rem;
        }

        .welcome-subtitle {
            font-size: 1rem;
        }

        .user-greeting {
            font-size: 0.95rem;
        }

        .time-display {
            font-size: 2.5rem;
        }

        .date-display {
            font-size: 1.1rem;
        }

        .day-display {
            font-size: 1rem;
        }

        .photo-container {
            height: 300px;
        }

        .welcome-card {
            padding: 1.5rem;
        }

        .clock-card {
            padding: 1.5rem;
        }
    }

    /* Animation for page load */
    .welcome-card,
    .clock-card,
    .photo-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .clock-card {
        animation-delay: 0.2s;
    }

    .photo-card {
        animation-delay: 0.4s;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    // Clock functionality
    function updateClock() {
        const now = new Date();

        // Format time
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;

        // Format date
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', options);

        // Format day
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dayString = days[now.getDay()];

        // Update display
        document.getElementById('timeDisplay').textContent = timeString;
        document.getElementById('dateDisplay').textContent = dateString;
        document.getElementById('dayDisplay').textContent = dayString;
    }

    // Update clock immediately and then every second
    updateClock();
    setInterval(updateClock, 1000);

    // Fallback image if landscape photo doesn't exist
    document.getElementById('landscapePhoto').addEventListener('error', function() {
        this.src = 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1920&q=80';
    });
</script>
@endsection