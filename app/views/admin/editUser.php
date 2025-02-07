<?php if (isset($error)) { ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <main>
        <form action="/admin/user/edit/<?php echo $user['id']; ?>" method="post">
            <h1>Registro de Usuario</h1>
            <label for="name">Nombre:</label><br>
            <?php if (isset($errors['name'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['name']); ?></span><br>
            <?php } ?>
            <input type="text"  name="user[name]" required value="<?php echo htmlspecialchars($user['nombre'] ?? ''); ?>">

            <label for="surnames">Apellidos:</label><br>
            <?php if (isset($errors['surnames'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['surnames']); ?></span><br>
            <?php } ?>
            <input type="text"  name="user[surnames]" required value="<?php echo htmlspecialchars($user['apellidos'] ?? ''); ?>">

            <label for="address">Dirección:</label><br>
            <?php if (isset($errors['address'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['address']); ?></span><br>
            <?php } ?>
            <input type="text"  name="user[address]" value="<?php echo htmlspecialchars($user['direccion'] ?? ''); ?>">

            <label for="email">Correo Electrónico:</label><br>
            <?php if (isset($errors['email'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['email']); ?></span><br>
            <?php } ?>
            <input type="email"  name="user[email]" required value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">

            <label for="phone">Teléfono:</label><br>
            <?php if (isset($errors['phone'])) { ?>
            <span class="form-error"><?php echo htmlspecialchars($errors['phone']); ?></span><br>
            <?php } ?>
            <input type="tel"  name="user[phone]" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">

            <label for="rol">Rol:</label>
            <select name="user[role]">
                <option value="client" <?php echo (isset($user['rol']) && $user['rol'] === 'client') ? 'selected' : ''; ?>>Cliente</option>
                <option value="admin" <?php echo (isset($user['rol']) && $user['rol'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
            </select><br>

            <input type="submit" value="Guardar">
            <button type="button" onclick="location.href='/admin/users'">Cancelar</button>
        </form>

    </main>
