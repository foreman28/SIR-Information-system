<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_status"] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_first_name = $_POST["new_first_name"];
    $new_last_name = $_POST["new_last_name"];
    $new_phone_number = $_POST["new_phone_number"];
    $new_email = $_POST["new_email"];
    $new_position = $_POST["new_position"];
    $new_login = $_POST["new_login"];
    $new_password = $_POST["new_password"];

    $insert_sql = "INSERT INTO Employees (First_Name, Last_Name, Phone, Email, Position, Login, Password)
                   VALUES ('$new_first_name', '$new_last_name', '$new_phone_number', '$new_email', '$new_position', '$new_login', '$new_password')";

    if ($conn->query($insert_sql) === TRUE) {
        header("Location: employees.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Сотрудники</title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Добавить нового сотрудника</h1>
        <form method="post" action="" class="form-edit form-edit-projectg">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_first_name">Имя:</label>
                    <label class="input">
                        <input type="text" id="new_first_name" name="new_first_name" placeholder="Имя" required>
                    </label>

                    <label class="label" for="new_last_name">Фамилия:</label>
                    <label class="input">
                        <input type="text" id="new_last_name" name="new_last_name" placeholder="Фамилия" required>
                    </label>
                </div>
                <div class="form-edit__box">
                    <label class="label" for="new_position">Позиция:</label>
                    <label class="input">
                        <input type="text" id="new_position" name="new_position" placeholder="Позиция" required>
                    </label>

                    <label class="label" for="new_phone_number">Номер телефона:</label>
                    <label class="input">
                        <input type="tel" id="new_phone_number" name="new_phone_number" placeholder="Tелефон" required>
                    </label>

                    <label class="label" for="new_email">Почта:</label>
                    <label class="input">
                        <input type="email" id="new_email" name="new_email" placeholder="Почта" required>
                    </label>
                </div>
                <div class="form-edit__box">
                    <label class="label" for="new_login">Логин:</label>
                    <label class="input">
                        <input type="text" id="new_login" name="new_login" placeholder="Логин" required>
                    </label>

                    <label class="label" for="new_password">Пароль:</label>
                    <label class="input">
                        <input type="password" id="new_password" name="new_password" placeholder="Пароль" required>
                    </label>
                </div>
            </div>


            <button class="btn" type="submit">Сохранить</button>
        </form>
    </main>
</body>

</html>