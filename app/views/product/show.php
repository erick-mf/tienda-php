<main>
    <h1>Listado de Productos</h1>

    <?php if (isset($msg)) { ?>
    <div class="error-message">
        <p><?php echo htmlspecialchars($msg); ?></p>
    </div>
    <?php } ?>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
    <a href="/admin/product/new" class="add-link">Agregar producto</a>
    <?php } ?>

    <?php if (isset($products) && is_array($products) && ! empty($products)) { ?>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Oferta</th>
                <th>Imagen</th>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                <th>Fecha</th>
                <th colspan="2">Acciones</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
                <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                <td><p><?php echo htmlspecialchars($product['descripcion']); ?></p></td>
                <td><?php echo htmlspecialchars($product['precio']); ?></td>
                <td><?php echo htmlspecialchars($product['stock']); ?></td>
                <td><?php echo htmlspecialchars($product['oferta']); ?></td>
                <td>
                    <?php if (! empty($product['imagen']) && isset($product['imagen'])) { ?>
                    <img src="<?php echo IMG_URL.htmlspecialchars($product['imagen']); ?>" alt="Imagen del producto" >
                    <?php } else { ?>
                    Sin imagen
                    <?php } ?>
                </td>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                <td><?php echo htmlspecialchars($product['fecha']); ?></td>
                <td>
                    <form action="/admin/product/edit/<?php echo $product['id']; ?>" method="get">
                        <input type="submit" value="Editar">
                    </form>
                </td>
                <td>
                    <form action="/admin/product/delete/<?php echo $product['id']; ?>" method="post">
                        <input type="submit" value="Eliminar">
                    </form>
                </td>
                <?php }?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
    <p>No hay productos disponibles</p>
    <?php } ?>
</main>
