<main>
    <h1>Listado de Categorías</h1>

    <?php if (isset($msg)) { ?>
    <div class="error-message">
        <p>Error al eliminar la categoria</p>
    </div>
    <?php } ?>

    <a href="/admin/category/new" class="add-link">Agregar categoria</a>
    <table>
        <thead>
            <tr>
                <th>Nombre de la Categoría</th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($results['nombre'])) { ?>
            <tr>
                <td><?php echo htmlspecialchars($results['nombre']); ?></td>
                <td><a href="/admin/category/edit/<?php echo $results['id']; ?>">Editar</a></td>
                <td><a href="/admin/category/delete/<?php echo $results['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar está categoría?')">Eliminar</a></td>
            </tr>
            <?php } elseif (is_array($results)) { ?>
            <?php foreach ($results as $result) { ?>
            <?php if (isset($result['nombre'])) { ?>
            <tr>
                <td><?php echo htmlspecialchars($result['nombre']); ?></td>
                <td>
                    <form action="/admin/category/edit/<?php echo $result['id']; ?>" method="get">
                        <input type="submit" value="Editar">
                    </form>
                </td>
                <td>
                    <form action="/admin/category/delete/<?php echo $result['id']; ?>" method="post">
                        <input type="submit" value="Eliminar" onclick="return confirm('La categoria será eliminada. Esta seguro?')">
                    </form>
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php } else { ?>
            <tr><td colspan="3">No hay categorías disponibles</td></tr>
            <?php } ?>
        </tbody>
    </table>
</main>
