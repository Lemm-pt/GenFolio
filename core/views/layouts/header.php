<?php use core\classes\Store; ?>
<?php 
// Carregar configurações para o menu
if(!isset($config)) {
    $config = new \core\models\Configuracao();
}
?>
<canvas id="particlesCanvas"></canvas>

<nav class="navegacao">
    <div class="container">
        <div class="row align-items-center g-0">
            <div class="col-6 col-md-3">
                <a href="?a=inicio" class="logo">
                    <h3><span class="logo-parte1"><?= htmlspecialchars($config->get('logo_parte1', 'Jo')) ?></span><span class="logo-parte2"><?= htmlspecialchars($config->get('logo_parte2', 'Folio')) ?></span></h3>
                </a>
            </div>
            <div class="col-6 col-md-9 text-end">
                <button class="menu-hamburger d-md-none" id="menuHamburger">
                    <i class="fas fa-bars fa-2x"></i>
                </button>
                <div class="menu-links d-none d-md-inline" id="menuLinks">
                    <a href="?a=inicio">Início</a>
                    <a href="#servicos">Serviços</a>
                    <a href="#itens">Itens</a>
                    <a href="?a=blog">Blog</a>
                    <a href="?a=contacto">Contacto</a>
                    <?php if(Store::adminLogado()): ?>
                        <a href="?a=admin">Admin</a>
                        <a href="?a=admin_logout">Sair</a>
                    <?php else: ?>
                        <a href="?a=admin_login">Área Restrita</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<div id="mobileMenuPanel" class="mobile-menu-panel d-md-none">
    <a href="?a=inicio">Início</a>
    <a href="#servicos">Serviços</a>
    <a href="#itens">Itens</a>
    <a href="?a=blog">Blog</a>
    <a href="?a=contacto">Contacto</a>
    <?php if(Store::adminLogado()): ?>
        <a href="?a=admin">Admin</a>
        <a href="?a=admin_logout">Sair</a>
    <?php else: ?>
        <a href="?a=admin_login">Área Restrita</a>
    <?php endif; ?>
</div>

<!-- SCRIPT DAS PARTÍCULAS / BOLHAS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('particlesCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let particles = [];
    let mouseX = null, mouseY = null;

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 4 + 1;
            this.speedX = (Math.random() - 0.5) * 0.5;
            this.speedY = (Math.random() - 0.5) * 0.5;
            this.color = `rgba(198, 164, 63, ${Math.random() * 0.3 + 0.1})`;
            this.originalX = this.x;
            this.originalY = this.y;
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            if (mouseX === null) {
                this.x += (this.originalX - this.x) * 0.02;
                this.y += (this.originalY - this.y) * 0.02;
            } else {
                const dx = this.x - mouseX;
                const dy = this.y - mouseY;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 150) {
                    const angle = Math.atan2(dy, dx);
                    const force = (150 - dist) / 150;
                    this.x += Math.cos(angle) * force * 2;
                    this.y += Math.sin(angle) * force * 2;
                } else {
                    this.x += (this.originalX - this.x) * 0.02;
                    this.y += (this.originalY - this.y) * 0.02;
                }
            }
            if (this.x < 0) this.x = canvas.width;
            if (this.x > canvas.width) this.x = 0;
            if (this.y < 0) this.y = canvas.height;
            if (this.y > canvas.height) this.y = 0;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = this.color;
            ctx.fill();
            ctx.shadowBlur = 10;
            ctx.shadowColor = 'rgba(198, 164, 63, 0.5)';
            ctx.fill();
            ctx.shadowBlur = 0;
        }
    }

    function init() {
        resizeCanvas();
        particles = [];
        const particleCount = Math.min(100, Math.floor(window.innerWidth * 0.1));
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        gradient.addColorStop(0, '#0a0a1a');
        gradient.addColorStop(1, '#1a1a2e');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Conexões
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 120) {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(198, 164, 63, ${0.1 * (1 - dist / 120)})`;
                    ctx.lineWidth = 0.5;
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }

        particles.forEach(p => {
            p.update();
            p.draw();
        });

        requestAnimationFrame(animate);
    }

    window.addEventListener('resize', () => {
        resizeCanvas();
        init();
    });
    window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });
    window.addEventListener('mouseleave', () => {
        mouseX = mouseY = null;
    });
    init();
    animate();
});

// Menu hambúrguer (já existia, mantém)
document.getElementById('menuHamburger')?.addEventListener('click', function() {
    document.getElementById('mobileMenuPanel').classList.toggle('show');
});
</script>

<style>
#particlesCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    pointer-events: none;
}
.navegacao {
    background: rgba(10,10,26,0.85);
    backdrop-filter: blur(12px);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(198,164,63,0.2);
}
.navegacao .logo h3 {
    font-size: 1.5rem;
    margin: 0;
    color: white;
}
.logo-parte2 { color: #C6A43F; }
.navegacao a {
    color: white;
    text-decoration: none;
    margin: 0 0.8rem;
    transition: color 0.2s;
}
.navegacao a:hover { color: #C6A43F; }
.menu-hamburger {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
}
.mobile-menu-panel {
    position: fixed;
    top: 60px;
    left: 0;
    width: 100%;
    background: rgba(10,10,26,0.98);
    backdrop-filter: blur(10px);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    transform: translateY(-120%);
    transition: transform 0.3s ease;
    z-index: 999;
}
.mobile-menu-panel.show { transform: translateY(0); }
.mobile-menu-panel a {
    color: white;
    text-decoration: none;
    padding: 0.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.container { margin-top: 70px; }
@media (max-width: 768px) {
    .container { margin-top: 60px; }
}
</style>