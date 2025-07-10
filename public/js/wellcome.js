// Initialize AOS animation
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true
    });

    // Page navigation function
    function showPage(pageId) {
      // Hide all pages
      document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
      });
      
      // Show selected page
      document.getElementById(pageId + '-page').classList.add('active');
      
      // Update active nav link
      document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
      });
      document.querySelector(`.nav-link[onclick="showPage('${pageId}')"]`).classList.add('active');
      
      // Add scroll effect to navbar
      window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });
    }

     // Black Hole Effect with SPK Text
    document.addEventListener('DOMContentLoaded', function() {
        const particleCount = window.innerWidth < 768 ? 300 : 800;
        const eventHorizon = window.innerWidth < 768 ? 25 : 40;
        const gravStrength = 50;

        const mainCanvas = document.getElementById('blackHoleCanvas');
        let ctx, buffer;
        let center, particles, mouse, hover;

        // Utility functions
        const TAU = Math.PI * 2;
        const HALF_PI = Math.PI / 2;

        function rand(max) { return Math.random() * max; }
        function randIn(min, max) { return Math.random() * (max - min) + min; }
        function dist(x1, y1, x2, y2) { return Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2)); }
        function angle(x1, y1, x2, y2) { return Math.atan2(y2 - y1, x2 - x1); }
        function fadeInOut(life, ttl) {
            const p = life / ttl;
            return p < 0.5 ? 2 * p : 2 * (1 - p);
        }
        function fadeOut(value, max) {
            return Math.max(0, 1 - (value / max));
        }
        function lerp(start, end, amt) {
            return (1 - amt) * start + amt * end;
        }

        class Particle {
            constructor() {
                this.init();
            }
            get color() {
                return `hsla(${this.hue}, 50%, 80%, ${fadeInOut(this.life, this.ttl)})`;
            }
            init() {
                this.life = 0;
                this.ttl = randIn(50, 200);
                this.speed = randIn(3, 5);
                this.size = randIn(0.5, 2);
                this.position = [rand(buffer.canvas.width), rand(buffer.canvas.height)];
                this.lastPosition = [...this.position];
                this.direction = angle(...this.position, ...center) - HALF_PI;
                this.velocity = [
                    Math.cos(this.direction) * this.speed,
                    Math.sin(this.direction) * this.speed
                ];
                this.hue = rand(360);
                this.reset = false;
            }
            die() {
                buffer.save();
                buffer.globalAlpha = 0.1;
                buffer.lineWidth = 1;
                buffer.strokeStyle = this.color;
                buffer.beginPath();
                buffer.arc(...center, eventHorizon, 0, TAU);
                buffer.closePath();
                buffer.stroke();
                buffer.restore();

                this.init();
            }
            update() {
                this.lastPosition = [...this.position];
                this.direction = lerp(
                    angle(...this.lastPosition, ...center),
                    angle(...this.position, ...center),
                    0.01
                );
                this.speed = fadeOut(dist(...this.position, ...center), buffer.canvas.width) * gravStrength;
                this.velocity = [
                    this.velocity[0] + (Math.cos(this.direction) * this.speed - this.velocity[0]) * 0.01,
                    this.velocity[1] + (Math.sin(this.direction) * this.speed - this.velocity[1]) * 0.01
                ];
                this.position[0] += this.velocity[0];
                this.position[1] += this.velocity[1];

                if (this.life++ > this.ttl) this.init();
                if (dist(...this.position, ...center) <= eventHorizon) this.die();

                return this;
            }
            draw() {
                buffer.save();
                buffer.lineWidth = this.size;
                buffer.strokeStyle = this.color;
                buffer.beginPath();
                buffer.moveTo(...this.lastPosition);
                buffer.lineTo(...this.position);
                buffer.stroke();
                buffer.closePath();
                buffer.restore();

                return this;
            }
        }

        function setup() {
            if (!mainCanvas) return;
            
            ctx = mainCanvas.getContext('2d');
            buffer = document.createElement('canvas').getContext('2d');
            
            center = [0, 0];
            mouse = [0, 0];
            hover = false;
            
            resize();
            createParticles();
            draw();
        }

        function createParticles() {
            particles = [];
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }

        function resize() {
            if (!mainCanvas || !buffer) return;

            const container = mainCanvas.parentElement;
            if (container) {
                const containerWidth = container.clientWidth;
                const containerHeight = container.clientHeight;

                mainCanvas.width = buffer.canvas.width = containerWidth;
                mainCanvas.height = buffer.canvas.height = containerHeight;

                center[0] = 0.5 * containerWidth;
                center[1] = 0.5 * containerHeight;
            }
        }

        function renderToScreen() {
            if (!ctx || !buffer) return;
            
            ctx.save();
            ctx.filter = window.innerWidth < 768 ? "blur(3px) saturate(150%)" : "blur(5px) saturate(200%) contrast(200%)";
            ctx.drawImage(buffer.canvas, 0, 0, mainCanvas.width, mainCanvas.height);
            ctx.restore();

            ctx.save();
            ctx.globalCompositeOperation = "lighter";
            ctx.drawImage(buffer.canvas, 0, 0, mainCanvas.width, mainCanvas.height);
            ctx.restore();
        }

        function draw() {
            if (!buffer || !ctx) {
                window.requestAnimationFrame(draw);
                return;
            }

            buffer.clearRect(0, 0, buffer.canvas.width, buffer.canvas.height);

            buffer.save();
            buffer.beginPath();
            buffer.filter = "blur(2px)";
            buffer.fillStyle = "rgba(0,0,0,0.1)";
            buffer.arc(...center, eventHorizon, 0, TAU);
            buffer.fill();
            buffer.closePath();
            buffer.restore();

            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            ctx.fillStyle = "rgba(0,0,0,0.5)";
            ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);

            // Smoothly move center to mouse position or canvas center
            center[0] = lerp(center[0], hover ? mouse[0] : 0.5 * buffer.canvas.width, 0.05);
            center[1] = lerp(center[1], hover ? mouse[1] : 0.5 * buffer.canvas.height, 0.05);

            for (let i = 0; i < particles.length; i++) {
                particles[i].draw().update();
            }

            renderToScreen();
            window.requestAnimationFrame(draw);
        }

        // Initialize everything when the DOM is loaded
        setup();
        
        // Handle resize events with debounce
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                resize();
            }, 100);
        });
    });