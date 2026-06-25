<?php use core\classes\Store; ?>


<?php 
if(!isset($config)) {
    $config = new \core\models\Configuracao();
}
$base_url = Store::getBaseUrl();
?>
<canvas id="particlesCanvas"></canvas>

<nav class="navbar-modern">
    <div class="nav-container">

    <?php 
$base_url = BASE_URL . CLIENTE_SLUG . '/';
?>
       <a href="<?= $base_url ?>" class="logo">
           <?php if($config->get('logo_imagem')): ?>
               <img src="<?= BASE_URL ?>assets/images/<?= $config->get('logo_imagem') ?>" alt="Logotipo" style="height: 40px; width: auto; margin-right: 8px;">
           <?php endif; ?>
           <span class="logo-part1"><?= htmlspecialchars($config->get('logo_parte1', 'Seven')) ?></span><span class="logo-part2"><?= htmlspecialchars($config->get('logo_parte2', 'Lux')) ?></span>
       </a>
        
        <button class="nav-toggle" id="navToggle">
            <span></span><span></span><span></span>
        </button>
        
        <div class="nav-menu" id="navMenu">
            <a href="<?= $base_url ?>" class="nav-link">Início</a>
            <a href="<?= $base_url ?>#servicos" class="nav-link">Serviços</a>
            <a href="<?= $base_url ?>#produtos" class="nav-link">Produtos</a>
            <a href="<?= $base_url ?>#galeria" class="nav-link">Galeria</a>
            <a href="<?= $base_url ?>blog" class="nav-link">Blog</a>
            <a href="<?= $base_url ?>#mapa" class="nav-link">Onde Estamos</a>
            <a href="<?= $base_url ?>#contacto" class="nav-link">Contacto</a>
            
            <?php if(Store::adminLogado()): ?>
                <a href="<?= $base_url ?>admin" class="nav-link admin-link"><i class="fas fa-crown"></i> Admin</a>
                <a href="<?= $base_url ?>logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Sair</a>
            <?php else: ?>
                <a href="<?= $base_url ?>admin_login" class="nav-link login-link"><i class="fas fa-lock"></i> Entrar</a>
                <?php 
                // Mostrar "Criar Conta" apenas para o cliente demo (vitrine-demo)
                $cliente_slug_atual = Store::getClienteSlug();
                if($cliente_slug_atual === 'vitrine-demo'): ?>
                    <a href="<?= Store::getUrl('novo_cliente') ?>" class="nav-link">Criar Conta</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</nav>

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
    z-index: 0;  /* Garantir que fica atrás do conteúdo */
    pointer-events: none;
    display: block;
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
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.logo img {
    max-height: 40px;
    width: auto;
}

.logo-part1 { color: #ffffff; }
.logo-part2 { color: #C6A43F; }

/* Menu desktop - ALINHADO À DIREITA */
.nav-menu {
    display: flex;
    gap: 0.2rem;
    align-items: center;
    margin-left: auto;
}

.nav-link {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 40px;
    transition: all 0.2s ease;
    white-space: nowrap;
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
    padding: 0.3rem;
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
        margin-left: 0;
    }
    .nav-menu.active { left: 0; }
    .nav-link { width: 100%; text-align: center; padding: 0.8rem; white-space: normal; }
    .logo { font-size: 1.3rem; }
}


#particlesCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    pointer-events: none;
    filter: drop-shadow(0 0 2px rgba(198,164,63,0.2));
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
document.querySelectorAll('a[href*="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        const hashIndex = href.indexOf('#');
        if(hashIndex === -1) return;
        
        const hash = href.substring(hashIndex);
        const target = document.querySelector(hash);
        
        if(target) {
            e.preventDefault();
            // Se não estiver na home, redireciona
            if(!window.location.href.includes('?a=inicio') && window.location.pathname !== '/sevenlux/public/' && window.location.pathname !== '/sevenlux/public/index.php') {
                window.location.href = href;
                return;
            }
            const navbarHeight = document.querySelector('.navbar-modern')?.offsetHeight || 80;
            const targetPos = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
            window.scrollTo({ top: targetPos, behavior: 'smooth' });
        }
    });
});

// Partículas suaves com conexões visíveis (estilo original)
// Partículas com conexões ligeiramente mais destacadas
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('particlesCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let particles = [];
    let mouseX = null, mouseY = null;
    let animationId = null;
    let time = 0;

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    class Particle {
        constructor() {
            this.reset();
            this.pulseSpeed = 0.01 + Math.random() * 0.02;
            this.pulsePhase = Math.random() * Math.PI * 2;
        }
        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.baseSize = Math.random() * 2.8 + 1.2; // ligeiramente maior (max 4.0)
            this.size = this.baseSize;
            this.speedX = (Math.random() - 0.5) * 0.2;
            this.speedY = (Math.random() - 0.5) * 0.2;
            const goldHue = 45 + (Math.random() * 10 - 5);
            this.color = `hsla(${goldHue}, 70%, 55%, ${Math.random() * 0.25 + 0.1})`;
            this.originalX = this.x;
            this.originalY = this.y;
        }
        update(mouseX, mouseY) {
            this.size = this.baseSize + Math.sin(time * this.pulseSpeed + this.pulsePhase) * 0.3;
            this.x += this.speedX;
            this.y += this.speedY;
            
            if (mouseX === null) {
                this.x += (this.originalX - this.x) * 0.005;
                this.y += (this.originalY - this.y) * 0.005;
            } else {
                const dx = this.x - mouseX;
                const dy = this.y - mouseY;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 100) {
                    const angle = Math.atan2(dy, dx);
                    const force = (100 - dist) / 100;
                    this.x += Math.cos(angle) * force * 1.2;
                    this.y += Math.sin(angle) * force * 1.2;
                } else {
                    this.x += (this.originalX - this.x) * 0.008;
                    this.y += (this.originalY - this.y) * 0.008;
                }
            }
            
            if (this.x < -40) this.x = canvas.width + 40;
            if (this.x > canvas.width + 40) this.x = -40;
            if (this.y < -40) this.y = canvas.height + 40;
            if (this.y > canvas.height + 40) this.y = -40;
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
        const particleCount = Math.min(100, Math.floor(window.innerWidth / 12));
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }

    function drawConnections() {
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 110) {
                    ctx.beginPath();
                    // Opacidade mais destacada: 0.35 (antes 0.25)
                    const opacity = 0.35 * (1 - dist / 110);
                    ctx.strokeStyle = `rgba(198, 164, 63, ${opacity})`;
                    ctx.lineWidth = 1.1; // ligeiramente mais grossa
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }
    }

    function animateBackground() {
        const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        grad.addColorStop(0, '#0a0a1a');
        grad.addColorStop(1, '#12122a');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }

    function animate() {
        time += 0.03;
        animateBackground();
        drawConnections();
        particles.forEach(p => {
            p.update(mouseX, mouseY);
            p.draw();
        });
        animationId = requestAnimationFrame(animate);
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

</script>