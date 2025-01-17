<main>
    <h2>Categorías</h2>
    <?php foreach ($categories as $category) { ?>
    <p><?php echo htmlspecialchars($category['nombre']) ?></p>
    <?php } ?>

    <h2>Productos</h2>
    <?php foreach ($products as $product) { ?>
    <section>
        <img src="<?php echo IMG_URL.htmlspecialchars($product['imagen']) ?>" style="width:8%;">
        <p><?php echo htmlspecialchars($product['nombre']) ?></p>
        <p><?php echo htmlspecialchars($product['descripcion']) ?></p>
    </section>
    <?php } ?>

    <?php foreach ($_SESSION['array'] as $category => $value) { ?>
    <p><?php echo $category.' '.$value ?></p>
    <?php } ?>
</main>
