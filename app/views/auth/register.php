<?php if (isset($error)) { ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <main>
        <form action="register" method="post">
            <h1>Registro de Usuario</h1>
            <label for="name">Nombre:</label><br>
            <?php if (isset($errors['name'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['name']); ?></span><br>
            <?php } ?>
            <input type="text"  name="user[name]" required value="<?php echo htmlspecialchars($_POST['user']['name'] ?? ''); ?>">

            <label for="surnames">Apellidos:</label><br>
            <?php if (isset($errors['surnames'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['surnames']); ?></span><br>
            <?php } ?>
            <input type="text"  name="user[surnames]" required value="<?php echo htmlspecialchars($_POST['user']['surnames'] ?? ''); ?>">

            <label for="address">Dirección:</label><br>
            <?php if (isset($errors['address'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['address']); ?></span><br>
            <?php } ?>
            <input type="text"  name="user[address]" value="<?php echo htmlspecialchars($_POST['user']['address'] ?? ''); ?>">

            <label for="email">Correo Electrónico:</label><br>
            <?php if (isset($errors['email'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['email']); ?></span><br>
            <?php } ?>
            <input type="email"  name="user[email]" required value="<?php echo htmlspecialchars($_POST['user']['email'] ?? ''); ?>">

            <label for="phone">Teléfono:</label><br>
            <?php if (isset($errors['phone'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['phone']); ?></span><br>
            <?php } ?>
            <input type="tel"  name="user[phone]" value="<?php echo htmlspecialchars($_POST['user']['phone'] ?? ''); ?>">

            <label for="password">Contraseña:</label><br>
            <?php if (isset($errors['password'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['password']); ?></span><br>
            <?php } ?>
            <input type="password"  name="user[password]" required>

            <?php if (isset($_SESSION['user_role'])) { ?>
            <?php if ($_SESSION['user_role'] === 'admin') { ?>
            <label for="rol">Rol:</label>
            <select name="user[rol]">
                <option value="client">Cliente</option>
                <option value="admin">Administrador</option>
            </select><br>

            <input type="submit" value="Registrar">
            <button type="button" onclick="location.href='/admin/users'">Cancelar</button>
            <?php } ?>
            <?php } else { ?>
            <br>
            <input type="submit" value="Registrar">
            <button type="button" onclick="location.href='/'">Cancelar</button>
            <p>¿Ya tienes una cuenta? <a href="login">Inicia sesión aquí</a>.</p>
            <?php } ?>
        </form>

    </main>
