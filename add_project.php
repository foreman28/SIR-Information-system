<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_status"] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_end_date = $_POST["new_end_date"];
    $new_start_date = $_POST["new_start_date"];
    $new_description = $_POST["new_description"];

    $insert_sql = "INSERT INTO Projects (Name, End_Date, Start_Date, Description) VALUES ('$new_name', '$new_end_date', '$new_start_date', '$new_description')";

    if ($conn->query($insert_sql) === TRUE) {
        header("Location: project.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Проекты</title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Добавить новый проект</h1>
        <form method="post" action="" class="form-edit">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_name">Название проекта:</label>
                    <label class="input">
                        <input type="text" id="new_name" name="new_name" placeholder="Название" required>
                    </label>

                    <label class="label" for="new_description">Описание:</label>
                    <label class="textarea">
                        <textarea id="new_description" name="new_description" required></textarea>
                    </label>
                </div>
                
                <div class="form-edit__box">
                    <label class="label" for="new_start_date">Дата начала:</label>
                    <label class="input">
                        <input type="date" id="new_start_date" name="new_start_date" required>
                    </label>

                    <label class="label" for="new_end_date">Дата окончания:</label>
                    <label class="input">
                        <input type="date" id="new_end_date" name="new_end_date" required>
                    </label>
                </div>
            </div>

            <button class="btn" type="submit">Добавить проект</button>
        </form>
    </main>
</body>

</html>