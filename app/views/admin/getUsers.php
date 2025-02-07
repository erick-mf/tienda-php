<?php if (isset($_SESSION['message'])) { ?>
    <div class="error-message">
        <p><?php echo htmlspecialchars($_SESSION['message']); ?></p>
    </div>
    <?php unset($_SESSION['message']); ?>
    <?php } ?>
    <main>
        <h1>Usuarios</h1>
        <a href="/register" class="add-link">Agregar usuario</a>

        <?php if (isset($users) && ! empty($users)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th colspan="2" style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($user['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($user['rol']); ?></td>
                    <td>
                        <form action="/admin/user/edit/<?php echo $user['id']; ?>" method="get">
                            <input type="submit" value="Editar">
                        </form>
                    </td>
                    <td>
                        <form action="/admin/user/delete/<?php echo $user['id']; ?>" method="post">
                            <input type="submit" value="Eliminar">
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p>No se encontraron usuarios.</p>
        <?php } ?>
    </main>
