<main>
    <?php if (! empty($_SESSION['order'])) { ?>
    <h2>Tu Carrito</h2>
    <div class="cart-table-container">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
        foreach ($_SESSION['order'] as $productId => $item) {
            $subtotal = $item['precio'] * $item['cantidad'];
            $total += $subtotal;
            ?>
                <tr>
                    <td class="product-column">
                        <img src="<?php echo IMG_URL.htmlspecialchars($item['imagen']); ?>"
                            alt="<?php echo htmlspecialchars($item['nombre']); ?>"
                            class="cart-item-image">
                    </td>
                    <td>€<?php echo number_format($item['precio'], 2); ?></td>
                    <td class="quantity-column">
                        <form action="/cart/update" method="POST" class="update-quantity-form">
                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['cantidad']; ?>" min="1" max="<?php echo $item['stock']; ?>">
                            <button type="submit" class="update-button">Actualizar</button>
                        </form>
                    </td>
                    <td>€<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <form action="/cart/delete/<?php echo $productId; ?>" method="POST" class="remove-item-form">
                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><strong>Total: €<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="cart-actions">
        <a href="/" class="button-cart">Seguir comprando</a>
        <a href="/checkout" class="button-cart">Proceder al pago</a>
    </div>

    <?php } else { ?>
    <div class="empty-content-message">
        <p>Tu carrito está vacío.</p>
        <a href="/" class="button-cart">Ir a la tienda</a>
    </div>
    <?php } ?>
</main>
