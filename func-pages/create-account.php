<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account</title>
</head>
<body>
    <main>
        <form action="create-process.php" method="POST">
        <label><input type="text" name="username" placeholder="Username" required=""></label>
            <label><input type="email" name="email" placeholder="E-mail" required=""></label>
            <label><input type="password" name="password" placeholder="Password" required=""></label>
            <input type="submit" name="submit" id="submit" value="Create">
        </form>
    </main>
</body>
</html>