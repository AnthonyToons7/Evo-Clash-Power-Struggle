<?php
session_start();
if ($_SESSION["USERS_ID"] != true){
    header("Location:../../login.php");
}
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update account</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <main>
        <div class="login__container">
            <div class="login">
                <h1>Update</h1>
                <form action="update-process.php" method="POST">
                    <label><input type="text" name="new-username" placeholder="New username" required=""></label>
                    <label><input type="email" name="new-email" placeholder="New e-mail" required=""></label>
                    <label><input type="password" name="new-password" placeholder="New password" required=""></label>
                    <input type="hidden" placeholder="id" name="IDUSER" value="<?= $_GET["USERS_ID"] ?>">
                    <input type="submit" name="submit" id="submit" value="Update">
                </form>
                <a href="account.php" id='update-back'>< Back</a>
            </div>
        </div>
    </main>
</body>
</html>