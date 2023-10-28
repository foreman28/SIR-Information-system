<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_type = $_POST["new_type"];
    $new_equipment_condition = $_POST["new_equipment_condition"];
    $new_acquisition_date = $_POST["new_acquisition_date"];

    $insert_sql = "INSERT INTO Equipment (Name, Type, Equipment_Condition, Acquisition_Date)
                   VALUES ('$new_name', '$new_type', '$new_equipment_condition', '$new_acquisition_date')";

    if ($conn->query($insert_sql) === TRUE) {
        header("Location: equipment.php");
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
        <h1 class="title">Добавить новое оборудование</h1>
        <form method="post" action="" class="form-edit form-edit-projectg">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_name">Название оборудования:</label>
                    <label class="input">
                        <input type="text" id="new_name" name="new_name" placeholder="Название" required>
                    </label>

                    <label class="label" for="new_type">Тип оборудования:</label>
                    <label class="input">
                        <input type="text" id="new_type" name="new_type" placeholder="Тип" required>
                    </label>

                    <label class="label" for="new_equipment_condition">Состояние:</label>
                    <label class="input">
                        <input type="text" id="new_equipment_condition" name="new_equipment_condition" placeholder="Состояние" required>
                    </label>

                    <label class="label" for="new_acquisition_date">Дата Приобретения:</label>
                    <label class="input">
                        <input type="date" id="new_acquisition_date" name="new_acquisition_date" placeholder="Дата Приобретения" required>
                    </label>
                </div>
            </div>

            <button class="btn" type="submit">Сохранить</button>
        </form>
    </main>
</body>

</html>