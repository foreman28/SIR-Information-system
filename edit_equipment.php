<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['edit_equipment'])) {
    $equipment_id_to_edit = $_GET['edit_equipment'];

    $edit_sql = "SELECT * FROM Equipment WHERE Equipment_ID = $equipment_id_to_edit";
    $edit_result = $conn->query($edit_sql);

    if ($edit_result->num_rows == 1) {
        $edit_row = $edit_result->fetch_assoc();
    } else {
        echo "Error: " . $conn->error;
        exit();
    }
} else {
    header("Location: equipment.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_type = $_POST["new_type"];
    $new_equipment_condition = $_POST["new_equipment_condition"];
    $new_acquisition_date = $_POST["new_acquisition_date"];

    $update_sql = "UPDATE Equipment SET Name = '$new_name', Type = '$new_type', Equipment_Condition = '$new_equipment_condition', Acquisition_Date = '$new_acquisition_date' WHERE Equipment_ID = $equipment_id_to_edit";

    if ($conn->query($update_sql) === TRUE) {
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
    <title>Проекты</title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Изменить оборудование</h1>
        <form method="post" action="" class="form-edit form-edit-projectg">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_name">Название оборудования:</label>
                    <label class="input">
                        <input type="text" id="new_name" name="new_name" value="<?php echo $edit_row["Name"]; ?>" required>
                    </label>

                    <label class="label" for="new_type">Тип оборудования:</label>
                    <label class="input">
                        <input type="text" id="new_type" name="new_type" value="<?php echo $edit_row["Type"]; ?>" required>
                    </label>

                    <label class="label" for="new_equipment_condition">Состояние:</label>
                    <label class="input">
                        <input type="text" id="new_equipment_condition" name="new_equipment_condition" value="<?php echo $edit_row["Equipment_Condition"]; ?>" required>
                    </label>

                    <label class="label" for="new_acquisition_date">Дата Приобретения:</label>
                    <label class="input">
                        <input type="date" id="new_acquisition_date" name="new_acquisition_date" value="<?php echo $edit_row["Acquisition_Date"]; ?>" required>
                    </label>
                </div>
            </div>

            <button class="btn" type="submit">Сохранить</button>
        </form>
    </main>
</body>

</html>