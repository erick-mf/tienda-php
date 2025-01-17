<main>
    <?php if (isset($errors['general'])) { ?>
    <div class="error-message"><?php echo htmlspecialchars($errors['general']); ?></div>
    <?php } ?>


    <form action="login" method="post">
        <h1>Iniciar Sesión</h1>
        <label for="email">Correo</label><br>
        <?php if (isset($errors['email'])) { ?>
        <span class="form-error"><?php echo htmlspecialchars($errors['email']); ?></span><br>
        <?php } ?>
        <input type="email" name="user[email]" value="<?php echo htmlspecialchars($_POST['user']['email'] ?? ''); ?>">

        <label for="password">Contraseña</label><br>
        <?php if (isset($errors['password'])) { ?>
        <span class="form-error"><?php echo htmlspecialchars($errors['password']); ?></span><br>
        <?php } ?>
        <input type="password" name="user[password]">

        <input type="submit" value="Iniciar sesión">
        <button type="button" onclick="location.href='/'">Cancelar</button>
        <p>¿No tienes una cuenta? <a href="/register">Regístrate aquí</a>.</p>
    </form>
</main>
