<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/public/assets/css/style.css">
        <title>Tienda</title>
    </head>
    <body>
        <header>
            <nav>
                <section class="nav-container">
                    <div class="nav-section nav-home">
                        <ul>
                            <li><a href="/">Fake</a></li>
                        </ul>
                    </div>
                    <div class="nav-section nav-options">
                        <ul>
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                            <li><a href="/admin/orders">Editar pedidos</a></li>
                            <li><a href="/admin/users">Editar usuarios</a></li>
                            <li><a href="/products">Editar productos</a></li>
                            <li><a href="/admin/category">Editar categorías</a></li>
                            <li class="dropdown">
                                <a href="#"><?php echo ucfirst(htmlspecialchars($_SESSION['user_role'])).'  '; ?></a>
                                <div class="dropdown-content">
                                    <a href="/logout">Cerrar sesión</a>
                                </div>
                            </li>
                            <?php } elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') { ?>
                            <li class="dropdown">
                                <a href="#">Categorías</a>
                                <div class="dropdown-content">
                                    <?php foreach ($_SESSION['categories'] as $category) { ?>
                                    <a href="/category/<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['nombre']); ?></a>
                                    <?php } ?>
                                </div>
                            </li>
                            <li><a href="/cart">Carrito</a></li>
                            <li class="dropdown">
                                <a href="#"><?php echo ucfirst(htmlspecialchars($_SESSION['user_name'])).'  '; ?></a>
                                <div class="dropdown-content">
                                    <a href="/logout">Cerrar sesión</a>
                                </div>
                            </li>
                            <?php } else { ?>
                            <li class="dropdown">
                                <a href="#">Categorías</a>
                                <div class="dropdown-content">
                                    <?php foreach ($_SESSION['categories'] as $category) { ?>
                                    <a href="/category/<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['nombre']); ?></a>
                                    <?php } ?>
                                </div>
                            </li>
                            <li><a href="/cart">Carrito</a></li>
                            <li><a href="/register">Registrarse</a></li>
                            <li><a href="/login">Iniciar sesión</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </section>
            </nav>
        </header>
