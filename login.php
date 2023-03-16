<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <main>
        <div class="login__container">
            <div class="login">
                <h1>Login</h1>
                <form action="func-pages/login_process.php" method="post">
                    <label><input type="text" name="username" placeholder="Username" required=""></label>
                    <label><input type="email" name="email" placeholder="E-mail" required=""></label>
                    <label><input type="password" name="password" placeholder="Password" required=""></label>
                    <input type="submit" name="submit" id="submit" value="Login">
                </form>
                <div id='a'>
                    <p>Don't have an account? </p>
                    <a href="func-pages/create-account.php">Sign up</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php
