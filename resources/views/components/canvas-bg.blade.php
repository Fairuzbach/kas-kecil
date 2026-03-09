<canvas id="bg-canvas"></canvas>
<div class="top-stripe"></div>

<script>
    (() => {
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');
        const TOKENS = ['IDR', 'Rp', '%', '∑', '✓', '↑', '→', 'Invoice', 'Nota', 'SPJ', 'BKU', 'RAB', 'Q4', 'APB',
            'SKU'
        ];
        const COLS = [
            [26, 59, 140],
            [208, 34, 42],
            [201, 154, 0]
        ];
        let W, H, pts = [];

        function resize() {
            W = canvas.width = window.innerWidth;
            H = canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', () => {
            resize();
            spawn();
        });

        class P {
            constructor() {
                this.reset(true);
            }
            reset(init) {
                this.x = Math.random() * W;
                this.y = init ? Math.random() * H : H + 20;
                this.vy = -(0.08 + Math.random() * .18);
                this.vx = (Math.random() - .5) * .07;
                this.sz = 7 + Math.random() * 8;
                this.maxA = .04 + Math.random() * .03;
                this.a = 0;
                this.text = TOKENS[Math.floor(Math.random() * TOKENS.length)];
                this.col = COLS[Math.floor(Math.random() * COLS.length)];
                this.life = 0;
                this.maxL = 400 + Math.random() * 350;
            }
            tick() {
                this.x += this.vx;
                this.y += this.vy;
                this.life++;
                const t = this.life / this.maxL;
                this.a = t < .1 ? (t / .1) * this.maxA : t > .8 ? ((1 - t) / .2) * this.maxA : this.maxA;
                if (this.life > this.maxL) this.reset();
            }
            draw() {
                ctx.save();
                ctx.globalAlpha = this.a;
                ctx.font = `600 ${this.sz}px 'Plus Jakarta Sans', sans-serif`;
                const [r, g, b] = this.col;
                ctx.fillStyle = `rgba(${r},${g},${b},1)`;
                ctx.fillText(this.text, this.x, this.y);
                ctx.restore();
            }
        }

        function spawn() {
            pts = Array.from({
                length: Math.max(16, Math.floor(W * H / 28000))
            }, () => new P());
        }
        spawn();

        (function loop() {
            ctx.clearRect(0, 0, W, H);
            pts.forEach(p => {
                p.tick();
                p.draw();
            });
            requestAnimationFrame(loop);
        })();
    })();
</script>
