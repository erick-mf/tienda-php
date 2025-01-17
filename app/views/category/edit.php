<main>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>

    <h1>Editar Categoria</h1>

    <form action="/admin/category/edit/<?php echo $category['id']; ?>" method="post">
        <label for="name">Nuevo nombre:</label><br>
        <?php if (isset($errors['name'])) { ?>
        <span class="form-error"><?php echo htmlspecialchars($errors['name']); ?></span><br>
        <?php } ?>
        <input type="text"  name="category[name]" required value="<?php echo htmlspecialchars($category['nombre'] ?? ''); ?>">

        <input type="submit" value="Guardar cambios">
        <button type="button" onclick="location.href='/admin/category'">Cancelar</button>
    </form>
</main>
