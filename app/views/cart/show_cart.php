<?php if (isset($_SESSION['message'])) { ?>
    <div class='<?php echo $_SESSION['message']['type']; ?>'>
        <p><?php echo htmlspecialchars($_SESSION['message']['text']); ?></p>
    </div>
    <?php unset($_SESSION['message']); ?>
    <?php } ?>

<main>
    <?php if (! empty($_SESSION['order'])) { ?>
    <h2>Tu Carrito</h2><br>
    <a class="button-cart" href="/cart/delete">Eliminar carrito</a><br>
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
                        <?php echo htmlspecialchars($item['nombre']); ?>
                    </td>
                    <td>€<?php echo number_format($item['precio'], 2); ?></td>
                    <td>
                        <form action="/cart/update" method="POST" class="update-quantity-form">
                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                            <span class="quantity-display"><?php echo $item['cantidad']; ?></span>
                            <button type="submit" name="quantity" value="decrease" class="quantity-button">-</button>
                            <button type="submit" name="quantity" value="increase" class="quantity-button">+</button>
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
