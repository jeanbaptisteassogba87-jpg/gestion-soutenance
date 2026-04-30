<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <form action="../../controllers/AuthController.php" method="post">
        <input type="text" name="login" placeholder="Entrez login">
        <input type="password" name="pwd" placeholder="Password">
        <input type="submit" name="btn" value="Connexion">
    </form>
</body>
</html>