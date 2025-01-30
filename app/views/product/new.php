<main>
    <form action="/admin/product/new" method="post" enctype="multipart/form-data">
        <h1>Registro de Producto</h1>
        <label for="category_id">Categoría:</label><br>
        <?php if (isset($errors['category_id'])) { ?>
        <span class="form-error"><?= htmlspecialchars($errors['category_id']) ?></span><br>
        <?php } ?>
        <select id="category_id" name="product[category_id]">
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['nombre']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="name">Nombre:</label><br>
        <?php if (isset($errors['name'])) { ?>
        <span class="form-error"><?= htmlspecialchars($errors['name']) ?></span><br>
        <?php } ?>
        <input type="text" name="product[name]" required value="<?= htmlspecialchars($_POST['product']['name'] ?? '') ?>">

        <label for="description">Descripción:</label><br>
        <?php if (isset($errors['description'])) { ?>
        <span class="form-error"><?= htmlspecialchars($errors['description']) ?></span><br>
        <?php } ?>
        <textarea name="product[description]" required><?= htmlspecialchars($_POST['product']['description'] ?? '') ?></textarea>

        <label for="price">Precio:</label><br>
        <?php if (isset($errors['price'])) { ?>
        <span class="form-error"><?= htmlspecialchars($errors['price']) ?></span><br>
        <?php } ?>
        <input type="number" name="product[price]" step="0.01" required value="<?= htmlspecialchars($_POST['product']['price'] ?? '') ?>">

        <label for="stock">Stock:</label><br>
        <?php if (isset($errors['stock'])) { ?>
        <span class="form-error"><?= htmlspecialchars($errors['stock']) ?></span><br>
        <?php } ?>
        <input type="number" name="product[stock]" required value="<?= htmlspecialchars($_POST['product']['stock'] ?? '') ?>">

        <label for="offer">Oferta:</label><br>
        <?php if (isset($errors['offer'])) { ?>
        <span class="form-error"><?= htmlspecialchars($errors['offer']) ?></span><br>
        <?php } ?>
        <input type="text" name="product[offer]" value="<?= htmlspecialchars($_POST['product']['offer'] ?? '') ?>">

        <label for="date">Fecha:</label><br>
        <?php if (isset($errors['date'])) { ?>
        <span class="form-error"><?= htmlspecialchars($errors['date']) ?></span><br>
        <?php } ?>
        <input type="date" name="product[date]" required value="<?= htmlspecialchars($_POST['product']['date'] ?? date('Y-m-d')) ?>">

        <label for="image">Imagen:</label><br>
        <?php if (isset($errors['image'])) { ?>
        <span class="form-error"><?= htmlspecialchars($errors['image']) ?></span><br>
        <?php } ?>
        <input type="file" name="product[image]" accept="image/*">

        <input type="submit" value="Crear Producto">
        <button type="button" onclick="location.href='/products'">Cancelar</button>
    </form>
</main>
