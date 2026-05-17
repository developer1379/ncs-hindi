<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 | Locked Channel - NCS Hindi</title>
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

        /* Ambient glowing orbs */
        .glowing-orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,158,11,0.06) 0%, rgba(220,38,38,0) 70%);
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center overflow-hidden p-6">

    <!-- Orbs -->
    <div class="glowing-orb top-1/4 left-1/4"></div>
    <div class="glowing-orb bottom-1/4 right-1/4"></div>

    <div class="relative z-10 w-full max-w-md text-center">
        <!-- Shield Lock Icon Container -->
        <div class="relative w-40 h-40 mx-auto mb-10">
            <div class="absolute -inset-4 bg-amber-500/10 blur-3xl rounded-full opacity-60"></div>
            <!-- Circle Badge -->
            <div class="relative w-full h-full bg-zinc-900 border border-zinc-800 rounded-full flex items-center justify-center shadow-2xl">
                <!-- Inner Label -->
                <div class="w-16 h-16 bg-black border-2 border-zinc-800 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-lock-open text-3xl text-amber-500/40"></i>
                </div>
            </div>
            <!-- Absolute floating lock indicator -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 scale-125 text-red-500">
                <i class="fa-solid fa-shield text-5xl opacity-80"></i>
                <i class="fa-solid fa-lock text-lg absolute inset-0 flex items-center justify-center text-white mt-1"></i>
            </div>
        </div>

        <!-- 403 Headline -->
        <h1 class="font-brand text-8xl font-black text-white italic tracking-tighter uppercase leading-none mb-3">
            403
        </h1>
        <p class="text-[10px] text-amber-500 font-black uppercase tracking-[0.3em] mb-8">
            Access Denied / Private Channel
        </p>

        <!-- Glass Card Details -->
        <div class="bg-zinc-900/40 border border-zinc-800/80 backdrop-blur-md rounded-3xl p-6 mb-8 text-zinc-400 text-sm leading-relaxed font-medium">
            This channel is locked. You do not have the necessary creator clearances to access this room. Please login or request permission.
        </div>

        <!-- Direct Navigation Buttons -->
        <div class="flex flex-col gap-3">
            <a href="/login" class="w-full bg-white text-black py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-amber-500 hover:text-white transition-all duration-300 shadow-xl shadow-white/5">
                <i class="fa-solid fa-right-to-bracket text-sm"></i> Access Studio / Login
            </a>
            <a href="/" class="w-full bg-zinc-900 hover:bg-zinc-800 border border-zinc-800 text-zinc-400 hover:text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2 transition-all">
                <i class="fa-solid fa-house text-sm"></i> Back to Feed
            </a>
        </div>
    </div>
</body>
</html>
