<x-webapp-layout
    title="{{ $seo['title'] ?? 'NCS Rhythm Tapper | Free Online Music Game' }}"
    description="{{ $seo['description'] ?? 'Play the NCS Rhythm Tapper! A free, online, neon-styled music rhythm game.' }}"
    keywords="{{ $seo['keywords'] ?? 'music game online, rhythm tapper' }}"
>
    {{-- Override layout padding to force fullscreen game --}}
    <style>
        main {
            padding: 0 !important;
            margin: 0 !important;
            overflow: hidden !important;
        }
        /* Mobile header adjustment if needed, but flex-1 handles it */
    </style>

    {{-- Game Wrapper: Full Screen, Full Width, Theme Support --}}
    <div id="game-theme-wrapper" class="w-full h-full flex flex-col transition-colors duration-500 bg-[#0a0a0c] text-white relative overflow-hidden">
        
        {{-- Theme Toggle --}}
        <button id="theme-toggle" class="absolute top-6 right-6 z-50 p-4 rounded-full bg-zinc-800/80 backdrop-blur-md text-amber-500 hover:bg-zinc-700 transition shadow-2xl flex items-center justify-center">
            <i class="fa-solid fa-moon text-xl" id="theme-icon"></i>
        </button>

        {{-- HUD Overlay (Score, Title) --}}
        <div class="absolute top-0 left-0 w-full p-6 z-30 flex justify-between items-start pointer-events-none">
            <div>
                <h1 class="font-brand text-3xl md:text-5xl font-black italic uppercase tracking-tighter title-text drop-shadow-lg">
                    NCS <span class="text-amber-500">Rhythm</span>
                </h1>
                <p class="text-xs md:text-sm text-zinc-300 font-bold tracking-widest mt-1 content-text drop-shadow-md">Tapper Edition</p>
            </div>
            <div class="text-right pointer-events-auto mr-16"> {{-- Margin to avoid theme toggle --}}
                <p class="text-[10px] md:text-xs text-zinc-400 font-black uppercase tracking-widest mb-1 content-text drop-shadow-md">Max Combo</p>
                <p id="high-score" class="text-3xl md:text-4xl font-black text-amber-500 font-brand drop-shadow-lg">0</p>
            </div>
        </div>

        {{-- Game Container - Full Width & Height --}}
        <div id="canvas-container" class="relative w-full flex-1 transition-colors duration-500">
            
            {{-- Start Screen Overlay --}}
            <div id="game-overlay" class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-black/80 backdrop-blur-md transition-opacity duration-300">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-500 to-red-600 rounded-3xl flex items-center justify-center mb-6 shadow-[0_0_50px_rgba(245,158,11,0.5)]">
                    <i class="fa-solid fa-play text-4xl text-white ml-2"></i>
                </div>
                <h2 id="game-status" class="text-4xl md:text-6xl font-black text-white font-brand italic uppercase tracking-widest mb-4 drop-shadow-[0_0_15px_rgba(255,255,255,0.5)] text-center px-4">Ready to Drop</h2>
                <p class="text-zinc-300 text-sm md:text-base font-medium tracking-widest uppercase mb-10 text-center px-4">Tap D, F, J, K or click the lanes</p>
                <button id="start-btn" class="px-12 py-5 bg-amber-500 text-black text-lg font-black uppercase tracking-widest rounded-2xl hover:bg-amber-400 hover:scale-105 transition-all shadow-[0_0_40px_rgba(245,158,11,0.5)] outline-none border-b-4 border-amber-700">
                    Start Game
                </button>
            </div>

            {{-- Canvas --}}
            <canvas id="rhythm-canvas" class="block w-full h-full cursor-crosshair z-10 relative"></canvas>
        </div>

        {{-- SEO Content Section (Visually Hidden but perfectly structured for Search Engines) --}}
        <div class="sr-only">
            <h2>About This Free Online Music Game</h2>
            <p>Welcome to the NCS Rhythm Tapper! This highly engaging, interactive browser game tests your reflexes and rhythm. Catch the neon beats! As the premier platform for royalty-free Hindi music and non-copyright soundtracks, NCS Hindi provides creators studio-quality audio. Our mini-games keep you entertained while discovering new beats. Play our free online rhythm game similar to piano tiles and beat making games.</p>
            <h3>How to Play</h3>
            <ul>
                <li>Watch for the glowing neon tiles falling.</li>
                <li>Wait until tiles enter the Strike Zone at the bottom.</li>
                <li>Tap keys (D, F, J, K) or screen directly!</li>
                <li>Chain Perfect hits for massive combos.</li>
            </ul>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Theme Toggle Logic
            const themeBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const wrapper = document.getElementById('game-theme-wrapper');
            const titleTexts = document.querySelectorAll('.title-text');
            const contentTexts = document.querySelectorAll('.content-text');
            const overlay = document.getElementById('game-overlay');

            let isLightMode = false;

            themeBtn.addEventListener('click', () => {
                isLightMode = !isLightMode;
                if (isLightMode) {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                    wrapper.classList.replace('bg-[#0a0a0c]', 'bg-slate-100');
                    overlay.classList.replace('bg-black/80', 'bg-slate-900/80');
                    titleTexts.forEach(el => el.classList.replace('text-white', 'text-slate-900'));
                    contentTexts.forEach(el => el.classList.replace('text-zinc-300', 'text-slate-600'));
                    contentTexts.forEach(el => el.classList.replace('text-zinc-400', 'text-slate-500'));
                    themeBtn.classList.replace('bg-zinc-800/80', 'bg-white/80');
                    themeBtn.classList.replace('text-amber-500', 'text-amber-600');
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                    wrapper.classList.replace('bg-slate-100', 'bg-[#0a0a0c]');
                    overlay.classList.replace('bg-slate-900/80', 'bg-black/80');
                    titleTexts.forEach(el => el.classList.replace('text-slate-900', 'text-white'));
                    contentTexts.forEach(el => el.classList.replace('text-slate-600', 'text-zinc-300'));
                    contentTexts.forEach(el => el.classList.replace('text-slate-500', 'text-zinc-400'));
                    themeBtn.classList.replace('bg-white/80', 'bg-zinc-800/80');
                    themeBtn.classList.replace('text-amber-600', 'text-amber-500');
                }
            });

            // Canvas & Game Logic
            const canvas = document.getElementById('rhythm-canvas');
            const ctx = canvas.getContext('2d');
            const startBtn = document.getElementById('start-btn');
            const statusText = document.getElementById('game-status');
            const highScoreEl = document.getElementById('high-score');

            function resizeCanvas() {
                const rect = canvas.parentElement.getBoundingClientRect();
                canvas.width = rect.width;
                canvas.height = rect.height;
            }
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            const AudioContext = window.AudioContext || window.webkitAudioContext;
            let audioCtx;
            const laneFrequencies = [261.63, 329.63, 392.00, 523.25]; // C4, E4, G4, C5
            
            function playDrumHit(laneIndex, isPerfect) {
                if (!audioCtx) audioCtx = new AudioContext();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                osc.type = isPerfect ? 'triangle' : 'sine';
                osc.frequency.setValueAtTime(laneFrequencies[laneIndex], audioCtx.currentTime);
                if (isPerfect) osc.frequency.exponentialRampToValueAtTime(40, audioCtx.currentTime + 0.1);
                
                gain.gain.setValueAtTime(isPerfect ? 0.8 : 0.4, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.1);
            }

            let isPlaying = false;
            let animationId;
            let lastTime = 0;
            let score = 0;
            let combo = 0;
            let maxCombo = localStorage.getItem('ncs_rhythm_max_combo') || 0;
            highScoreEl.textContent = maxCombo;
            
            const laneColors = ['#ef4444', '#3b82f6', '#eab308', '#22c55e'];
            const laneKeys = ['D', 'F', 'J', 'K'];
            let activeKeys = [false, false, false, false];

            let notes = [];
            let particles = [];
            let floatingTexts = [];

            // Speed based on screen height to keep gameplay consistent
            let noteSpeed = canvas.height * 0.8; 
            let spawnTimer = 0;
            let spawnInterval = 500;

            function spawnNote() {
                const lane = Math.floor(Math.random() * 4);
                notes.push({
                    lane: lane,
                    y: -100,
                    h: canvas.height * 0.15, // Responsive note height
                    active: true
                });
            }

            function spawnParticles(x, y, color) {
                for (let i = 0; i < 20; i++) {
                    particles.push({
                        x: x,
                        y: y,
                        vx: (Math.random() - 0.5) * 600,
                        vy: (Math.random() - 0.5) * 600 - 200,
                        life: 1.0,
                        color: color,
                        size: Math.random() * 6 + 3
                    });
                }
            }

            function spawnText(text, x, y, color) {
                floatingTexts.push({ text, x, y, life: 1.0, color });
            }

            function hitLane(lane) {
                if (!isPlaying) return;
                
                activeKeys[lane] = true;
                setTimeout(() => activeKeys[lane] = false, 100);

                const laneWidth = canvas.width / 4;
                const laneCenter = lane * laneWidth + laneWidth / 2;
                const STRIKE_ZONE_Y = canvas.height * 0.85;
                const STRIKE_ZONE_H = canvas.height * 0.1;

                let targetNote = null;
                for (let i = 0; i < notes.length; i++) {
                    if (notes[i].lane === lane && notes[i].active) {
                        targetNote = notes[i];
                        break; 
                    }
                }

                if (targetNote) {
                    const noteBottom = targetNote.y + targetNote.h;
                    const zoneCenter = STRIKE_ZONE_Y + STRIKE_ZONE_H/2;
                    const dist = Math.abs(noteBottom - zoneCenter);
                    
                    const hitTolerance = canvas.height * 0.15; // Responsive tolerance
                    
                    if (noteBottom > STRIKE_ZONE_Y - hitTolerance && targetNote.y < STRIKE_ZONE_Y + STRIKE_ZONE_H + hitTolerance) {
                        targetNote.active = false;
                        let hitType = '';
                        let points = 0;
                        let isPerfect = false;
                        
                        if (dist < canvas.height * 0.05) {
                            hitType = 'PERFECT'; points = 100; isPerfect = true; combo++;
                            spawnParticles(laneCenter, zoneCenter, laneColors[lane]);
                        } else if (dist < canvas.height * 0.1) {
                            hitType = 'GOOD'; points = 50; combo++;
                            spawnParticles(laneCenter, zoneCenter, '#ffffff');
                        } else {
                            hitType = 'BAD'; points = 10; combo = 0;
                        }

                        score += points * Math.max(1, Math.floor(combo / 10));
                        spawnText(hitType, laneCenter, STRIKE_ZONE_Y - 40, isPerfect ? laneColors[lane] : '#ffffff');
                        playDrumHit(lane, isPerfect);
                        updateMaxCombo();
                        return;
                    }
                }
                
                combo = 0;
                spawnText('MISS', laneCenter, STRIKE_ZONE_Y - 40, '#ef4444');
            }

            function updateMaxCombo() {
                if (combo > maxCombo) {
                    maxCombo = combo;
                    highScoreEl.textContent = maxCombo;
                    localStorage.setItem('ncs_rhythm_max_combo', maxCombo);
                }
            }

            function update(dt) {
                if (!isPlaying) return;
                
                noteSpeed = canvas.height * 0.8; // Update speed if resized

                spawnTimer += dt * 1000;
                if (spawnTimer > spawnInterval) {
                    spawnNote();
                    spawnTimer = 0;
                    spawnInterval = Math.max(200, spawnInterval - 2); 
                }

                for (let i = notes.length - 1; i >= 0; i--) {
                    let note = notes[i];
                    if (!note.active) continue;
                    
                    note.y += noteSpeed * dt;
                    if (note.y > canvas.height) {
                        note.active = false;
                        combo = 0;
                        const laneWidth = canvas.width / 4;
                        spawnText('MISS', note.lane * laneWidth + laneWidth/2, canvas.height - 40, '#ef4444');
                    }
                }
                
                notes = notes.filter(n => n.active);

                for (let i = particles.length - 1; i >= 0; i--) {
                    let p = particles[i];
                    p.x += p.vx * dt;
                    p.y += p.vy * dt;
                    p.vy += 1500 * dt; 
                    p.life -= dt * 1.5;
                    if (p.life <= 0) particles.splice(i, 1);
                }

                for (let i = floatingTexts.length - 1; i >= 0; i--) {
                    let t = floatingTexts[i];
                    t.y -= 150 * dt;
                    t.life -= dt * 1.5;
                    if (t.life <= 0) floatingTexts.splice(i, 1);
                }
            }

            function draw() {
                const STRIKE_ZONE_Y = canvas.height * 0.85;
                const STRIKE_ZONE_H = canvas.height * 0.1;

                const bgIntensity = Math.min(0.3, combo * 0.005);
                ctx.fillStyle = isLightMode ? `rgba(241, 245, 249, ${isPlaying ? 0.3 : 1.0})` : `rgba(10, 10, 12, ${isPlaying ? 0.3 : 1.0})`; 
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                const laneWidth = canvas.width / 4;

                for (let i = 0; i < 4; i++) {
                    ctx.beginPath();
                    ctx.moveTo(i * laneWidth, 0);
                    ctx.lineTo(i * laneWidth, canvas.height);
                    ctx.strokeStyle = isLightMode ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';
                    ctx.lineWidth = 2;
                    ctx.stroke();

                    const padY = STRIKE_ZONE_Y;
                    ctx.fillStyle = isLightMode ? 'rgba(0,0,0,0.4)' : 'rgba(255,255,255,0.4)';
                    ctx.font = 'bold 24px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(laneKeys[i], i * laneWidth + laneWidth/2, padY + STRIKE_ZONE_H/2 + 8);

                    if (activeKeys[i]) {
                        const grad = ctx.createLinearGradient(0, padY, 0, canvas.height);
                        grad.addColorStop(0, laneColors[i]);
                        grad.addColorStop(1, 'transparent');
                        ctx.fillStyle = grad;
                        ctx.globalAlpha = 0.6;
                        ctx.fillRect(i * laneWidth, padY, laneWidth, canvas.height - padY);
                        ctx.globalAlpha = 1.0;
                    }
                    
                    ctx.beginPath();
                    ctx.moveTo(0, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                    ctx.lineTo(canvas.width, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                    ctx.strokeStyle = isLightMode ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.1)';
                    ctx.lineWidth = 4;
                    ctx.stroke();
                }

                for (let note of notes) {
                    const x = note.lane * laneWidth + laneWidth * 0.05;
                    const w = laneWidth * 0.9;
                    const color = laneColors[note.lane];
                    
                    ctx.fillStyle = color;
                    ctx.shadowColor = color;
                    ctx.shadowBlur = isLightMode ? 15 : 30;
                    
                    const grad = ctx.createLinearGradient(0, note.y, 0, note.y + note.h);
                    grad.addColorStop(0, 'white');
                    grad.addColorStop(1, color);
                    ctx.fillStyle = grad;
                    
                    ctx.beginPath();
                    ctx.roundRect(x, note.y, w, note.h, 16);
                    ctx.fill();
                    ctx.shadowBlur = 0;
                }

                for (let p of particles) {
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = Math.max(0, p.life);
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.globalAlpha = 1.0;

                for (let t of floatingTexts) {
                    ctx.fillStyle = t.color;
                    ctx.globalAlpha = Math.max(0, t.life);
                    ctx.font = '900 32px "Inter", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.shadowColor = 'black';
                    ctx.shadowBlur = 4;
                    ctx.fillText(t.text, t.x, t.y);
                    ctx.shadowBlur = 0;
                }
                ctx.globalAlpha = 1.0;

                if (isPlaying) {
                    ctx.fillStyle = isLightMode ? '#0f172a' : '#ffffff';
                    ctx.font = '900 24px "Inter", sans-serif';
                    ctx.textAlign = 'left';
                    ctx.fillText(`SCORE: ${score}`, 30, canvas.height - 30);
                    
                    ctx.textAlign = 'right';
                    ctx.fillStyle = combo > 10 ? '#f59e0b' : (isLightMode ? '#0f172a' : '#ffffff');
                    const comboSize = 24 + Math.min(24, combo);
                    ctx.font = `900 ${comboSize}px "Inter", sans-serif`;
                    ctx.fillText(`${combo}x COMBO`, canvas.width - 30, canvas.height - 30);
                }
            }

            function gameLoop(timestamp) {
                if (!lastTime) lastTime = timestamp;
                const dt = (timestamp - lastTime) / 1000;
                lastTime = timestamp;

                update(dt);
                draw();

                if (isPlaying) {
                    animationId = requestAnimationFrame(gameLoop);
                }
            }

            function startGame() {
                if (!audioCtx) audioCtx = new AudioContext();
                audioCtx.resume();
                
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
                
                isPlaying = true;
                score = 0;
                combo = 0;
                notes = [];
                particles = [];
                floatingTexts = [];
                spawnTimer = 0;
                spawnInterval = 600;
                lastTime = performance.now();
                
                resizeCanvas();
                animationId = requestAnimationFrame(gameLoop);
            }

            window.addEventListener('keydown', (e) => {
                if (!isPlaying) return;
                const keyMap = {'d':0, 'f':1, 'j':2, 'k':3};
                const lane = keyMap[e.key.toLowerCase()];
                if (lane !== undefined) {
                    hitLane(lane);
                }
            });

            canvas.addEventListener('mousedown', (e) => {
                if (!isPlaying) return;
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const laneWidth = rect.width / 4;
                const lane = Math.floor(x / laneWidth);
                hitLane(lane);
            });
            
            canvas.addEventListener('touchstart', (e) => {
                if (!isPlaying) return;
                e.preventDefault(); 
                const rect = canvas.getBoundingClientRect();
                for (let i=0; i < e.changedTouches.length; i++) {
                    const x = e.changedTouches[i].clientX - rect.left;
                    const laneWidth = rect.width / 4;
                    const lane = Math.floor(x / laneWidth);
                    hitLane(lane);
                }
            }, {passive: false});

            startBtn.addEventListener('click', startGame);
            
            draw();
        });
    </script>
    @endpush
</x-webapp-layout>
