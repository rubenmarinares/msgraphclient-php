<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microsoft Graph</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px 20px;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background-color: #007bff;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .logout-btn {
            background-color: red;
            padding: 8px 12px;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-links">
        <a href="index.php" class="<?=($imenu1==0?'active':'')?>">Inicio</a>
        <a href="test.php" class="<?=($imenu1==1?'active':'')?>">Usuario</a>
        <a href="onedrive.php" class="<?=($imenu1==2?'active':'')?>">OneDrive</a>
        <a href="email.php" class="<?=($imenu1==3?'active':'')?>">Email</a>
    </div>
    <div class="user-section">
        <span><?=$_SESSION["email"]?></span>
        <button class="logout-btn"><a href="index.php?logout=1">Salir</a></button>
    </div>
</nav>
