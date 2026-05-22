<x-webapp-layout
    title="Neon Memory Challenge | NCS Hindi"
    description="Play the interactive Neon Memory Challenge mini-game. Test your rhythmic memory!"
>
    <div class="max-w-4xl mx-auto py-8">
        {{-- Header & Score Card --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12">
            <div>
                <h1 class="font-brand text-4xl md:text-5xl font-black italic uppercase text-white tracking-tighter">
                    Neon <span class="text-amber-500">Memory</span>
                </h1>
                <p class="text-zinc-400 text-sm mt-2 font-medium tracking-wide">Test your rhythmic memory in this Simon Says challenge.</p>
            </div>
            
            {{-- Live Score Card --}}
            <div class="flex items-center gap-4 bg-zinc-900/60 backdrop-blur-md p-4 rounded-3xl border border-zinc-800 shadow-2xl">
                <div class="text-center px-4 border-r border-zinc-800">
                    <p class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-1">Score</p>
                    <p id="current-score" class="text-3xl font-black text-white font-brand">0</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-1">High Score</p>
                    <p id="high-score" class="text-3xl font-black text-amber-500 font-brand">0</p>
                </div>
            </div>
        </div>

        {{-- Game Container --}}
        <div class="relative bg-zinc-900/40 backdrop-blur-sm border border-zinc-800/50 rounded-[3rem] p-8 md:p-16 flex flex-col items-center justify-center min-h-[500px]">
            
            {{-- Status Overlay --}}
            <div id="game-overlay" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-black/60 backdrop-blur-sm rounded-[3rem] transition-opacity duration-300">
                <h2 id="game-status" class="text-3xl font-black text-white uppercase tracking-widest mb-6">Ready to play?</h2>
                <button id="start-btn" class="px-8 py-4 bg-amber-500 text-black text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-amber-400 hover:scale-105 transition-all shadow-[0_0_30px_rgba(245,158,11,0.3)] outline-none">
                    Start Game
                </button>
            </div>

            {{-- Simon Pads Grid --}}
            <div class="grid grid-cols-2 gap-4 md:gap-8 w-full max-w-md relative z-0">
                {{-- Top Left: Red (C4) --}}
                <div class="simon-pad relative w-full aspect-square bg-red-950/50 border-4 border-red-900/50 rounded-3xl cursor-pointer transition-all duration-200 shadow-inner" 
                     data-color="red" data-note="261.63">
                     <div class="absolute inset-0 rounded-2xl bg-red-500 opacity-0 transition-opacity duration-150 shadow-[0_0_50px_rgba(239,68,68,0.8)] pointer-events-none highlight-layer"></div>
                </div>
                
                {{-- Top Right: Blue (E4) --}}
                <div class="simon-pad relative w-full aspect-square bg-blue-950/50 border-4 border-blue-900/50 rounded-3xl cursor-pointer transition-all duration-200 shadow-inner" 
                     data-color="blue" data-note="329.63">
                     <div class="absolute inset-0 rounded-2xl bg-blue-500 opacity-0 transition-opacity duration-150 shadow-[0_0_50px_rgba(59,130,246,0.8)] pointer-events-none highlight-layer"></div>
                </div>
                
                {{-- Bottom Left: Yellow (G4) --}}
                <div class="simon-pad relative w-full aspect-square bg-yellow-950/50 border-4 border-yellow-900/50 rounded-3xl cursor-pointer transition-all duration-200 shadow-inner" 
                     data-color="yellow" data-note="392.00">
                     <div class="absolute inset-0 rounded-2xl bg-yellow-400 opacity-0 transition-opacity duration-150 shadow-[0_0_50px_rgba(250,204,21,0.8)] pointer-events-none highlight-layer"></div>
                </div>
                
                {{-- Bottom Right: Green (C5) --}}
                <div class="simon-pad relative w-full aspect-square bg-green-950/50 border-4 border-green-900/50 rounded-3xl cursor-pointer transition-all duration-200 shadow-inner" 
                     data-color="green" data-note="523.25">
                     <div class="absolute inset-0 rounded-2xl bg-green-500 opacity-0 transition-opacity duration-150 shadow-[0_0_50px_rgba(34,197,94,0.8)] pointer-events-none highlight-layer"></div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Audio Context Setup
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            let audioCtx;
            
            // UI Elements
            const pads = document.querySelectorAll('.simon-pad');
            const startBtn = document.getElementById('start-btn');
            const gameOverlay = document.getElementById('game-overlay');
            const gameStatus = document.getElementById('game-status');
            const currentScoreEl = document.getElementById('current-score');
            const highScoreEl = document.getElementById('high-score');
            
            // Game State
            let sequence = [];
            let playerSequence = [];
            let score = 0;
            let highScore = localStorage.getItem('ncs_simon_high_score') || 0;
            let isPlayingSequence = false;
            let waitingForPlayer = false;
            
            // Initialize High Score
            highScoreEl.textContent = highScore;

            // Audio Synthesis for Synth sounds
            function playNote(frequency) {
                if (!audioCtx) audioCtx = new AudioContext();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                
                oscillator.type = 'sine'; // Smooth synth sound
                oscillator.frequency.value = frequency;
                
                // Envelope
                gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.5, audioCtx.currentTime + 0.05); // Attack
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5); // Decay
                
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                
                oscillator.start(audioCtx.currentTime);
                oscillator.stop(audioCtx.currentTime + 0.5);
            }

            // Pad Animation & Sound
            function activatePad(pad) {
                const highlight = pad.querySelector('.highlight-layer');
                const freq = parseFloat(pad.dataset.note);
                
                // Visual
                highlight.style.opacity = '1';
                pad.style.transform = 'scale(0.95)';
                
                // Sound
                playNote(freq);
                
                setTimeout(() => {
                    highlight.style.opacity = '0';
                    pad.style.transform = 'scale(1)';
                }, 300);
            }

            // Game Logic
            async function nextRound() {
                score++;
                currentScoreEl.textContent = score;
                playerSequence = [];
                isPlayingSequence = true;
                waitingForPlayer = false;
                
                // Add random pad to sequence
                const colors = ['red', 'blue', 'yellow', 'green'];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                sequence.push(randomColor);
                
                // Update UI temporarily while playing
                setTimeout(async () => {
                    for (let i = 0; i < sequence.length; i++) {
                        const padColor = sequence[i];
                        const pad = document.querySelector(`[data-color="${padColor}"]`);
                        activatePad(pad);
                        // Delay between notes, gets slightly faster as sequence grows
                        const delay = Math.max(300, 600 - (sequence.length * 15));
                        await new Promise(r => setTimeout(r, delay));
                    }
                    isPlayingSequence = false;
                    waitingForPlayer = true;
                }, 800);
            }

            function gameOver() {
                waitingForPlayer = false;
                
                // Play error sound (dissonant chord)
                playNote(150);
                setTimeout(() => playNote(140), 100);
                
                // Update High Score
                if (score - 1 > highScore) {
                    highScore = score - 1;
                    localStorage.setItem('ncs_simon_high_score', highScore);
                    highScoreEl.textContent = highScore;
                }
                
                // Reset State
                gameStatus.textContent = `Game Over! Score: ${score - 1}`;
                startBtn.textContent = 'Play Again';
                gameOverlay.classList.remove('hidden');
                gameOverlay.style.opacity = '1';
                gameOverlay.style.pointerEvents = 'auto';
            }

            // Event Listeners
            startBtn.addEventListener('click', () => {
                if (!audioCtx) audioCtx = new AudioContext();
                audioCtx.resume();
                
                sequence = [];
                score = 0;
                currentScoreEl.textContent = '0';
                
                gameOverlay.style.opacity = '0';
                gameOverlay.style.pointerEvents = 'none';
                setTimeout(() => gameOverlay.classList.add('hidden'), 300);
                
                nextRound();
            });

            pads.forEach(pad => {
                // Prevent default touch behavior
                pad.addEventListener('touchstart', (e) => e.preventDefault(), {passive: false});
                
                const handleInteraction = (e) => {
                    e.preventDefault();
                    if (!waitingForPlayer || isPlayingSequence) return;
                    
                    const color = pad.dataset.color;
                    activatePad(pad);
                    playerSequence.push(color);
                    
                    // Check against sequence
                    const currentIndex = playerSequence.length - 1;
                    if (playerSequence[currentIndex] !== sequence[currentIndex]) {
                        gameOver();
                        return;
                    }
                    
                    // Completed sequence
                    if (playerSequence.length === sequence.length) {
                        waitingForPlayer = false;
                        setTimeout(nextRound, 1000);
                    }
                };

                pad.addEventListener('mousedown', handleInteraction);
                pad.addEventListener('touchstart', handleInteraction);
            });
        });
    </script>
    @endpush
</x-webapp-layout>
