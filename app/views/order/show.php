<?php if (isset($_SESSION['message'])) { ?>
    <div class='<?php echo $_SESSION['message']['type']; ?>'>
        <p><?php echo htmlspecialchars($_SESSION['message']['text']); ?></p>
    </div>
    <?php unset($_SESSION['message']); ?>
    <?php } ?>

<main>
    <?php if (isset($orders) && is_array($orders) && ! empty($orders)) { ?>
        <h1>Listado de Pedidos</h1>
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Dirección</th>
                <th>Coste</th>
                <th>Estado</th>
                <th>Fecha</th>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                <th colspan="2">Acciones</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
                <td><?php echo htmlspecialchars($order['nombre'].' '.$order['apellidos']); ?></td>
                <td><?php echo htmlspecialchars($order['direccion'].', '.$order['localidad'].', '.$order['provincia']); ?></td>
                <td>€ <?php echo number_format($order['coste'], 2); ?></td>
                <td><?php echo htmlspecialchars($order['estado']); ?></td>
                <td><?php echo htmlspecialchars($order['fecha']); ?></td>
                <td>
                    <form action="/admin/order/confirmation/<?php echo $order['id']; ?>" method="post">
                        <input type="submit" value="Entregado">
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
    <div class="empty-content-message">
        <p>No hay más pedidos realizados.</p>
    </div>
    <?php } ?>
</main>
