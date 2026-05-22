<x-webapp-layout
    title="{{ $seo['title'] ?? 'NCS Rhythm Tapper | Free Online Music Game' }}"
    description="{{ $seo['description'] ?? 'Play the NCS Rhythm Tapper! A free, online, neon-styled music rhythm game.' }}"
    keywords="{{ $seo['keywords'] ?? 'music game online, rhythm tapper' }}"
>
    <style>
        main {
            padding: 0 !important;
            margin: 0 !important;
            overflow: hidden !important;
        }
    </style>

    {{-- Game Wrapper --}}
    <div id="game-theme-wrapper" class="w-full h-full flex flex-col transition-colors duration-500 bg-[#0f0f13] text-white relative overflow-hidden">
        
        {{-- Theme Toggle --}}
        <button id="theme-toggle" class="absolute top-6 right-6 z-50 w-12 h-12 rounded-full bg-zinc-800/80 backdrop-blur-md text-amber-500 hover:bg-zinc-700 transition shadow-2xl flex items-center justify-center outline-none border border-zinc-700/50">
            <i class="fa-solid fa-moon text-xl" id="theme-icon"></i>
        </button>

        {{-- Game Container --}}
        <div id="canvas-container" class="relative w-full flex-1 transition-colors duration-500">
            
            {{-- Start Screen Overlay --}}
            <div id="game-overlay" class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-black/80 backdrop-blur-md transition-opacity duration-300">
                <div class="text-center mb-8">
                    <h1 class="font-brand text-5xl md:text-7xl font-black italic uppercase tracking-tighter title-text drop-shadow-[0_0_30px_rgba(255,255,255,0.2)] text-white">
                        NCS <span class="text-amber-500">Rhythm</span>
                    </h1>
                    <p class="text-zinc-400 font-bold tracking-[0.3em] uppercase mt-2 text-sm md:text-base">Tap the beats</p>
                </div>

                <div class="w-24 h-24 bg-gradient-to-br from-amber-500 to-amber-700 rounded-full flex items-center justify-center mb-8 shadow-[0_0_60px_rgba(245,158,11,0.6)] cursor-pointer hover:scale-110 transition-transform" id="start-btn-circle">
                    <i class="fa-solid fa-play text-4xl text-white ml-2"></i>
                </div>

                <h2 id="game-status" class="text-2xl md:text-3xl font-black text-white font-brand uppercase tracking-widest mb-4">Are you ready?</h2>
                <p class="text-zinc-400 text-sm md:text-base font-medium tracking-widest uppercase text-center px-4">Controls: D, F, J, K or Tap</p>
            </div>

            {{-- Canvas --}}
            <canvas id="rhythm-canvas" class="block w-full h-full cursor-crosshair z-10 relative"></canvas>
        </div>

        {{-- SEO Content Section (Visually Hidden) --}}
        <div class="sr-only">
            <h2>About This Free Online Music Game</h2>
            <p>Welcome to the NCS Rhythm Tapper! This highly engaging, interactive browser game tests your reflexes and rhythm. Catch the neon beats! As the premier platform for royalty-free Hindi music and non-copyright soundtracks, NCS Hindi provides creators studio-quality audio. Our mini-games keep you entertained while discovering new beats. Play our free online rhythm game similar to piano tiles and beat making games.</p>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const wrapper = document.getElementById('game-theme-wrapper');
            const overlay = document.getElementById('game-overlay');
            const titleTexts = document.querySelectorAll('.title-text');

            let isLightMode = false;

            themeBtn.addEventListener('click', () => {
                isLightMode = !isLightMode;
                if (isLightMode) {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                    wrapper.classList.replace('bg-[#0f0f13]', 'bg-slate-50');
                    overlay.classList.replace('bg-black/80', 'bg-slate-100/90');
                    titleTexts.forEach(el => el.classList.replace('text-white', 'text-slate-900'));
                    themeBtn.classList.replace('bg-zinc-800/80', 'bg-white/90');
                    themeBtn.classList.replace('border-zinc-700/50', 'border-slate-300');
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                    wrapper.classList.replace('bg-slate-50', 'bg-[#0f0f13]');
                    overlay.classList.replace('bg-slate-100/90', 'bg-black/80');
                    titleTexts.forEach(el => el.classList.replace('text-slate-900', 'text-white'));
                    themeBtn.classList.replace('bg-white/90', 'bg-zinc-800/80');
                    themeBtn.classList.replace('border-slate-300', 'border-zinc-700/50');
                }
            });

            const canvas = document.getElementById('rhythm-canvas');
            const ctx = canvas.getContext('2d');
            const startBtnCircle = document.getElementById('start-btn-circle');
            const statusText = document.getElementById('game-status');

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
                if (isPerfect) osc.frequency.exponentialRampToValueAtTime(40, audioCtx.currentTime + 0.15);
                
                gain.gain.setValueAtTime(isPerfect ? 0.8 : 0.4, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15);
            }

            let isPlaying = false;
            let animationId;
            let lastTime = 0;
            let score = 0;
            let combo = 0;
            let maxCombo = localStorage.getItem('ncs_rhythm_max_combo') || 0;
            
            const laneColors = ['#ef4444', '#3b82f6', '#eab308', '#22c55e'];
            const laneKeys = ['D', 'F', 'J', 'K'];
            let activeKeys = [false, false, false, false];

            let notes = [];
            let particles = [];
            let floatingTexts = [];

            let noteSpeed = canvas.height * 0.7; 
            let spawnTimer = 0;
            let spawnInterval = 550;

            function spawnNote() {
                const lane = Math.floor(Math.random() * 4);
                notes.push({
                    lane: lane,
                    y: -100,
                    h: 60,
                    active: true,
                    scored: false
                });
            }

            function spawnParticles(x, y, color) {
                for (let i = 0; i < 25; i++) {
                    particles.push({
                        x: x,
                        y: y,
                        vx: (Math.random() - 0.5) * 800,
                        vy: (Math.random() - 0.5) * 800 - 100,
                        life: 1.0,
                        color: color,
                        size: Math.random() * 5 + 2
                    });
                }
            }

            function spawnText(text, x, y, color) {
                floatingTexts.push({ text, x, y, life: 1.0, color, scale: 0 });
            }

            function hitLane(lane) {
                if (!isPlaying) return;
                
                activeKeys[lane] = true;
                setTimeout(() => activeKeys[lane] = false, 150);

                const trackWidth = Math.min(500, canvas.width * 0.9);
                const offsetX = (canvas.width - trackWidth) / 2;
                const laneWidth = trackWidth / 4;
                const laneCenter = offsetX + (lane * laneWidth) + (laneWidth / 2);
                
                const STRIKE_ZONE_Y = canvas.height * 0.82;
                const STRIKE_ZONE_H = 80;

                let targetNote = null;
                for (let i = 0; i < notes.length; i++) {
                    if (notes[i].lane === lane && notes[i].active && !notes[i].scored) {
                        targetNote = notes[i];
                        break; 
                    }
                }

                if (targetNote) {
                    const noteBottom = targetNote.y + targetNote.h;
                    const zoneCenter = STRIKE_ZONE_Y + STRIKE_ZONE_H/2;
                    const dist = Math.abs((targetNote.y + targetNote.h/2) - zoneCenter);
                    
                    const hitTolerance = 100;
                    
                    if (dist < hitTolerance) {
                        targetNote.active = false;
                        targetNote.scored = true;
                        
                        let hitType = '';
                        let points = 0;
                        let isPerfect = false;
                        
                        if (dist < 25) {
                            hitType = 'PERFECT'; points = 100; isPerfect = true; combo++;
                            spawnParticles(laneCenter, zoneCenter, laneColors[lane]);
                        } else if (dist < 60) {
                            hitType = 'GOOD'; points = 50; combo++;
                            spawnParticles(laneCenter, zoneCenter, '#ffffff');
                        } else {
                            hitType = 'OKAY'; points = 10; combo = 0;
                        }

                        score += points + (combo * 5);
                        spawnText(hitType, laneCenter, STRIKE_ZONE_Y - 50, isPerfect ? laneColors[lane] : '#ffffff');
                        playDrumHit(lane, isPerfect);
                        updateMaxCombo();
                        return;
                    }
                }
                
                combo = 0;
                spawnText('MISS', laneCenter, STRIKE_ZONE_Y - 50, '#ef4444');
            }

            function updateMaxCombo() {
                if (combo > maxCombo) {
                    maxCombo = combo;
                    localStorage.setItem('ncs_rhythm_max_combo', maxCombo);
                }
            }

            function update(dt) {
                if (!isPlaying) return;
                
                noteSpeed = canvas.height * 0.7; 

                spawnTimer += dt * 1000;
                if (spawnTimer > spawnInterval) {
                    spawnNote();
                    spawnTimer = 0;
                    spawnInterval = Math.max(300, spawnInterval - 1); 
                }

                const STRIKE_ZONE_Y = canvas.height * 0.82;
                const STRIKE_ZONE_H = 80;

                for (let i = notes.length - 1; i >= 0; i--) {
                    let note = notes[i];
                    if (!note.active) continue;
                    
                    note.y += noteSpeed * dt;
                    
                    if (note.y > STRIKE_ZONE_Y + STRIKE_ZONE_H && !note.scored) {
                        note.active = false;
                        note.scored = true;
                        combo = 0;
                        
                        const trackWidth = Math.min(500, canvas.width * 0.9);
                        const offsetX = (canvas.width - trackWidth) / 2;
                        const laneWidth = trackWidth / 4;
                        const laneCenter = offsetX + (note.lane * laneWidth) + (laneWidth / 2);
                        
                        spawnText('MISS', laneCenter, canvas.height - 80, '#ef4444');
                    }
                }
                
                notes = notes.filter(n => n.active);

                for (let i = particles.length - 1; i >= 0; i--) {
                    let p = particles[i];
                    p.x += p.vx * dt;
                    p.y += p.vy * dt;
                    p.vy += 1200 * dt; 
                    p.life -= dt * 2;
                    if (p.life <= 0) particles.splice(i, 1);
                }

                for (let i = floatingTexts.length - 1; i >= 0; i--) {
                    let t = floatingTexts[i];
                    t.y -= 80 * dt;
                    t.life -= dt * 1.5;
                    t.scale = Math.min(1.2, t.scale + dt * 5);
                    if (t.life <= 0) floatingTexts.splice(i, 1);
                }
            }

            function draw() {
                // Background
                ctx.fillStyle = isLightMode ? '#f8fafc' : '#0a0a0c'; 
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Grid/Ambient Background Effect
                ctx.strokeStyle = isLightMode ? 'rgba(0,0,0,0.03)' : 'rgba(255,255,255,0.03)';
                ctx.lineWidth = 1;
                ctx.beginPath();
                for(let i=0; i<canvas.height; i+=40) {
                    ctx.moveTo(0, i + (lastTime/20)%40);
                    ctx.lineTo(canvas.width, i + (lastTime/20)%40);
                }
                ctx.stroke();

                // Track Calculation
                const trackWidth = Math.min(500, canvas.width * 0.9);
                const offsetX = (canvas.width - trackWidth) / 2;
                const laneWidth = trackWidth / 4;

                const STRIKE_ZONE_Y = canvas.height * 0.82;
                const STRIKE_ZONE_H = 80;

                // Track Background
                ctx.fillStyle = isLightMode ? 'rgba(0,0,0,0.02)' : 'rgba(255,255,255,0.02)';
                ctx.fillRect(offsetX, 0, trackWidth, canvas.height);

                for (let i = 0; i < 4; i++) {
                    const laneX = offsetX + (i * laneWidth);
                    
                    // Lane separator
                    ctx.beginPath();
                    ctx.moveTo(laneX, 0);
                    ctx.lineTo(laneX, canvas.height);
                    ctx.strokeStyle = isLightMode ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.1)';
                    ctx.lineWidth = i === 0 ? 3 : 1;
                    ctx.stroke();

                    if (i === 3) {
                        ctx.beginPath();
                        ctx.moveTo(laneX + laneWidth, 0);
                        ctx.lineTo(laneX + laneWidth, canvas.height);
                        ctx.lineWidth = 3;
                        ctx.stroke();
                    }

                    // Strike Pad Key Text
                    const padY = STRIKE_ZONE_Y;
                    ctx.fillStyle = isLightMode ? '#64748b' : '#64748b';
                    ctx.font = '900 24px "Inter", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(laneKeys[i], laneX + laneWidth/2, padY + STRIKE_ZONE_H/2 + 8);

                    // Active Lane Glow
                    if (activeKeys[i]) {
                        const grad = ctx.createLinearGradient(0, STRIKE_ZONE_Y, 0, 0);
                        grad.addColorStop(0, laneColors[i]);
                        grad.addColorStop(1, 'transparent');
                        ctx.fillStyle = grad;
                        ctx.globalAlpha = 0.4;
                        ctx.fillRect(laneX, 0, laneWidth, STRIKE_ZONE_Y + STRIKE_ZONE_H);
                        ctx.globalAlpha = 1.0;
                    }
                }

                // Strike Zone Box
                ctx.beginPath();
                ctx.roundRect(offsetX, STRIKE_ZONE_Y, trackWidth, STRIKE_ZONE_H, 12);
                ctx.strokeStyle = isLightMode ? 'rgba(0,0,0,0.2)' : 'rgba(255,255,255,0.2)';
                ctx.lineWidth = 4;
                ctx.stroke();
                
                // Strike Line
                ctx.beginPath();
                ctx.moveTo(offsetX, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                ctx.lineTo(offsetX + trackWidth, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                ctx.strokeStyle = isLightMode ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.1)';
                ctx.lineWidth = 2;
                ctx.stroke();

                // Notes
                for (let note of notes) {
                    const x = offsetX + (note.lane * laneWidth) + (laneWidth * 0.15);
                    const w = laneWidth * 0.7;
                    const color = laneColors[note.lane];
                    
                    ctx.fillStyle = color;
                    ctx.shadowColor = color;
                    ctx.shadowBlur = isLightMode ? 8 : 25;
                    
                    ctx.beginPath();
                    ctx.roundRect(x, note.y, w, note.h, 12);
                    ctx.fill();
                    
                    // Inner bright core
                    ctx.fillStyle = '#ffffff';
                    ctx.shadowBlur = 0;
                    ctx.beginPath();
                    ctx.roundRect(x + w*0.3, note.y + 4, w*0.4, note.h - 8, 8);
                    ctx.fill();
                }

                // Particles
                for (let p of particles) {
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = Math.max(0, p.life);
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.globalAlpha = 1.0;

                // Floating Texts
                for (let t of floatingTexts) {
                    ctx.save();
                    ctx.translate(t.x, t.y);
                    ctx.scale(t.scale, t.scale);
                    ctx.fillStyle = t.color;
                    ctx.globalAlpha = Math.max(0, t.life);
                    ctx.font = '900 36px "Inter", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.shadowColor = isLightMode ? 'rgba(0,0,0,0.2)' : 'rgba(0,0,0,0.8)';
                    ctx.shadowBlur = 8;
                    ctx.fillText(t.text, 0, 0);
                    ctx.restore();
                }

                // HUD (Score & Combo)
                ctx.fillStyle = isLightMode ? '#0f172a' : '#ffffff';
                ctx.font = '900 32px "Inter", sans-serif';
                ctx.textAlign = 'left';
                
                // Left HUD
                const hudY = 60;
                ctx.fillText(`SCORE`, 30, hudY);
                ctx.fillStyle = '#eab308';
                ctx.fillText(score, 30, hudY + 40);
                
                // Right HUD
                ctx.textAlign = 'right';
                ctx.fillStyle = isLightMode ? '#0f172a' : '#ffffff';
                ctx.fillText(`MAX COMBO: ${maxCombo}`, canvas.width - 30, hudY);
                ctx.fillStyle = combo > 10 ? '#ef4444' : (isLightMode ? '#3b82f6' : '#38bdf8');
                ctx.font = `900 ${32 + Math.min(20, combo/2)}px "Inter", sans-serif`;
                ctx.fillText(`${combo}x COMBO`, canvas.width - 30, hudY + 40);
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
                spawnInterval = 550;
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
                
                const trackWidth = Math.min(500, canvas.width * 0.9);
                const offsetX = (canvas.width - trackWidth) / 2;
                
                if (x >= offsetX && x <= offsetX + trackWidth) {
                    const laneWidth = trackWidth / 4;
                    const lane = Math.floor((x - offsetX) / laneWidth);
                    if (lane >= 0 && lane <= 3) hitLane(lane);
                }
            });
            
            canvas.addEventListener('touchstart', (e) => {
                if (!isPlaying) return;
                e.preventDefault(); 
                const rect = canvas.getBoundingClientRect();
                const trackWidth = Math.min(500, canvas.width * 0.9);
                const offsetX = (canvas.width - trackWidth) / 2;
                
                for (let i=0; i < e.changedTouches.length; i++) {
                    const x = e.changedTouches[i].clientX - rect.left;
                    if (x >= offsetX && x <= offsetX + trackWidth) {
                        const laneWidth = trackWidth / 4;
                        const lane = Math.floor((x - offsetX) / laneWidth);
                        if (lane >= 0 && lane <= 3) hitLane(lane);
                    }
                }
            }, {passive: false});

            startBtnCircle.addEventListener('click', startGame);
            
            draw();
        });
    </script>
    @endpush
</x-webapp-layout>
