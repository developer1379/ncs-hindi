<x-webapp-layout
    title="{{ $seo['title'] ?? 'NCS Rhythm Tapper | Free Online Music Game' }}"
    description="{{ $seo['description'] ?? 'Play the NCS Rhythm Tapper! A free, online, neon-styled music rhythm game.' }}"
    keywords="{{ $seo['keywords'] ?? 'music game online, rhythm tapper' }}"
>
    <article class="max-w-6xl mx-auto py-8 px-4 flex flex-col xl:flex-row gap-8 items-start">
        
        {{-- Game Section --}}
        <section class="w-full xl:w-2/3 flex flex-col items-center">
            <div class="w-full flex justify-between items-end mb-6">
                <div>
                    <h1 class="font-brand text-4xl md:text-5xl font-black italic uppercase text-white tracking-tighter">
                        NCS <span class="text-amber-500">Rhythm Tapper</span>
                    </h1>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-1">Max Combo</p>
                    <p id="high-score" class="text-2xl font-black text-amber-500 font-brand">0</p>
                </div>
            </div>

            {{-- Game Container --}}
            <div class="relative w-full max-w-[600px] aspect-[3/4] bg-[#0a0a0c] border-2 border-zinc-800 rounded-3xl overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.8)]">
                
                {{-- Start Screen Overlay --}}
                <div id="game-overlay" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity duration-300">
                    <h2 id="game-status" class="text-4xl font-black text-white font-brand italic uppercase tracking-widest mb-2 drop-shadow-[0_0_15px_rgba(255,255,255,0.5)] text-center px-4">Ready to Drop</h2>
                    <p class="text-zinc-400 text-sm font-medium tracking-wide mb-8 text-center px-4">Tap the keys D, F, J, K or click the lanes to hit the falling beats!</p>
                    <button id="start-btn" class="px-10 py-4 bg-amber-500 text-black text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-amber-400 hover:scale-105 transition-all shadow-[0_0_30px_rgba(245,158,11,0.4)] outline-none border-b-4 border-amber-700">
                        Start Game
                    </button>
                </div>

                {{-- Canvas --}}
                <canvas id="rhythm-canvas" class="block w-full h-full cursor-crosshair z-10 relative"></canvas>
            </div>
        </section>

        {{-- SEO Content Section --}}
        <aside class="w-full xl:w-1/3 bg-zinc-900/30 backdrop-blur-md border border-zinc-800/50 rounded-[2.5rem] p-8 xl:sticky xl:top-24 mt-8 xl:mt-16">
            <h2 class="text-xl font-bold text-white mb-4">About This Free Online Music Game</h2>
            <div class="prose prose-invert prose-sm text-zinc-400 leading-relaxed">
                <p>Welcome to the <strong>NCS Rhythm Tapper</strong>! This highly engaging, interactive browser game tests your reflexes and rhythm. If you love games like Piano Tiles or Guitar Hero, you'll love catching these neon beats.</p>
                
                <h3 class="text-amber-500 text-base font-bold mt-6 mb-2">How to Play</h3>
                <ul class="list-disc pl-4 space-y-2 mb-6">
                    <li>Watch for the glowing neon tiles falling down the four distinct lanes.</li>
                    <li>Wait until the tiles enter the bottom <strong>Strike Zone</strong>.</li>
                    <li>Tap the corresponding keys (<strong>D, F, J, K</strong>) or tap the screen directly on mobile devices to hit the note!</li>
                    <li>Chain together <em>Perfect</em> hits to build your <strong>Combo Multiplier</strong> and achieve a massive high score.</li>
                </ul>

                <h3 class="text-amber-500 text-base font-bold mt-6 mb-2">Why Play NCS Games?</h3>
                <p>As the premier platform for <strong>royalty-free Hindi music</strong> and <strong>non-copyright soundtracks</strong>, NCS Hindi provides creators not just with studio-quality audio, but also a vibrant ecosystem. Our mini-games are designed to keep the community engaged, entertained, and inspired while discovering new beats for their next viral video.</p>
            </div>
        </aside>

    </article>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('rhythm-canvas');
            const ctx = canvas.getContext('2d');
            const overlay = document.getElementById('game-overlay');
            const startBtn = document.getElementById('start-btn');
            const statusText = document.getElementById('game-status');
            const highScoreEl = document.getElementById('high-score');

            // Responsive Canvas Setup
            function resizeCanvas() {
                // To keep internal resolution crisp, we match actual pixels
                const rect = canvas.parentElement.getBoundingClientRect();
                canvas.width = rect.width;
                canvas.height = rect.height;
            }
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            // Web Audio API
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

            // Game Variables
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

            // Entities
            let notes = [];
            let particles = [];
            let floatingTexts = [];

            const NOTE_SPEED = 400; // pixels per second
            const STRIKE_ZONE_Y = canvas.height * 0.85;
            const STRIKE_ZONE_H = 80;
            let spawnTimer = 0;
            let spawnInterval = 600; // ms between notes

            function spawnNote() {
                const lane = Math.floor(Math.random() * 4);
                notes.push({
                    lane: lane,
                    y: -50,
                    h: 120, // tall for neon look
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
                floatingTexts.push({
                    text: text,
                    x: x,
                    y: y,
                    life: 1.0,
                    color: color
                });
            }

            function hitLane(lane) {
                if (!isPlaying) return;
                
                // Visual feedback on lane
                activeKeys[lane] = true;
                setTimeout(() => activeKeys[lane] = false, 100);

                const laneWidth = canvas.width / 4;
                const laneCenter = lane * laneWidth + laneWidth / 2;

                // Find lowest note in this lane
                let targetNote = null;
                for (let i = 0; i < notes.length; i++) {
                    if (notes[i].lane === lane && notes[i].active) {
                        targetNote = notes[i];
                        break; // Since notes are ordered, the first is the lowest
                    }
                }

                if (targetNote) {
                    const noteBottom = targetNote.y + targetNote.h;
                    const noteCenter = targetNote.y + targetNote.h/2;
                    const zoneCenter = STRIKE_ZONE_Y + STRIKE_ZONE_H/2;
                    
                    const dist = Math.abs(noteBottom - zoneCenter);
                    
                    if (noteBottom > STRIKE_ZONE_Y - 40 && targetNote.y < STRIKE_ZONE_Y + STRIKE_ZONE_H + 40) {
                        targetNote.active = false;
                        let hitType = '';
                        let points = 0;
                        let isPerfect = false;
                        
                        if (dist < 30) {
                            hitType = 'PERFECT';
                            points = 100;
                            isPerfect = true;
                            combo++;
                            spawnParticles(laneCenter, zoneCenter, laneColors[lane]);
                        } else if (dist < 60) {
                            hitType = 'GOOD';
                            points = 50;
                            combo++;
                            spawnParticles(laneCenter, zoneCenter, '#ffffff');
                        } else {
                            hitType = 'BAD';
                            points = 10;
                            combo = 0;
                        }

                        score += points * Math.max(1, Math.floor(combo / 10));
                        spawnText(hitType, laneCenter, STRIKE_ZONE_Y - 20, isPerfect ? laneColors[lane] : '#ffffff');
                        playDrumHit(lane, isPerfect);
                        updateMaxCombo();
                        return;
                    }
                }
                
                // Missed (pressed when empty)
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

                // Spawning
                spawnTimer += dt * 1000;
                if (spawnTimer > spawnInterval) {
                    spawnNote();
                    spawnTimer = 0;
                    // Gradually increase difficulty
                    spawnInterval = Math.max(250, spawnInterval - 5); 
                }

                // Update Notes
                for (let i = notes.length - 1; i >= 0; i--) {
                    let note = notes[i];
                    if (!note.active) continue;
                    
                    note.y += NOTE_SPEED * dt;
                    
                    // Missed note
                    if (note.y > canvas.height) {
                        note.active = false;
                        combo = 0;
                        const laneWidth = canvas.width / 4;
                        spawnText('MISS', note.lane * laneWidth + laneWidth/2, canvas.height - 20, '#ef4444');
                        
                        // Game Over Condition (Miss 3 notes... let's just make it reset combo for endless fun, or actually game over?)
                        // Let's do endless but score tracking. To make it a real game, let's say missing 5 notes ends it.
                        // Actually, dropping combo to 0 is punishing enough, let's keep it endless for engagement.
                    }
                }
                
                // Clean up inactive notes
                notes = notes.filter(n => n.active);

                // Update Particles
                for (let i = particles.length - 1; i >= 0; i--) {
                    let p = particles[i];
                    p.x += p.vx * dt;
                    p.y += p.vy * dt;
                    p.vy += 1000 * dt; // gravity
                    p.life -= dt * 2;
                    if (p.life <= 0) particles.splice(i, 1);
                }

                // Update Texts
                for (let i = floatingTexts.length - 1; i >= 0; i--) {
                    let t = floatingTexts[i];
                    t.y -= 100 * dt; // float up
                    t.life -= dt * 1.5;
                    if (t.life <= 0) floatingTexts.splice(i, 1);
                }
            }

            function draw() {
                // Dynamic background based on combo
                const bgIntensity = Math.min(0.2, combo * 0.005);
                ctx.fillStyle = `rgba(10, 10, 12, ${isPlaying ? 0.3 : 1.0})`; // trailing effect
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                const laneWidth = canvas.width / 4;

                // Draw Lanes and Strike Zone
                for (let i = 0; i < 4; i++) {
                    // Lane separator
                    ctx.beginPath();
                    ctx.moveTo(i * laneWidth, 0);
                    ctx.lineTo(i * laneWidth, canvas.height);
                    ctx.strokeStyle = 'rgba(255,255,255,0.05)';
                    ctx.lineWidth = 1;
                    ctx.stroke();

                    // Strike pad visual
                    const padY = STRIKE_ZONE_Y;
                    
                    // Key label
                    ctx.fillStyle = 'rgba(255,255,255,0.3)';
                    ctx.font = 'bold 20px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(laneKeys[i], i * laneWidth + laneWidth/2, padY + STRIKE_ZONE_H + 30);

                    if (activeKeys[i]) {
                        // Glow when pressed
                        const grad = ctx.createLinearGradient(0, padY, 0, canvas.height);
                        grad.addColorStop(0, laneColors[i]);
                        grad.addColorStop(1, 'transparent');
                        ctx.fillStyle = grad;
                        ctx.globalAlpha = 0.5;
                        ctx.fillRect(i * laneWidth, padY, laneWidth, canvas.height - padY);
                        ctx.globalAlpha = 1.0;
                    }
                    
                    // Strike Line
                    ctx.beginPath();
                    ctx.moveTo(0, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                    ctx.lineTo(canvas.width, STRIKE_ZONE_Y + STRIKE_ZONE_H/2);
                    ctx.strokeStyle = 'rgba(255,255,255,0.2)';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                }

                // Draw Notes
                for (let note of notes) {
                    const x = note.lane * laneWidth + laneWidth * 0.1;
                    const w = laneWidth * 0.8;
                    const color = laneColors[note.lane];
                    
                    ctx.fillStyle = color;
                    ctx.shadowColor = color;
                    ctx.shadowBlur = 20;
                    
                    // Neon gradient for note
                    const grad = ctx.createLinearGradient(0, note.y, 0, note.y + note.h);
                    grad.addColorStop(0, 'white');
                    grad.addColorStop(1, color);
                    ctx.fillStyle = grad;
                    
                    // Rounded rect
                    ctx.beginPath();
                    ctx.roundRect(x, note.y, w, note.h, 10);
                    ctx.fill();
                    ctx.shadowBlur = 0;
                }

                // Draw Particles
                for (let p of particles) {
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = Math.max(0, p.life);
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.globalAlpha = 1.0;

                // Draw Texts
                for (let t of floatingTexts) {
                    ctx.fillStyle = t.color;
                    ctx.globalAlpha = Math.max(0, t.life);
                    ctx.font = '900 24px "Inter", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(t.text, t.x, t.y);
                }
                ctx.globalAlpha = 1.0;

                // Draw UI Layer (Combo & Score)
                if (isPlaying) {
                    ctx.fillStyle = '#ffffff';
                    ctx.font = '900 20px "Inter", sans-serif';
                    ctx.textAlign = 'left';
                    ctx.fillText(`SCORE: ${score}`, 20, 40);
                    
                    ctx.textAlign = 'right';
                    ctx.fillStyle = combo > 10 ? '#f59e0b' : '#ffffff';
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

            function stopGame() {
                isPlaying = false;
                cancelAnimationFrame(animationId);
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
                statusText.textContent = `SCORE: ${score}`;
                startBtn.textContent = "Play Again";
            }

            // Input Handling
            window.addEventListener('keydown', (e) => {
                if (!isPlaying) return;
                const keyMap = {'d':0, 'f':1, 'j':2, 'k':3};
                const lane = keyMap[e.key.toLowerCase()];
                if (lane !== undefined) {
                    hitLane(lane);
                }
            });

            // Touch / Click handling for mobile
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
                e.preventDefault(); // prevent scroll
                const rect = canvas.getBoundingClientRect();
                for (let i=0; i < e.changedTouches.length; i++) {
                    const x = e.changedTouches[i].clientX - rect.left;
                    const laneWidth = rect.width / 4;
                    const lane = Math.floor(x / laneWidth);
                    hitLane(lane);
                }
            }, {passive: false});

            startBtn.addEventListener('click', startGame);
            
            // Initial render
            draw();
        });
    </script>
    @endpush
</x-webapp-layout>
