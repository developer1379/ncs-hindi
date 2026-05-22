<x-webapp-layout
    title="{{ $seo['title'] ?? 'NCS Rhythm Tapper | Free Online Music Game' }}"
    description="{{ $seo['description'] ?? 'Play the NCS Rhythm Tapper! A free, online, neon-styled music rhythm game.' }}"
    keywords="{{ $seo['keywords'] ?? 'music game online, rhythm tapper' }}"
>
    {{-- Game Wrapper: Responsive, No-Scroll, Theme Support --}}
    <div id="game-theme-wrapper" class="w-full h-full flex flex-col md:flex-row gap-4 transition-colors duration-500 bg-black text-white rounded-3xl p-4 overflow-hidden relative" style="height: calc(100vh - 120px);">
        
        {{-- Theme Toggle --}}
        <button id="theme-toggle" class="absolute top-4 right-4 z-50 p-3 rounded-full bg-zinc-800 text-amber-500 hover:bg-zinc-700 transition shadow-lg flex items-center justify-center">
            <i class="fa-solid fa-moon" id="theme-icon"></i>
        </button>

        {{-- Game Section --}}
        <section class="flex-1 flex flex-col h-full items-center justify-center relative">
            <div class="w-full max-w-[500px] flex justify-between items-end mb-2">
                <div>
                    <h1 class="font-brand text-2xl md:text-3xl font-black italic uppercase tracking-tighter title-text">
                        NCS <span class="text-amber-500">Rhythm</span>
                    </h1>
                </div>
                <div class="text-right">
                    <p class="text-[9px] text-zinc-500 font-black uppercase tracking-widest mb-1">Max Combo</p>
                    <p id="high-score" class="text-xl font-black text-amber-500 font-brand">0</p>
                </div>
            </div>

            {{-- Game Container --}}
            <div id="canvas-container" class="relative w-full max-w-[500px] flex-1 bg-[#0a0a0c] border-2 border-zinc-800 rounded-3xl overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.5)] transition-colors duration-500">
                
                {{-- Start Screen Overlay --}}
                <div id="game-overlay" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-black/80 backdrop-blur-md transition-opacity duration-300">
                    <h2 id="game-status" class="text-3xl font-black text-white font-brand italic uppercase tracking-widest mb-2 drop-shadow-[0_0_15px_rgba(255,255,255,0.5)] text-center px-4">Ready to Drop</h2>
                    <p class="text-zinc-300 text-xs font-medium tracking-wide mb-6 text-center px-4">Tap D, F, J, K or click lanes</p>
                    <button id="start-btn" class="px-8 py-3 bg-amber-500 text-black text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-amber-400 hover:scale-105 transition-all shadow-[0_0_20px_rgba(245,158,11,0.4)] outline-none border-b-4 border-amber-700">
                        Start Game
                    </button>
                </div>

                {{-- Canvas --}}
                <canvas id="rhythm-canvas" class="block w-full h-full cursor-crosshair z-10 relative"></canvas>
            </div>
        </section>

        {{-- SEO Content Section (Sidebar for Desktop, Hidden visually on mobile but accessible) --}}
        <aside id="seo-sidebar" class="hidden md:flex flex-col w-[300px] lg:w-[350px] bg-zinc-900/50 backdrop-blur-md border border-zinc-800/50 rounded-3xl p-6 h-full overflow-y-auto no-scrollbar transition-colors duration-500">
            <h2 class="text-lg font-bold mb-3 title-text">About This Free Online Music Game</h2>
            <div class="prose prose-sm prose-invert content-text leading-relaxed">
                <p>Welcome to the <strong>NCS Rhythm Tapper</strong>! This highly engaging, interactive browser game tests your reflexes and rhythm. Catch the neon beats!</p>
                
                <h3 class="text-amber-500 text-sm font-bold mt-4 mb-1">How to Play</h3>
                <ul class="list-disc pl-4 space-y-1 mb-4 text-xs">
                    <li>Watch for the glowing neon tiles falling.</li>
                    <li>Wait until tiles enter the <strong>Strike Zone</strong> at the bottom.</li>
                    <li>Tap keys (<strong>D, F, J, K</strong>) or screen directly!</li>
                    <li>Chain <em>Perfect</em> hits for massive combos.</li>
                </ul>

                <h3 class="text-amber-500 text-sm font-bold mt-4 mb-1">Why Play?</h3>
                <p class="text-xs">As the premier platform for <strong>royalty-free Hindi music</strong> and <strong>non-copyright soundtracks</strong>, NCS Hindi provides creators studio-quality audio. Our mini-games keep you entertained while discovering new beats.</p>
            </div>
        </aside>

        {{-- Mobile SEO block (visually hidden but present in DOM) --}}
        <div class="md:hidden sr-only">
            <h2>About This Free Online Music Game</h2>
            <p>Welcome to the NCS Rhythm Tapper! This highly engaging, interactive browser game tests your reflexes and rhythm. Catch the neon beats! As the premier platform for royalty-free Hindi music and non-copyright soundtracks, NCS Hindi provides creators studio-quality audio. Our mini-games keep you entertained while discovering new beats.</p>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Theme Toggle Logic
            const themeBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const wrapper = document.getElementById('game-theme-wrapper');
            const canvasContainer = document.getElementById('canvas-container');
            const sidebar = document.getElementById('seo-sidebar');
            const titleTexts = document.querySelectorAll('.title-text');
            const contentTexts = document.querySelectorAll('.content-text');
            const overlay = document.getElementById('game-overlay');

            let isLightMode = false;

            themeBtn.addEventListener('click', () => {
                isLightMode = !isLightMode;
                if (isLightMode) {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                    // Light mode styles
                    wrapper.classList.replace('bg-black', 'bg-slate-100');
                    canvasContainer.classList.replace('bg-[#0a0a0c]', 'bg-slate-200');
                    canvasContainer.classList.replace('border-zinc-800', 'border-slate-300');
                    sidebar.classList.replace('bg-zinc-900/50', 'bg-white/80');
                    sidebar.classList.replace('border-zinc-800/50', 'border-slate-300');
                    overlay.classList.replace('bg-black/80', 'bg-slate-900/80');
                    
                    titleTexts.forEach(el => el.classList.replace('text-white', 'text-slate-900'));
                    contentTexts.forEach(el => el.classList.replace('prose-invert', 'prose-slate'));
                    themeBtn.classList.replace('bg-zinc-800', 'bg-white');
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                    // Dark mode styles
                    wrapper.classList.replace('bg-slate-100', 'bg-black');
                    canvasContainer.classList.replace('bg-slate-200', 'bg-[#0a0a0c]');
                    canvasContainer.classList.replace('border-slate-300', 'border-zinc-800');
                    sidebar.classList.replace('bg-white/80', 'bg-zinc-900/50');
                    sidebar.classList.replace('border-slate-300', 'border-zinc-800/50');
                    overlay.classList.replace('bg-slate-900/80', 'bg-black/80');

                    titleTexts.forEach(el => el.classList.replace('text-slate-900', 'text-white'));
                    contentTexts.forEach(el => el.classList.replace('prose-slate', 'prose-invert'));
                    themeBtn.classList.replace('bg-white', 'bg-zinc-800');
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

            const NOTE_SPEED = 400; 
            let spawnTimer = 0;
            let spawnInterval = 600;

            function spawnNote() {
                const lane = Math.floor(Math.random() * 4);
                notes.push({
                    lane: lane,
                    y: -50,
                    h: 80,
                    active: true
                });
            }

            function spawnParticles(x, y, color) {
                for (let i = 0; i < 15; i++) {
                    particles.push({
                        x: x,
                        y: y,
                        vx: (Math.random() - 0.5) * 400,
                        vy: (Math.random() - 0.5) * 400 - 100,
                        life: 1.0,
                        color: color,
                        size: Math.random() * 4 + 2
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
                const STRIKE_ZONE_H = 80;

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
                    
                    if (noteBottom > STRIKE_ZONE_Y - 40 && targetNote.y < STRIKE_ZONE_Y + STRIKE_ZONE_H + 40) {
                        targetNote.active = false;
                        let hitType = '';
                        let points = 0;
                        let isPerfect = false;
                        
                        if (dist < 30) {
                            hitType = 'PERFECT'; points = 100; isPerfect = true; combo++;
                            spawnParticles(laneCenter, zoneCenter, laneColors[lane]);
                        } else if (dist < 60) {
                            hitType = 'GOOD'; points = 50; combo++;
                            spawnParticles(laneCenter, zoneCenter, '#ffffff');
                        } else {
                            hitType = 'BAD'; points = 10; combo = 0;
                        }

                        score += points * Math.max(1, Math.floor(combo / 10));
                        spawnText(hitType, laneCenter, STRIKE_ZONE_Y - 20, isPerfect ? laneColors[lane] : '#ffffff');
                        playDrumHit(lane, isPerfect);
                        updateMaxCombo();
                        return;
                    }
                }
                
                combo = 0;
                spawnText('MISS', laneCenter, STRIKE_ZONE_Y - 20, '#ef4444');
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

                spawnTimer += dt * 1000;
                if (spawnTimer > spawnInterval) {
                    spawnNote();
                    spawnTimer = 0;
                    spawnInterval = Math.max(250, spawnInterval - 5); 
                }

                for (let i = notes.length - 1; i >= 0; i--) {
                    let note = notes[i];
                    if (!note.active) continue;
                    
                    note.y += NOTE_SPEED * dt;
                    if (note.y > canvas.height) {
                        note.active = false;
                        combo = 0;
                        const laneWidth = canvas.width / 4;
                        spawnText('MISS', note.lane * laneWidth + laneWidth/2, canvas.height - 20, '#ef4444');
                    }
                }
                
                notes = notes.filter(n => n.active);

                for (let i = particles.length - 1; i >= 0; i--) {
                    let p = particles[i];
                    p.x += p.vx * dt;
                    p.y += p.vy * dt;
                    p.vy += 1000 * dt; 
                    p.life -= dt * 2;
                    if (p.life <= 0) particles.splice(i, 1);
                }

                for (let i = floatingTexts.length - 1; i >= 0; i--) {
                    let t = floatingTexts[i];
                    t.y -= 100 * dt;
                    t.life -= dt * 1.5;
                    if (t.life <= 0) floatingTexts.splice(i, 1);
                }
            }

            function draw() {
                const STRIKE_ZONE_Y = canvas.height * 0.85;
                const STRIKE_ZONE_H = 80;

                const bgIntensity = Math.min(0.2, combo * 0.005);
                ctx.fillStyle = isLightMode ? `rgba(226, 232, 240, ${isPlaying ? 0.3 : 1.0})` : `rgba(10, 10, 12, ${isPlaying ? 0.3 : 1.0})`; 
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                const laneWidth = canvas.width / 4;

                for (let i = 0; i < 4; i++) {
                    ctx.beginPath();
                    ctx.moveTo(i * laneWidth, 0);
                    ctx.lineTo(i * laneWidth, canvas.height);
                    ctx.strokeStyle = isLightMode ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';
                    ctx.lineWidth = 1;
                    ctx.stroke();

                    const padY = STRIKE_ZONE_Y;
                    ctx.fillStyle = isLightMode ? 'rgba(0,0,0,0.3)' : 'rgba(255,255,255,0.3)';
                    ctx.font = 'bold 20px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(laneKeys[i], i * laneWidth + laneWidth/2, padY + STRIKE_ZONE_H + 30);

                    if (activeKeys[i]) {
                        const grad = ctx.createLinearGradient(0, padY, 0, canvas.height);
                        grad.addColorStop(0, laneColors[i]);
                        grad.addColorStop(1, 'transparent');
                        ctx.fillStyle = grad;
                        ctx.globalAlpha = 0.5;
                        ctx.fillRect(i * laneWidth, padY, laneWidth, canvas.height - padY);
                        ctx.globalAlpha = 1.0;
                    }
                    
                    ctx.beginPath();
                    ctx.moveTo(0, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                    ctx.lineTo(canvas.width, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                    ctx.strokeStyle = isLightMode ? 'rgba(0,0,0,0.2)' : 'rgba(255,255,255,0.2)';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                }

                for (let note of notes) {
                    const x = note.lane * laneWidth + laneWidth * 0.1;
                    const w = laneWidth * 0.8;
                    const color = laneColors[note.lane];
                    
                    ctx.fillStyle = color;
                    ctx.shadowColor = color;
                    ctx.shadowBlur = isLightMode ? 10 : 20;
                    
                    const grad = ctx.createLinearGradient(0, note.y, 0, note.y + note.h);
                    grad.addColorStop(0, 'white');
                    grad.addColorStop(1, color);
                    ctx.fillStyle = grad;
                    
                    ctx.beginPath();
                    ctx.roundRect(x, note.y, w, note.h, 10);
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
                    ctx.font = '900 24px "Inter", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(t.text, t.x, t.y);
                }
                ctx.globalAlpha = 1.0;

                if (isPlaying) {
                    ctx.fillStyle = isLightMode ? '#0f172a' : '#ffffff';
                    ctx.font = '900 20px "Inter", sans-serif';
                    ctx.textAlign = 'left';
                    ctx.fillText(`SCORE: ${score}`, 20, 40);
                    
                    ctx.textAlign = 'right';
                    ctx.fillStyle = combo > 10 ? '#f59e0b' : (isLightMode ? '#0f172a' : '#ffffff');
                    const comboSize = 20 + Math.min(20, combo);
                    ctx.font = `900 ${comboSize}px "Inter", sans-serif`;
                    ctx.fillText(`${combo}x COMBO`, canvas.width - 20, 40);
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
                spawnInterval = 800;
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
