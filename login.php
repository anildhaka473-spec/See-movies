<?php
session_start();

$correct_email = "admin@gmail.com";
$correct_password = "123456";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === $correct_email && $password === $correct_password) {

        $_SESSION["logged_in"] = true;

        header("Location: index.php");
        exit;

    } else {

        $error = "Invalid email or password";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Movie Search</title>

    <link rel="stylesheet" href="style.css">

</head>

<body class="login-page">

    <div class="login-box">

        <h1>🎬 Movie Search</h1>

        <h2>Login</h2>

        <?php if (isset($error)): ?>

            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>

        <form method="POST" action="login.php">

            <input
                type="email"
                name="email"
                placeholder="Enter Email"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required
            >

            <button type="submit">
                Login
            </button>

        </form>

    </div>

</body>

</html>
