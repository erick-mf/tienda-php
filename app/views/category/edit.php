<main>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>

    <h1>Editar Categoria</h1>

    <form action="/admin/category/edit/<?php echo $result['id']; ?>" method="post">
        <label for="name">Nuevo nombre:</label><br>
        <input type="text"  name="category[name]" required value="<?php echo htmlspecialchars($result['nombre'] ?? ''); ?>">
        <?php if (isset($errors['name'])) { ?>
            <span style="color: red;"><?php echo htmlspecialchars($errors['name']); ?></span>
        <?php } ?><br>

        <input type="submit" value="Guardar cambios">
        <button type="button" onclick="location.href='/admin/category'">Cancelar</button>
    </form>
</main>
