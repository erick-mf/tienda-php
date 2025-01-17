<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/public/assets/css/style.css">
        <title>Biblioteca</title>
    </head>
    <body>
        <header>
            <nav>
                <section class="nav-container">
                    <div class="nav-section nav-home">
                        <ul>
                            <li><a href="/">Inicio</a></li>
                        </ul>
                    </div>
                    <div class="nav-section nav-options">
                        <ul>
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                            <li><a href="/products">Ver productos</a></li>
                            <li><a href="/admin/category">Ver categorías</a></li>
                            <li><a href="/logout">Cerrar sesión</a></li>
                            <?php } else { ?>
                            <li><a >Categorias</a></li>
                            <li><a href="/carrito">Carrito</a></li>
                            <li><a href="/register">Registrarse</a></li>
                            <li><a href="/login">Iniciar sesión</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </section>
            </nav>
        </header>
