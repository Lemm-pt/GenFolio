
<?php use core\classes\Store; ?>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="btn btn-dark d-md-none" type="button" onclick="toggleMobileMenu()">
            <i class="fas fa-bars"></i>
        </button>
          <a class="navbar-brand" href="<?= Store::getBaseUrl() ?>admin">
               <i class="fas fa-crown"></i> Vitrine Admin
         </a>
        <div>
            <a href="?a=admin_configuracoes" class="btn btn-sm btn-outline-gold me-2">
                <i class="fas fa-cog"></i> Configs
            </a>
           <span class="text-gold me-3">
               <i class="fas fa-user-circle"></i> <?= $_SESSION['cliente_email'] ?? ($_SESSION['cliente_slug'] ?? 'Admin') ?>
           </span>
            <a href="?a=admin_logout" class="btn btn-sm btn-danger">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Menu Lateral -->
        <div class="col-md-3 col-lg-2 p-0">
            <?php include(__DIR__ . '/admin_menu.php'); ?>
        </div>
        
        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10">
            <div class="p-3">