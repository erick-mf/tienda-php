<main>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>

    <h1>Nueva Categoria</h1>

    <form action="/admin/category/new" method="post">
        <label for="name">Nombre:</label><br>
        <input type="text"  name="category[name]" required value="<?php echo htmlspecialchars($_POST['category']['name'] ?? ''); ?>">
        <?php if (isset($errors['name'])) { ?>
            <span style="color: red;"><?php echo htmlspecialchars($errors['name']); ?></span>
        <?php } ?><br>

        <input type="submit" value="Agregar">
        <button type="button" onclick="location.href='/admin/category'">Cancelar</button>
    </form>
</main>
