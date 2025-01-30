<main>
     <?php if (! empty($products) && is_array($products)) { ?>
    <a href="/cart/delete">Eliminar carrito</a>
    <div class="product-grid">
        <?php foreach ($products as $product) { ?>
        <div class="product-card">
            <div class="product-image-container">
                <img src="<?php echo IMG_URL.htmlspecialchars($product['imagen']) ?>" alt="<?php echo htmlspecialchars($product['nombre']) ?>" class="product-image">
            </div>
            <div class="product-info">
                <h3 class="product-name"><?php echo htmlspecialchars($product['nombre']) ?></h3>
                <p class="product-description"><?php echo htmlspecialchars($product['descripcion']) ?></p>
                <p><b>Stock:</b> <?php echo htmlspecialchars($product['stock']) ?> und.</p>
                <p class="product-price">Precio: €<?php echo number_format($product['precio'], 2) ?></p>
                <form action="/cart/add" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['nombre']) ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['precio'] ?>">
                    <input type="hidden" name="product_stock" value="<?php echo $product['precio'] ?>">
                    <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['imagen']) ?>">
                    <button type="submit" class="add-to-cart">Agregar al carrito</button>
                </form>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } else { ?>
    <div class="confirmation-container">
        <p>No hay productos disponibles en este momento.</p>
    </div>
<?php } ?>
</main>
