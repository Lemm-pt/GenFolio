<?php use core\classes\Store; ?>
<?php 
if(!isset($config)) {
    $config = new \core\models\Configuracao();
}
?>
<canvas id="particlesCanvas"></canvas>

<nav class="navbar-modern">
    <div class="nav-container">
        <a href="?a=inicio" class="logo">
            <span class="logo-part1"><?= htmlspecialchars($config->get('logo_parte1', 'Vitrine')) ?></span><span class="logo-part2"><?= htmlspecialchars($config->get('logo_parte2', '.lemm')) ?></span>
        </a>
        
        <button class="nav-toggle" id="navToggle">
            <span></span><span></span><span></span>
        </button>
        
        <div class="nav-menu" id="navMenu">
    <a href="?a=inicio" class="nav-link">Início</a>
    
    <!-- Links âncora só funcionam na home, senão vão para home + âncora -->
    <?php if(basename($_SERVER['REQUEST_URI']) === 'index.php' && (!isset($_GET['a']) || $_GET['a'] === 'inicio' || $_GET['a'] === '')): ?>
        <a href="#servicos" class="nav-link">Serviços</a>
        <a href="#produtos" class="nav-link">Produtos</a>
        <a href="#mapa" class="nav-link">Onde Estamos</a>
        <a href="#contacto" class="nav-link">Contacto</a>
    <?php else: ?>
        <a href="?a=inicio#servicos" class="nav-link">Serviços</a>
        <a href="?a=inicio#produtos" class="nav-link">Produtos</a>
        <a href="?a=inicio#mapa" class="nav-link">Onde Estamos</a>
        <a href="?a=inicio#contacto" class="nav-link">Contacto</a>
    <?php endif; ?>
    
    <a href="?a=blog" class="nav-link">Blog</a>
    
    <?php if(Store::adminLogado()): ?>
        <a href="?a=admin" class="nav-link admin-link"><i class="fas fa-crown"></i> Admin</a>
        <a href="?a=admin_logout" class="nav-link"><i class="fas fa-sign-out-alt"></i></a>
    <?php else: ?>
        <a href="?a=admin_login" class="nav-link login-link"><i class="fas fa-lock"></i> Entrar</a>
    <?php endif; ?>
</div>
    </div>
</nav>

<!-- Espaçador -->
<div style="height: 80px;"></div>

<style>
/* Reset e fonts */
* { margin: 0; padding: 0; box-sizing: border-box; }

/* Canvas partículas */
#particlesCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    pointer-events: none;
}

/* Navbar moderna */
.navbar-modern {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    padding: 0.75rem 0;
    background: rgba(10, 10, 26, 0.75);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(198, 164, 63, 0.15);
    transition: all 0.3s ease;
}

.navbar-modern.scrolled {
    padding: 0.4rem 0;
    background: rgba(10, 10, 26, 0.95);
    backdrop-filter: blur(25px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.nav-container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Logo */
.logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: -0.5px;
}

.logo-part1 { color: #ffffff; }
.logo-part2 { color: #C6A43F; }

/* Menu desktop */
.nav-menu {
    display: flex;
    gap: 0.2rem;
    align-items: center;
}

.nav-link {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 40px;
    transition: all 0.2s ease;
}

.nav-link:hover {
    color: #C6A43F;
    background: rgba(198, 164, 63, 0.1);
}

.admin-link {
    background: rgba(198, 164, 63, 0.15);
    color: #C6A43F;
}

.admin-link:hover {
    background: #C6A43F;
    color: #0a0a1a;
}

.login-link {
    border: 1px solid rgba(198, 164, 63, 0.3);
}

/* Botão hambúrguer */
.nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
}

.nav-toggle span {
    width: 24px;
    height: 2px;
    background: white;
    transition: all 0.3s ease;
}

.nav-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}
.nav-toggle.active span:nth-child(2) { opacity: 0; }
.nav-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
}

/* Mobile */
@media (max-width: 768px) {
    .nav-container { padding: 0 1.2rem; }
    .nav-toggle { display: flex; }
    .nav-menu {
        position: fixed;
        top: 65px;
        left: -100%;
        width: 100%;
        background: rgba(10, 10, 26, 0.98);
        backdrop-filter: blur(20px);
        flex-direction: column;
        padding: 1.5rem;
        gap: 0.5rem;
        transition: left 0.3s ease;
    }
    .nav-menu.active { left: 0; }
    .nav-link { width: 100%; text-align: center; padding: 0.8rem; }
    .logo { font-size: 1.3rem; }
}
</style>

<script>
// Menu mobile
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navToggle.classList.toggle('active');
        navMenu.classList.toggle('active');
    });
}

// Fechar menu ao clicar
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        navToggle?.classList.remove('active');
        navMenu?.classList.remove('active');
    });
});

// Scroll effect
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar-modern');
    if (window.scrollY > 20) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
});

// Scroll suave para âncoras
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        if(targetId === '#' || targetId === '') return;
        const target = document.querySelector(targetId);
        if(target) {
            e.preventDefault();
            const navbarHeight = document.querySelector('.navbar-modern')?.offsetHeight || 80;
            const targetPos = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
            window.scrollTo({ top: targetPos, behavior: 'smooth' });
        }
    });
});

// Partículas
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('particlesCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let particles = [], mouseX = null, mouseY = null;

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1;
            this.speedX = (Math.random() - 0.5) * 0.3;
            this.speedY = (Math.random() - 0.5) * 0.3;
            this.color = `rgba(198, 164, 63, ${Math.random() * 0.25 + 0.05})`;
            this.originalX = this.x;
            this.originalY = this.y;
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            if (mouseX === null) {
                this.x += (this.originalX - this.x) * 0.01;
                this.y += (this.originalY - this.y) * 0.01;
            } else {
                const dx = this.x - mouseX, dy = this.y - mouseY, dist = Math.sqrt(dx*dx + dy*dy);
                if (dist < 120) {
                    const angle = Math.atan2(dy, dx);
                    const force = (120 - dist) / 120;
                    this.x += Math.cos(angle) * force * 1.5;
                    this.y += Math.sin(angle) * force * 1.5;
                }
            }
            if (this.x < -50) this.x = canvas.width + 50;
            if (this.x > canvas.width + 50) this.x = -50;
            if (this.y < -50) this.y = canvas.height + 50;
            if (this.y > canvas.height + 50) this.y = -50;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = this.color;
            ctx.fill();
        }
    }

    function init() {
        resizeCanvas();
        particles = [];
        const count = Math.min(80, Math.floor(window.innerWidth / 15));
        for (let i = 0; i < count; i++) particles.push(new Particle());
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        grad.addColorStop(0, '#0a0a1a');
        grad.addColorStop(1, '#16162a');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x, dy = particles[i].y - particles[j].y, dist = Math.sqrt(dx*dx + dy*dy);
                if (dist < 100) {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(198, 164, 63, ${0.08 * (1 - dist / 100)})`;
                    ctx.lineWidth = 0.5;
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }
        particles.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(animate);
    }

    window.addEventListener('resize', () => { resizeCanvas(); init(); });
    window.addEventListener('mousemove', (e) => { mouseX = e.clientX; mouseY = e.clientY; });
    window.addEventListener('mouseleave', () => { mouseX = mouseY = null; });
    init();
    animate();
});
</script>