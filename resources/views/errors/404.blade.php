<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Lost in the Beat - NCS Hindi</title>
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

        /* Ambient Glowing Orbs */
        .glowing-orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, rgba(220,38,38,0) 70%);
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }

        /* Broken Equalizer Animation */
        .eq-bar {
            width: 4px;
            background: #f59e0b;
            border-radius: 2px;
            height: 12px;
        }
        
        .eq-flat-1 { animation: flat-pulse 2s infinite ease-in-out; }
        .eq-flat-2 { animation: flat-pulse 2.3s infinite ease-in-out; }
        .eq-flat-3 { animation: flat-pulse 1.8s infinite ease-in-out; }
        .eq-flat-4 { animation: flat-pulse 2.5s infinite ease-in-out; }

        @keyframes flat-pulse {
            0%, 100% { height: 12px; opacity: 0.3; }
            50% { height: 4px; opacity: 0.1; }
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center overflow-hidden p-6">

    <!-- Orbs -->
    <div class="glowing-orb top-1/4 left-1/4"></div>
    <div class="glowing-orb bottom-1/4 right-1/4"></div>

    <div class="relative z-10 w-full max-w-md text-center">
        <!-- Equalizer Disc Icon Container -->
        <div class="relative w-40 h-40 mx-auto mb-10">
            <div class="absolute -inset-4 bg-amber-500/10 blur-3xl rounded-full opacity-60"></div>
            <!-- Vinyl Record -->
            <div class="relative w-full h-full bg-zinc-900 border border-zinc-800 rounded-full flex items-center justify-center shadow-2xl">
                <!-- Inner Label -->
                <div class="w-16 h-16 bg-black border-2 border-zinc-800 rounded-full flex items-center justify-center">
                    <!-- Spinning broken link indicator -->
                    <i class="fa-solid fa-compact-disc text-3xl text-amber-500/40 animate-pulse"></i>
                </div>
            </div>
            <!-- Floating needle/arm indicating track ended -->
            <div class="absolute top-4 right-4 w-12 h-20 origin-top-right rotate-45 text-red-500 opacity-60">
                <i class="fa-solid fa-sliders text-2xl"></i>
            </div>
        </div>

        <!-- 404 Headline -->
        <h1 class="font-brand text-8xl font-black text-white italic tracking-tighter uppercase leading-none mb-3">
            404
        </h1>
        <p class="text-[10px] text-amber-500 font-black uppercase tracking-[0.3em] mb-8">
            Track Lost / Page Not Found
        </p>

        <!-- Glass Card Details -->
        <div class="bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-md rounded-3xl p-6 mb-8 text-zinc-400 text-sm leading-relaxed font-medium">
            The page you are looking for has been moved, deleted, or never existed in our music library. Let's get you back on beat!
            
            <!-- Broken soundwave visualizer decoration -->
            <div class="flex items-center justify-center gap-1.5 mt-6">
                <span class="eq-bar eq-flat-1"></span>
                <span class="eq-bar eq-flat-2"></span>
                <span class="eq-bar eq-flat-3 bg-red-600"></span>
                <span class="w-8 h-[2px] bg-red-600/30 rounded"></span>
                <span class="eq-bar eq-flat-4 bg-red-600"></span>
                <span class="eq-bar eq-flat-2"></span>
                <span class="eq-bar eq-flat-1"></span>
            </div>
        </div>

        <!-- Direct Navigation Buttons -->
        <div class="flex flex-col gap-3">
            <a href="/" class="w-full bg-white text-black py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-amber-500 hover:text-white transition-all duration-300 shadow-xl shadow-white/5">
                <i class="fa-solid fa-house text-sm"></i> Back to Feed
            </a>
            <a href="/vault/streams" class="w-full bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-zinc-400 hover:text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2 transition-all">
                <i class="fa-solid fa-box-open text-sm"></i> Music Library
            </a>
        </div>
    </div>
</body>
</html>
