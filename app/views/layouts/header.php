<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Biblioteca</title>
    </head>
    <body>
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_name'])) { ?>
                <li><a href="/logout">Cerrar sesión</a></li>
                <li><a href="/admin/category">Ver las categorias</a></li>
                <?php } else { ?>
                <li><a href="/register">Registrarse</a></li>
                <li><a href="/login">Iniciar sesión</a></li>
                <?php } ?>
            </ul>
        </nav>
