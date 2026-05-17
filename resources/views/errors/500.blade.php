<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 | System Jammed - NCS Hindi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=Outfit:wght@900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #050505;
        }

        .font-brand {
            font-family: 'Outfit', sans-serif;
        }

        /* Ambient Alert Orbs */
        .glowing-orb {
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, rgba(220,38,38,0) 70%);
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }

        /* Static wave pulse */
        .static-pulse {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center overflow-hidden p-6">

    <!-- Orbs -->
    <div class="glowing-orb top-1/4 left-1/4"></div>
    <div class="glowing-orb bottom-1/4 right-1/4"></div>

    <div class="relative z-10 w-full max-w-md text-center">
        <!-- Broken Sound Wave / Alert Icon Container -->
        <div class="relative w-40 h-40 mx-auto mb-10 static-pulse">
            <div class="absolute -inset-4 bg-red-600/10 blur-3xl rounded-full opacity-60"></div>
            <!-- Circle Badge -->
            <div class="relative w-full h-full bg-zinc-900 border border-zinc-800 rounded-full flex items-center justify-center shadow-2xl">
                <!-- Inner Label -->
                <div class="w-16 h-16 bg-black border-2 border-zinc-800 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
                </div>
            </div>
        </div>

        <!-- 500 Headline -->
        <h1 class="font-brand text-8xl font-black text-white italic tracking-tighter uppercase leading-none mb-3">
            500
        </h1>
        <p class="text-[10px] text-red-500 font-black uppercase tracking-[0.3em] mb-8">
            System Jammed / Server Overload
        </p>

        <!-- Glass Card Details -->
        <div class="bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-md rounded-3xl p-6 mb-8 text-zinc-400 text-sm leading-relaxed font-medium">
            Our audio engines encountered an unexpected glitch. Our engineers are already tuning the servers to get the stream back online. Please try refreshing.
        </div>

        <!-- Direct Navigation Buttons -->
        <div class="flex flex-col gap-3">
            <button onclick="window.location.reload()" class="w-full bg-white text-black py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-red-600 hover:text-white transition-all duration-300 shadow-xl shadow-white/5">
                <i class="fa-solid fa-rotate text-sm"></i> Retry Stream
            </button>
            <a href="/" class="w-full bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-zinc-400 hover:text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2 transition-all">
                <i class="fa-solid fa-house text-sm"></i> Back to Feed
            </a>
        </div>
    </div>
</body>
</html>
