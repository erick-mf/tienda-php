<main>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>

    <form action="/admin/category/new" method="post">
        <h1>Nueva Categoria</h1>
        <label for="name">Nombre:</label><br>
        <?php if (isset($errors['name'])) { ?>
        <span class="form-error"><?php echo htmlspecialchars($errors['name']); ?></span><br>
        <?php } ?>
        <input type="text"  name="category[name]" required value="<?php echo htmlspecialchars($_POST['category']['name'] ?? ''); ?>">

        <input type="submit" value="Agregar">
        <button type="button" onclick="location.href='/admin/category'">Cancelar</button>
    </form>
</main>
