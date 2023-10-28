<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_description = $_POST["new_description"];
    $new_employee_id = $_POST["new_employee_id"];
    $new_project_id = $_POST["new_project_id"];
    
    if ($_SESSION["user_status"] !== 'admin') {
        $new_employee_id =  $_SESSION["user_id"];
    }
    else {
        $new_employee_id = $_POST["new_employee_id"];
    }

    $insert_sql = "INSERT INTO Reports (Report_Name, Report_Description, Employee_ID, Project_ID) VALUES ('$new_name', '$new_description', '$new_employee_id', '$new_project_id')";

    if ($conn->query($insert_sql) === TRUE) {
        header("Location: reports.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Отчеты</title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Добавить новый отчет</h1>
        <form method="post" action="" class="form-edit">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_name">Название отчета:</label>
                    <label class="input">
                        <input type="text" id="new_name" name="new_name" placeholder="Название" required>
                    </label>

                    <label class="label" for="new_description">Описание:</label>
                    <label class="textarea">
                        <textarea id="new_description" name="new_description" required></textarea>
                    </label>

                <?php if ($_SESSION["user_status"] === 'admin') { ?>
                    <label class="label" for="new_employee_id">Сотрудник:</label>
                    <label class="select">
                        <select id="new_employee_id" name="new_employee_id" required>
                            <?php
                            $sql = "SELECT Employee_ID, Login, First_Name, Last_Name FROM Employees";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $employee_id = $row['Employee_ID'];
                                    $employee_name = $row['First_Name'] . " " . $row['Last_Name'] . " (" . $row['Login'] . ")";
                                    // $selected = ($employee_id == $employee_id_to_add) ? 'selected' : '';
                                    echo "<option value='$employee_id'>$employee_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                    
                <?php } ?>

<label class="label" for="new_project_id">Проект:</label>
                    <label class="select">
                        <select id="new_project_id" name="new_project_id" required>
     
                            <?php
                            $sql = "SELECT Project_ID, Name FROM Projects";
                            $result = $conn->query($sql);

                            $sql_2 = "SELECT Project_ID FROM Reports";
                            $result_2 = $conn->query($sql_2);
                            $arr_project = [];

                            while ($row_2 = $result_2->fetch_assoc()) {
                                if ($row_2['Project_ID'] != 0) {
                                    $arr_project[] += $row_2['Project_ID'];
                                }
                            }


                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $project_id = $row['Project_ID'];
                                    $project_name = $row['Name'];
                                    $selected = ($project_id == $edit_row['Project_ID']) ? 'selected' : '';

                                    if (!in_array($project_id, $arr_project) || $selected) {
                                        $value = ($project_id === '') ? 'NULL' : $project_id;
                                        echo "<option value=\"$value\" $selected>$project_name</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>
            </div>

            <button class="btn" type="submit">Добавить отчет</button>
        </form>
    </main>
</body>

</html>