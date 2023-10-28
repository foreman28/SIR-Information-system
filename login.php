<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: project.php");
    exit();
}

include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];

    $sql = "SELECT Employee_ID, First_Name, Last_Name, Status FROM Employees WHERE Login='$login' AND Password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION["user_id"] = $row["Employee_ID"];
        $_SESSION["user_name"] = $row["First_Name"] . " " . $row["Last_Name"];
        $_SESSION["user_status"] = $row["Status"];
        header("Location: project.php");
    } else {
        $error = "Неправильный логин или пароль.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Авторизация</title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df">
    <div class="f1 login-box">
        <img class="login__logo-form" src="./image/logo.svg" alt="logo">
        <h1 class="login__title">Авторизация</h1>

        <form action="login.php" method="post" class="login__form">

            <label for="login" class="login__input input">
                <svg class="input-icon">
                    <use xlink:href="image/sprite.svg#avatar"></use>
                </svg>
                <input type="text" name="login" id="login" placeholder="Логин" required>
            </label>

            <label for="password" class="login__input input">
                <svg class="input-icon">
                    <use xlink:href="image/sprite.svg#lock"></use>
                </svg>

                <input type="password" name="password" id="password"
                    class="password-input" placeholder="Пароль" required>

                <a href="#" class="password-control input-icon"
                    onclick="show_hide_password(this, document.getElementById('password'))"></a>
            </label>

            <button type="submit" id="submitBtn" class="btn">Вход</button>
        </form>

        <?php
        if (isset($error)) {
            echo "<p class='login-error'>$error</p>";
        }
        ?>
    </div>

    <div class="f1 login__logo">
        <img src="./image/logo.svg" alt="logo">
    </div>
</body>

<script defer>
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const submitButton = document.getElementById('submitBtn');

    function updateButtonOpacity() {
        if (loginInput.value.trim() === '' || passwordInput.value.trim() === '') {
            submitButton.style.opacity = '0.8';
            submitButton.disabled = true;
        } else {
            submitButton.style.opacity = '1';
            submitButton.disabled = false;
        }
    }

    loginInput.addEventListener('input', updateButtonOpacity);
    passwordInput.addEventListener('input', updateButtonOpacity);

    updateButtonOpacity();

    
</script>

</html>