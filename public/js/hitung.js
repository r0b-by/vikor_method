// Animation for calculation steps
    document.addEventListener('DOMContentLoaded', function() {
        // Show loading animation when form is submitted
        const calculationForm = document.getElementById('calculationForm');
        if (calculationForm) {
            calculationForm.addEventListener('submit', function() {
                document.getElementById('loadingOverlay').style.display = 'flex';
                simulateProgress();
            });
        }

        // Animate calculation steps when they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.calculation-step').forEach(step => {
            observer.observe(step);
        });

        // Tab functionality
        const tabs = document.querySelectorAll('[data-tabs-toggle] [role="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-tabs-target'));
                document.querySelectorAll('[role="tabpanel"]').forEach(panel => {
                    panel.classList.add('hidden');
                });
                target.classList.remove('hidden');

                // Update active tab styling
                document.querySelectorAll('[role="tab"]').forEach(t => {
                    t.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-300');
                    t.classList.add('border-transparent', 'hover:text-blue-600', 'hover:border-blue-300', 'dark:hover:text-blue-300');
                });
                this.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-300');
                this.classList.remove('border-transparent', 'hover:text-blue-600', 'hover:border-blue-300', 'dark:hover:text-blue-300');
            });
        });

        // Activate the first tab by default
        if (tabs.length > 0) {
            tabs[0].click();
        }
    });

    // Simulate progress bar animation
    function simulateProgress() {
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const messages = [
            "Memuat data siswa...",
            "Memproses matriks keputusan...",
            "Melakukan normalisasi...",
            "Menghitung nilai Si dan Ri...",
            "Menentukan indeks kompromi Qi...",
            "Menganalisis kondisi stabilitas...",
            "Menyusun peringkat akhir..."
        ];
        
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                setTimeout(() => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                }, 500);
            }
            
            progressBar.style.width = `${progress}%`;
            
            // Update progress text based on progress
            const messageIndex = Math.min(Math.floor(progress / (100 / messages.length)), messages.length - 1);
            progressText.textContent = messages[messageIndex];
            
            // Add some random particles
            if (progress < 100 && Math.random() > 0.7) {
                addParticle();
            }
        }, 300);
    }

    // Add tech particles for loading animation
    function addParticle() {
        const particlesContainer = document.getElementById('techParticles');
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random position
        const x = Math.random() * 100;
        const y = Math.random() * 100;
        particle.style.left = `${x}%`;
        particle.style.top = `${y}%`;
        
        // Random size
        const size = Math.random() * 5 + 2;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        
        // Random opacity
        particle.style.opacity = Math.random() * 0.5 + 0.3;
        
        particlesContainer.appendChild(particle);
        
        // Remove particle after animation
        setTimeout(() => {
            particle.remove();
        }, 1000);
    }