// assets/js/seven-lux.js
/**
 * Seven Lux - Sistema de Temas Interativos
 * 7 faróis/pedras preciosas que transformam o design do site
 */

if (typeof BASE_URL === 'undefined') {
    var BASE_URL = window.location.origin + '/sevenlux/public/';
}

class SevenLux {
    constructor() {
        this.themes = {
            ruby: {
                name: 'Rubi',
                color: '#E74C3C',
                glow: 'rgba(231, 76, 60, 0.6)',
                gradient: 'linear-gradient(135deg, #1a0a0a 0%, #2d0a0a 50%, #1a0505 100%)',
                accent: '#E74C3C',
                secondary: '#C0392B',
                text: '#ffd6d6',
                cardBg: 'rgba(231, 76, 60, 0.08)',
                border: 'rgba(231, 76, 60, 0.3)',
                icon: 'fa-gem',
                btnBg: '#E74C3C',
                btnHover: '#C0392B'
            },
            sapphire: {
                name: 'Safira',
                color: '#3498DB',
                glow: 'rgba(52, 152, 219, 0.6)',
                gradient: 'linear-gradient(135deg, #0a0a1a 0%, #0a1a2d 50%, #050a1a 100%)',
                accent: '#3498DB',
                secondary: '#2980B9',
                text: '#d6edff',
                cardBg: 'rgba(52, 152, 219, 0.08)',
                border: 'rgba(52, 152, 219, 0.3)',
                icon: 'fa-water',
                btnBg: '#3498DB',
                btnHover: '#2980B9'
            },
            emerald: {
                name: 'Esmeralda',
                color: '#2ECC71',
                glow: 'rgba(46, 204, 113, 0.6)',
                gradient: 'linear-gradient(135deg, #0a1a0a 0%, #0a2d1a 50%, #051a0a 100%)',
                accent: '#2ECC71',
                secondary: '#27AE60',
                text: '#d6ffd6',
                cardBg: 'rgba(46, 204, 113, 0.08)',
                border: 'rgba(46, 204, 113, 0.3)',
                icon: 'fa-leaf',
                btnBg: '#2ECC71',
                btnHover: '#27AE60'
            },
            amethyst: {
                name: 'Ametista',
                color: '#9B59B6',
                glow: 'rgba(155, 89, 182, 0.6)',
                gradient: 'linear-gradient(135deg, #1a0a1a 0%, #2d0a2d 50%, #1a051a 100%)',
                accent: '#9B59B6',
                secondary: '#8E44AD',
                text: '#f0d6ff',
                cardBg: 'rgba(155, 89, 182, 0.08)',
                border: 'rgba(155, 89, 182, 0.3)',
                icon: 'fa-crown',
                btnBg: '#9B59B6',
                btnHover: '#8E44AD'
            },
            topaz: {
                name: 'Topázio',
                color: '#F39C12',
                glow: 'rgba(243, 156, 18, 0.6)',
                gradient: 'linear-gradient(135deg, #1a140a 0%, #2d1f0a 50%, #1a0f05 100%)',
                accent: '#F39C12',
                secondary: '#E67E22',
                text: '#fff0d6',
                cardBg: 'rgba(243, 156, 18, 0.08)',
                border: 'rgba(243, 156, 18, 0.3)',
                icon: 'fa-sun',
                btnBg: '#F39C12',
                btnHover: '#E67E22'
            },
            pearl: {
                name: 'Pérola',
                color: '#B8B8B8',
                glow: 'rgba(180, 180, 180, 0.5)',
                gradient: 'linear-gradient(135deg, #1a1a1a 0%, #333333 40%, #2a2a2a 70%, #1a1a1a 100%)',
                accent: '#D0D0D0',
                secondary: '#999999',
                text: '#e8e8e8',
                cardBg: 'rgba(200, 200, 200, 0.06)',
                border: 'rgba(200, 200, 200, 0.15)',
                icon: 'fa-circle',
                btnBg: '#B8B8B8',
                btnHover: '#999999'
            },
            diamond: {
                name: 'Diamante',
                color: '#00D4FF',
                glow: 'rgba(0, 212, 255, 0.6)',
                gradient: 'linear-gradient(135deg, #0a0a1a 0%, #0a1a2d 50%, #0a0a1a 100%)',
                accent: '#00D4FF',
                secondary: '#0099CC',
                text: '#d6f5ff',
                cardBg: 'rgba(0, 212, 255, 0.08)',
                border: 'rgba(0, 212, 255, 0.3)',
                icon: 'fa-star',
                btnBg: '#00D4FF',
                btnHover: '#0099CC'
            }
        };

        this.currentTheme = this.getSavedTheme() || 'ruby';
        this.isTransitioning = false;
        this.particleCanvas = null;
        this.particleCtx = null;
        this.particleAnimationId = null;
        this.particleList = [];
        this.connections = [];
        this.mouseX = null;
        this.mouseY = null;
        this.init();
    }

    getSavedTheme() {
        try {
            return localStorage.getItem('sevenlux_theme') || null;
        } catch (e) {
            return null;
        }
    }

    saveTheme(theme) {
        try {
            localStorage.setItem('sevenlux_theme', theme);
        } catch (e) {}
    }

    init() {
        this.createLuxBeacons();
        this.applyTheme(this.currentTheme);
        this.setupParticleSystem();
        this.setupThemeSelector();
        this.setupMouseTracking();
        setTimeout(() => this.forceBackgroundUpdate(), 50);
    }

    setupMouseTracking() {
        document.addEventListener('mousemove', (e) => {
            this.mouseX = e.clientX;
            this.mouseY = e.clientY;
        });
        document.addEventListener('mouseleave', () => {
            this.mouseX = null;
            this.mouseY = null;
        });
    }

    forceBackgroundUpdate() {
        const theme = this.themes[this.currentTheme];
        if (!theme) return;
        
        document.body.style.setProperty('background', theme.gradient, 'important');
        document.body.style.background = theme.gradient;
        document.documentElement.style.background = theme.gradient;
        
        console.log('🎨 Tema aplicado:', this.currentTheme);
    }

    createLuxBeacons() {
        if (document.getElementById('sevenLuxContainer')) return;

        const container = document.createElement('div');
        container.id = 'sevenLuxContainer';
        container.className = 'seven-lux-container';
        
        const title = document.createElement('div');
        title.className = 'seven-lux-title';
        title.innerHTML = `
            <span class="lux-sparkle">✦</span>
            Seven Lux
            <span class="lux-sparkle">✦</span>
        `;
        container.appendChild(title);

        const themes = Object.keys(this.themes);
        themes.forEach((key, index) => {
            const theme = this.themes[key];
            const beacon = document.createElement('div');
            beacon.className = `lux-beacon ${key} ${key === this.currentTheme ? 'active' : ''}`;
            beacon.dataset.theme = key;
            beacon.style.setProperty('--delay', `${index * 0.15}s`);
            
            beacon.innerHTML = `
                <div class="beacon-glow" style="background: ${theme.glow};"></div>
                <div class="beacon-core" style="background: ${theme.color};">
                    <i class="fas ${theme.icon}"></i>
                </div>
                <div class="beacon-name">${theme.name}</div>
                <div class="beacon-ring"></div>
                <div class="beacon-sparkle"></div>
            `;
            
            beacon.addEventListener('click', () => this.selectTheme(key));
            container.appendChild(beacon);
        });

        document.body.prepend(container);
    }

    selectTheme(themeKey) {
        if (this.isTransitioning || themeKey === this.currentTheme) return;
        this.isTransitioning = true;

        document.querySelectorAll('.lux-beacon').forEach(el => {
            el.classList.toggle('active', el.dataset.theme === themeKey);
        });

        this.applyTheme(themeKey);
        this.saveTheme(themeKey);
        this.currentTheme = themeKey;
        this.forceBackgroundUpdate();

        const activeBeacon = document.querySelector(`.lux-beacon.${themeKey}`);
        if (activeBeacon) {
            activeBeacon.classList.add('pulsing');
            setTimeout(() => activeBeacon.classList.remove('pulsing'), 1500);
        }

        setTimeout(() => {
            this.isTransitioning = false;
        }, 800);
    }

    applyTheme(themeKey) {
        const theme = this.themes[themeKey];
        if (!theme) return;

        const root = document.documentElement;
        
        root.style.setProperty('--theme-primary', theme.color);
        root.style.setProperty('--theme-secondary', theme.secondary);
        root.style.setProperty('--theme-glow', theme.glow);
        root.style.setProperty('--theme-text', theme.text);
        root.style.setProperty('--theme-card-bg', theme.cardBg);
        root.style.setProperty('--theme-border', theme.border);
        root.style.setProperty('--theme-gradient', theme.gradient);
        root.style.setProperty('--theme-accent', theme.accent);
        root.style.setProperty('--theme-btn-bg', theme.btnBg);
        root.style.setProperty('--theme-btn-hover', theme.btnHover);

        document.body.style.transition = 'background 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        document.body.style.background = theme.gradient;
        document.documentElement.style.background = theme.gradient;

        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            metaThemeColor.content = theme.color;
        }

        document.dispatchEvent(new CustomEvent('themeChanged', { 
            detail: { theme: themeKey, data: theme }
        }));

        document.querySelectorAll('.lux-beacon').forEach(el => {
            el.classList.toggle('active', el.dataset.theme === themeKey);
        });
    }

    setupParticleSystem() {
        const canvas = document.createElement('canvas');
        canvas.id = 'luxParticles';
        canvas.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.7;
        `;
        document.body.prepend(canvas);

        this.particleCanvas = canvas;
        this.particleCtx = canvas.getContext('2d');
        this.resizeParticles();
        this.initParticles();
        this.animateParticles();

        window.addEventListener('resize', () => {
            this.resizeParticles();
            this.initParticles();
        });

        document.addEventListener('themeChanged', () => {});
    }

    resizeParticles() {
        if (!this.particleCanvas) return;
        this.particleCanvas.width = window.innerWidth;
        this.particleCanvas.height = window.innerHeight;
    }

    initParticles() {
        if (!this.particleCanvas) return;
        const count = Math.min(120, Math.floor(window.innerWidth / 10));
        this.particleList = [];
        for (let i = 0; i < count; i++) {
            this.particleList.push(this.createParticle());
        }
    }

    createParticle() {
        return {
            x: Math.random() * this.particleCanvas.width,
            y: Math.random() * this.particleCanvas.height,
            size: Math.random() * 3 + 1.5,
            speedX: (Math.random() - 0.5) * 0.4,
            speedY: (Math.random() - 0.5) * 0.4,
            opacity: Math.random() * 0.5 + 0.2,
            pulse: Math.random() * Math.PI * 2,
            pulseSpeed: 0.02 + Math.random() * 0.03,
            baseSize: Math.random() * 2 + 1
        };
    }

    animateParticles() {
        const ctx = this.particleCtx;
        if (!ctx) return;
        
        const theme = this.themes[this.currentTheme] || this.themes.ruby;
        const color = theme.color || '#C6A43F';
        
        ctx.clearRect(0, 0, this.particleCanvas.width, this.particleCanvas.height);
        
        // Desenhar conexões primeiro (por baixo das partículas)
        this.drawConnections(ctx, color);
        
        // Desenhar partículas
        this.particleList.forEach(p => {
            p.x += p.speedX;
            p.y += p.speedY;
            p.pulse += p.pulseSpeed;
            
            // Interação com o mouse
            if (this.mouseX !== null && this.mouseY !== null) {
                const dx = p.x - this.mouseX;
                const dy = p.y - this.mouseY;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 150) {
                    const angle = Math.atan2(dy, dx);
                    const force = (150 - dist) / 150;
                    p.x += Math.cos(angle) * force * 1.5;
                    p.y += Math.sin(angle) * force * 1.5;
                }
            }
            
            if (p.x < -50) p.x = this.particleCanvas.width + 50;
            if (p.x > this.particleCanvas.width + 50) p.x = -50;
            if (p.y < -50) p.y = this.particleCanvas.height + 50;
            if (p.y > this.particleCanvas.height + 50) p.y = -50;
            
            const size = p.baseSize + Math.sin(p.pulse) * 0.5;
            
            // Brilho da partícula (efeito de glow)
            const gradient = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, size * 3);
            gradient.addColorStop(0, color);
            gradient.addColorStop(1, 'transparent');
            
            ctx.beginPath();
            ctx.arc(p.x, p.y, size, 0, Math.PI * 2);
            ctx.fillStyle = color;
            ctx.globalAlpha = p.opacity + Math.sin(p.pulse) * 0.15;
            ctx.fill();
            
            // Glow exterior
            ctx.shadowColor = color;
            ctx.shadowBlur = 15;
            ctx.fill();
            ctx.shadowBlur = 0;
            
            ctx.globalAlpha = 1;
        });
        
        this.particleAnimationId = requestAnimationFrame(() => this.animateParticles());
    }

    drawConnections(ctx, color) {
        const maxDist = 150;
        
        for (let i = 0; i < this.particleList.length; i++) {
            for (let j = i + 1; j < this.particleList.length; j++) {
                const p1 = this.particleList[i];
                const p2 = this.particleList[j];
                const dx = p1.x - p2.x;
                const dy = p1.y - p2.y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                
                if (dist < maxDist) {
                    const opacity = 0.5 * (1 - dist / maxDist);
                    
                    // Linha com glow
                    ctx.beginPath();
                    ctx.moveTo(p1.x, p1.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.strokeStyle = color;
                    ctx.globalAlpha = opacity;
                    ctx.lineWidth = 1.2;
                    ctx.shadowColor = color;
                    ctx.shadowBlur = 8;
                    ctx.stroke();
                    ctx.shadowBlur = 0;
                    ctx.globalAlpha = 1;
                }
            }
        }
    }

    setupThemeSelector() {
        const themes = Object.keys(this.themes);
        document.addEventListener('keydown', (e) => {
            const num = parseInt(e.key);
            if (num >= 1 && num <= 7 && themes[num - 1]) {
                this.selectTheme(themes[num - 1]);
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (!window.sevenLux) {
        window.sevenLux = new SevenLux();
    }
});

window.SevenLux = SevenLux;