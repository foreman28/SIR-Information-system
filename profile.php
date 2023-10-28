<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM Employees WHERE Employee_ID = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $current_login = $row["Login"];
    $current_first_name = $row["First_Name"];
    $current_last_name = $row["Last_Name"];
    $current_phone = $row["Phone"];
    $current_email = $row["Email"];
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["change_password"])) {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        if ($old_password === $row["Password"]) {
            if ($new_password === $confirm_password) {
                $update_password_sql = "UPDATE Employees SET Password = '$new_password' WHERE Employee_ID = $user_id";

                if ($conn->query($update_password_sql) === TRUE) {
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "New password and confirmation do not match.";
            }
        } else {
            echo "Old password is incorrect.";
        }
    } else {
        $new_login = $_POST["new_login"];
        $new_first_name = $_POST["new_first_name"];
        $new_last_name = $_POST["new_last_name"];
        $new_phone = $_POST["new_phone"];
        $new_email = $_POST["new_email"];

        $_SESSION["user_name"] = $new_first_name . " " . $new_last_name;

        $update_sql = "UPDATE Employees SET
                       Login = '$new_login',
                       First_Name = '$new_first_name',
                       Last_Name = '$new_last_name',
                       Phone = '$new_phone',
                       Email = '$new_email'
                       WHERE Employee_ID = $user_id";

        if ($conn->query($update_sql) === TRUE) {
            header("Location: profile.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Личный кабинет</title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>
    <main class="main">
        <h1 class="title">Личный кабинет</h1>
        <div class="form__profile">
            <form id="edit-profile" method="post" action="" class="form-edit form-edit__profile">
                <div class="form-edit__boxs form-edit__profile-boxs">
                    <div class="form-edit__box">
                        <label class="label" for="new_login">Логин:</label>
                        <label class="input">
                            <svg class="input-icon">
                                <use xlink:href="image/sprite.svg#edit-avatar"></use>
                            </svg>
                            <input type="text" id="new_login" name="new_login" value="<?php echo $current_login; ?>" required>
                        </label>

                        <label class="label" for="new_first_name">Имя:</label>
                        <label class="input">
                            <svg class="input-icon">
                                <use xlink:href="image/sprite.svg#edit-avatar"></use>
                            </svg>
                            <input type="text" id="new_first_name" name="new_first_name" value="<?php echo $current_first_name; ?>" required>
                        </label>

                        <label class="label" for="new_last_name">Фамилия:</label>
                        <label class="input">
                            <svg class="input-icon">
                                <use xlink:href="image/sprite.svg#edit-avatar"></use>
                            </svg>
                            <input type="text" id="new_last_name" name="new_last_name" value="<?php echo $current_last_name; ?>" required>
                        </label>

                        <label class="label" for="new_phone">Номер телефона:</label>
                        <label class="input">
                            <svg class="input-icon">
                                <use xlink:href="image/sprite.svg#tel"></use>
                            </svg>
                            <input type="text" id="new_phone" name="new_phone" value="<?php echo $current_phone; ?>" required>
                        </label>

                        <label class="label" for="new_email">Электронная почта:</label>
                        <label class="input">
                            <svg class="input-icon">
                                <use xlink:href="image/sprite.svg#email"></use>
                            </svg>
                            <input type="text" id="new_email" name="new_email" value="<?php echo $current_email; ?>" required>
                        </label>
                        <button form="edit-profile" class="btn" type="submit" name="update_profile">Сохранить изменения</button>
                    </div>
                </div>
            </form>
            <form id="edit-password" method="post" action="" class="form-edit form-edit__password">
                <div class="form-edit__boxs form-edit__profile-boxs">
                    <div class="form-edit__box">
                        <label class="label" for="old_password">Старый пароль:</label>
                        <label class="input">
                            <svg class="input-icon">
                                <use xlink:href="image/sprite.svg#password"></use>
                            </svg>
                            <input type="text" id="old_password" name="old_password" placeholder="Старый пароль" required>
                        </label>

                        <label class="label" for="new_password">Новый пароль:</label>
                        <label class="input">
                            <svg class="input-icon">
                                <use xlink:href="image/sprite.svg#password"></use>
                            </svg>
                            <input type="text" id="new_password" name="new_password" placeholder="Новый пароль" required>
                        </label>

                        <label class="label" for="confirm_password">Повторите пароль:</label>
                        <label class="input">
                            <svg class="input-icon">
                                <use xlink:href="image/sprite.svg#password"></use>
                            </svg>
                            <input type="text" id="confirm_password" name="confirm_password" placeholder="Повторите пароль" required>
                        </label>
                        <button form="edit-password" class="btn" name="change_password">Изменить пароль</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>

</html>