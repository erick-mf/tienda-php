<main>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>
    <h1>Registro de Usuario</h1>

    <form action="register" method="post">
        <label for="name">Nombre:</label><br>
        <input type="text"  name="user[name]" required value="<?php echo htmlspecialchars($_POST['user']['name'] ?? ''); ?>">
        <?php if (isset($errors['name'])) { ?>
            <span style="color: red;"><?php echo htmlspecialchars($errors['name']); ?></span>
        <?php } ?><br>

        <label for="surnames">Apellidos:</label><br>
        <input type="text"  name="user[surnames]" required value="<?php echo htmlspecialchars($_POST['user']['surnames'] ?? ''); ?>">
        <?php if (isset($errors['surnames'])) { ?>
            <span style="color: red;"><?php echo htmlspecialchars($errors['surnames']); ?></span>
        <?php } ?><br>

        <label for="address">Dirección:</label><br>
        <input type="text"  name="user[address]" value="<?php echo htmlspecialchars($_POST['user']['address'] ?? ''); ?>">
        <?php if (isset($errors['address'])) { ?>
            <span style="color: red;"><?php echo htmlspecialchars($errors['address']); ?></span>
        <?php } ?><br>

        <label for="email">Correo Electrónico:</label><br>
        <input type="email"  name="user[email]" required value="<?php echo htmlspecialchars($_POST['user']['email'] ?? ''); ?>">
        <?php if (isset($errors['email'])) { ?>
            <span style="color: red;"><?php echo htmlspecialchars($errors['email']); ?></span>
        <?php } ?><br>

        <label for="phone">Teléfono:</label><br>
        <input type="tel"  name="user[phone]" value="<?php echo htmlspecialchars($_POST['user']['phone'] ?? ''); ?>">
        <?php if (isset($errors['phone'])) { ?>
            <span style="color: red;"><?php echo htmlspecialchars($errors['phone']); ?></span>
        <?php } ?><br>

        <label for="password">Contraseña:</label><br>
        <input type="password"  name="user[password]" required>
        <?php if (isset($errors['password'])) { ?>
            <span style="color: red;"><?php echo htmlspecialchars($errors['password']); ?></span>
        <?php } ?><br>

        <input type="submit" value="Registrar">
        <button type="button" onclick="location.href='/'">Cancelar</button>
    </form>

    <p>¿Ya tienes una cuenta? <a href="login">Inicia sesión aquí</a>.</p>
</main>
