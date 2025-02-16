<?php if (isset($_SESSION['message'])) { ?>
    <div class='<?php echo $_SESSION['message']['type']; ?>'>
        <p><?php echo htmlspecialchars($_SESSION['message']['text']); ?></p>
    </div>
    <?php unset($_SESSION['message']); ?>
    <?php } ?>

    <main>
        <section class="checkout-container">
            <h2>Resumen del Pedido</h2>

            <?php if (! empty($_SESSION['order'])) { ?>
            <table class="checkout-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
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
                <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                        <td>€<?php echo number_format($item['precio'], 2); ?></td>
                        <td>€<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total:</td>
                        <td>€<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>

            <section class="payment-details">
                <form action="/checkout" method="POST">
                    <h2>Detalles de Envío</h2>
                    <label for="address">Dirección:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($_SESSION['user_address']) ?? ''?>" readonly>

                    <label for="state">Provincia:</label>
                    <?php if (isset($errors['state'])) { ?>
                    <span style="color: red;"><?php echo htmlspecialchars($errors['state']); ?></span><br>
                    <?php } ?>
                    <input type="text" id="state" name="state" value="<?php echo $_POST['state'] ?? ''?>" >

                    <label for="city">Localidad:</label>
                    <?php if (isset($errors['city'])) { ?>
                    <span style="color: red;"><?php echo htmlspecialchars($errors['city']); ?></span><br>
                    <?php } ?>
                    <input type="text" id="city" name="city" value="<?php echo $_POST['city'] ?? ''?>" >

                    <input type="submit"  value="Confirmar el pedido" onclick="return confirm('Los datos del formulario son correctos?')">
                    <button type="button" onclick="location.href='/cart'">Regresar al carrito</button>

                    <div id="paypal-button-container"></div>
                </form>
            </section>
            <?php } ?>
        </section>
    </main>
