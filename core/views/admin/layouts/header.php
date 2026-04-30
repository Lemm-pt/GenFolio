<nav class="navbar navbar-dark bg-dark"><div class="container"><a class="navbar-brand" href="?a=admin">JoFolio Admin</a><div><span class="text-white me-3"><?= $_SESSION['admin_user'] ?? '' ?></span><a href="?a=admin_logout" class="btn btn-sm btn-danger">Sair</a></div></div></nav>
<div class="container mt-4">

<a href="?a=admin_configuracoes" class="btn btn-sm btn-outline-light ms-2">⚙️ Configs</a>