<div class="container mt-5"><div class="row justify-content-center"><div class="col-md-4"><h3 class="text-center">Login Admin</h3>
<form action="?a=admin_login_submit" method="POST">
    <input type="text" name="text_admin" class="form-control mb-3" placeholder="Usuário" required>
    <input type="password" name="text_senha" class="form-control mb-3" placeholder="Senha" required>
    <button type="submit" class="btn btn-dark w-100">Entrar</button>
</form>
<?php if(isset($_SESSION['erro'])):?><div class="alert alert-danger mt-3"><?=$_SESSION['erro']; unset($_SESSION['erro']);?></div><?php endif;?>
</div></div></div>