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
                            <li><a href="/products">Editar productos</a></li>
                            <li><a href="/admin/category">Editar categorías</a></li>
                            <li><a href="/logout">Cerrar sesión</a></li>
                            <?php } elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') { ?>
                            <li><a >Categorias</a></li>
                            <li><a href="/cart">Carrito</a></li>
                            <li><a href="/logout"><?php echo ucfirst(htmlspecialchars($_SESSION['user_name'])).'  '; ?>[Cerrar sesión]</a></li>
                            <?php } else { ?>}
                            <li><a href="/cart">Carrito</a></li>
                            <li><a href="/register">Registrarse</a></li>
                            <li><a href="/login">Iniciar sesión</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </section>
            </nav>
        </header>
