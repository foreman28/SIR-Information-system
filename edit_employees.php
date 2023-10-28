<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_status"] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['edit_employee'])) {
    $employee_id_to_edit = $_GET['edit_employee'];

    $edit_sql = "SELECT * FROM Employees WHERE Employee_ID = $employee_id_to_edit";
    $edit_result = $conn->query($edit_sql);

    if ($edit_result->num_rows == 1) {
        $edit_row = $edit_result->fetch_assoc();
    } else {
        echo "Error: " . $conn->error;
        exit();
    }
} else {
    header("Location: employees.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_login = $_POST["new_login"];
    $new_first_name = $_POST["new_first_name"];
    $new_last_name = $_POST["new_last_name"];
    $new_last_position = $_POST["new_position"];
    $new_phone_number = $_POST["new_phone_number"];
    $new_email = $_POST["new_email"];
    $new_password = $_POST["new_password"];
    $new_status = $_POST["new_status"];

    $update_sql = "UPDATE Employees SET
                   Login = '$new_login',
                   First_Name = '$new_first_name',
                   Last_Name = '$new_last_name',
                   Position = '$new_last_position',
                   Phone = '$new_phone_number',
                   Email = '$new_email',
                   Password = '$new_password',
                   Status = '$new_status'
                   WHERE Employee_ID = $employee_id_to_edit";

    if ($conn->query($update_sql) === TRUE) {
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
        <h1 class="title">Изменить данные сотрудника</h1>
        <form method="post" action="" class="form-edit form-edit-projectg">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_first_name">Имя:</label>
                    <label class="input">
                        <input type="text" id="new_first_name" name="new_first_name" placeholder="Имя" value="<?php echo $edit_row["First_Name"]; ?>" required>
                    </label>

                    <label class="label" for="new_last_name">Фамилия:</label>
                    <label class="input">
                        <input type="text" id="new_last_name" name="new_last_name" placeholder="Фамилия" value="<?php echo $edit_row["Last_Name"]; ?>" required>
                    </label>

                    <label class="label" for="new_status">Статус:</label>
                    <label class="select">
                        <select id="new_status" name="new_status" required>
                            <option class='option' value='admin' <?php echo ('admin' == $edit_row['Status']) ? 'selected' : '' ?> >admin</option>
                            <option class='option' value='user'  <?php echo ('user' == $edit_row['Status']) ?  'selected' : '' ?> >user</option>
                        </select>
                    </label>
                </div>

                <div class="form-edit__box">
                    <label class="label" for="new_position">Позиция:</label>
                    <label class="input">
                        <input type="text" id="new_position" name="new_position" placeholder="Позиция" value="<?php echo $edit_row["Position"]; ?>" required>
                    </label>
                    
                    <label class="label" for="new_phone_number">Номер телефона:</label>
                    <label class="input">
                        <input type="tel" id="new_phone_number" name="new_phone_number" placeholder="Номер телефона" value="<?php echo $edit_row["Phone"]; ?>" required>
                    </label>

                    <label class="label" for="new_email">Почта:</label>
                    <label class="input">
                        <input type="email" id="new_email" name="new_email" placeholder="Почта" value="<?php echo $edit_row["Email"]; ?>" required>
                    </label>
                </div>

                <div class="form-edit__box">
                    <label class="label" for="new_login">Логин:</label>
                    <label class="input">
                        <input type="text" id="new_login" name="new_login" placeholder="Логин" value="<?php echo $edit_row["Login"]; ?>" required>
                    </label>

                    <label class="label" for="new_password">Пароль:</label>
                    <label for="password" class="input">
                        <input type="password" id="new_password" name="new_password" placeholder="Пароль" value="<?php echo $edit_row["Password"]; ?>" required>
                        <a href="#" class="password-control input-icon" onclick="show_hide_password(this, document.getElementById('new_password'))"></a>
                    </label>

                </div>
            </div>

            <button class="btn" type="submit">Сохранить</button>
        </form>
    </main>
</body>
<script>

</script>
</html>