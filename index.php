<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
    <?php include_once('layouts/header.php'); ?>
    <div class="login-page">
        <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
        <div class="text-center">
            <h1 class="Login_h1">Inicio de Sesión</h1>
        </div>
        <?php echo display_msg($msg); ?>
        <form method="post" action="auth.php" class="clearfix">

            <div class="input-group">
                <span class="input-group-addon transparent"><span class="glyphicon glyphicon-user"></span></span>
                <input type="name" class="form-control" name="username" placeholder="Nombre de usario" required>
            </div>
            <div class="input-group">
                <span class="input-group-addon transparent"><span class="glyphicon glyphicon-lock"></span></span>
                <input type="password" name="password" class="form-control" placeholder="Contraseña">
            </div>
            <div class="form-group" align=center>
                <button type="submit" class="btn btn-primary btn-md">Entrar</button>
            </div>
        </form>
    </div>
    <?php include_once('layouts/footer.php'); ?>
