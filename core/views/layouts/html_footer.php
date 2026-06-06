<script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>

<script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/axios.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/app.js"></script>

<!-- Botão Voltar ao Topo -->
<style>
    #scrollToTop {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: #C6A43F;
        color: #0a0a1a;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        z-index: 999;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    #scrollToTop:hover {
        background: #a8862e;
        transform: scale(1.05);
    }
</style>

<button id="scrollToTop" title="Voltar ao topo">↑</button>

<script>
    const scrollBtn = document.getElementById('scrollToTop');
    if(scrollBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollBtn.style.display = 'flex';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
</script>

<?php if(!defined('NO_FOOTER_CLOSE')): ?>
</body>
</html>
<?php endif; ?>