<?php if (isset($error)) { ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

<main>

    <form action="/admin/product/edit/<?php echo $product['id']; ?>" method="post" enctype="multipart/form-data">
        <h1>Editar Producto</h1>
        <label for="category_id">Categoría:</label><br>
        <?php if (isset($errors['category_id'])) { ?>
        <span style="color: red;"><?php echo htmlspecialchars($errors['category_id']); ?></span><br>
        <?php } ?>
        <select id="category_id" name="product[category_id]">
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo ($product['categoria_id'] == $category['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['nombre']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="name">Nombre:</label><br>
        <?php if (isset($errors['name'])) { ?>
        <span style="color: red;"><?php echo htmlspecialchars($errors['name']); ?></span><br>
        <?php } ?>
        <input type="text" name="product[name]" required value="<?php echo htmlspecialchars($product['nombre'] ?? ''); ?>">

        <label for="description">Descripción:</label><br>
        <?php if (isset($errors['description'])) { ?>
        <span style="color: red;"><?php echo htmlspecialchars($errors['description']); ?></span><br>
        <?php } ?>
        <textarea name="product[description]" required><?php echo htmlspecialchars($product['descripcion'] ?? ''); ?></textarea>

        <label for="price">Precio:</label><br>
        <?php if (isset($errors['price'])) { ?>
        <span style="color: red;"><?php echo htmlspecialchars($errors['price']); ?></span><br>
        <?php } ?>
        <input type="number" name="product[price]" step="0.01" required value="<?php echo htmlspecialchars($product['precio'] ?? ''); ?>">

        <label for="stock">Stock:</label><br>
        <?php if (isset($errors['stock'])) { ?>
        <span style="color: red;"><?php echo htmlspecialchars($errors['stock']); ?></span><br>
        <?php } ?>
        <input type="number" name="product[stock]" required value="<?php echo htmlspecialchars($product['stock'] ?? ''); ?>">

        <label for="offer">Oferta:</label><br>
        <?php if (isset($errors['offer'])) { ?>
        <span style="color: red;"><?php echo htmlspecialchars($errors['offer']); ?></span><br>
        <?php } ?>
        <input type="text" name="product[offer]" value="<?php echo htmlspecialchars($product['oferta'] ?? ''); ?>">

        <!-- <label for="date">Fecha:</label><br> -->
        <!-- <?php if (isset($errors['date'])) { ?> -->
        <!-- <span style="color: red;"><?php echo htmlspecialchars($errors['date']); ?></span><br> -->
        <!-- <?php } ?> -->
        <!-- <input type="date" name="product[date]" required value="<?php echo htmlspecialchars($product['fecha'] ?? ''); ?>"> -->

        <label for="image">Nueva imagen (opcional):</label><br>
        <?php if (isset($errors['image'])) { ?>
        <span style="color: red;"><?php echo htmlspecialchars($errors['image']); ?></span><br>
        <?php } ?>
        <input type="file" name="product[image]" accept="image/*">

        <input type="submit" value="Guardar cambios">
        <button type="button" onclick="location.href='/products'">Cancelar</button>
    </form>
</main>
