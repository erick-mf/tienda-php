<?php if (isset($_SESSION['message'])) { ?>
    <div class="error-message">
        <?php echo htmlspecialchars($_SESSION['message']); ?>
    </div>
  <?php unset($_SESSION['message']); ?>
    <?php } ?>
    <main>
        <h1>Listado de Categorías</h1>

        <a href="/admin/category/new" class="add-link">Agregar categoria</a>
        <table>
            <thead>
                <tr>
                    <th>Nombre de la Categoría</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($results) && is_array($results) && ! empty($results)) { ?>
                <?php foreach ($results as $result) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['nombre']); ?></td>
                    <td>
                        <form action="/admin/category/edit/<?php echo $result['id']; ?>" method="get">
                            <input type="submit" value="Editar">
                        </form>
                    </td>
                    <td>
                        <form action="/admin/category/delete/<?php echo $result['id']; ?>" method="post">
                            <input type="submit" value="Eliminar" onclick="return confirm('La categoria será eliminada. ¿Está seguro?')">
                        </form>
                    </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr><td colspan="3">No hay categorías disponibles</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
