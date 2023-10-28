<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_status"] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['edit_project'])) {
    $project_id_to_edit = $_GET['edit_project'];

    $edit_sql = "SELECT * FROM Projects WHERE Project_ID = $project_id_to_edit";
    $edit_result = $conn->query($edit_sql);

    if ($edit_result->num_rows == 1) {
        $edit_row = $edit_result->fetch_assoc();
    } else {
        echo "Error: " . $conn->error;
        exit();
    }
} else {
    header("Location: project.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_end_date = $_POST["new_end_date"];
    $new_start_date = $_POST["new_start_date"];
    $new_description = $_POST["new_description"];

    $update_sql = "UPDATE Projects SET Name = '$new_name', End_Date = '$new_end_date', Start_Date = '$new_start_date', Description = '$new_description' WHERE Project_ID = $project_id_to_edit";

    if ($conn->query($update_sql) === TRUE) {
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
        <h1 class="title">Изменить проект</h1>
        <form method="post" action="" class="form-edit form-edit-projectg">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_name">Название проекта:</label>
                    <label class="input">
                        <input type="text" id="new_name" name="new_name" value="<?php echo $edit_row["Name"]; ?>" required>
                    </label>

                    <label class="label" for="new_description">Описание:</label>
                    <label class="textarea">
                        <textarea id="new_description" name="new_description" required><?php echo $edit_row["Description"]; ?></textarea>
                    </label>
                </div>
                
                <div class="form-edit__box">
                    <label class="label" for="new_start_date">Дата начала:</label>
                    <label class="input">
                        <input type="date" id="new_start_date" name="new_start_date" value="<?php echo $edit_row["Start_Date"]; ?>" required>
                    </label>

                    <label class="label" for="new_end_date">Дата окончания:</label>
                    <label class="input">
                        <input type="date" id="new_end_date" name="new_end_date" value="<?php echo $edit_row["End_Date"]; ?>" required>
                    </label>
                </div>
            </div>

            <button class="btn" type="submit">Сохранить</button>
        </form>
    </main>
</body>

</html>