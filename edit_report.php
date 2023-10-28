<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_status"] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['edit_report'])) {
    $report_id_to_edit = $_GET['edit_report'];

    $edit_sql = "SELECT * FROM Reports WHERE Report_ID = $report_id_to_edit";
    $edit_result = $conn->query($edit_sql);

    if ($edit_result->num_rows == 1) {
        $edit_row = $edit_result->fetch_assoc();
    } else {
        echo "Error: " . $conn->error;
        exit();
    }

    
} else {
    header("Location: reports.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_description = $_POST["new_description"];
    $new_employee_id = $_POST["new_employee_id"];
    $new_project_id = $_POST["new_project_id"];



    $update_sql = " UPDATE Reports SET
                    Report_Name = '$new_name',
                    Report_Description = '$new_description',
                    Employee_ID = '$new_employee_id',
                    Project_ID = '$new_project_id'
                    WHERE Report_ID = $report_id_to_edit";
    
    
    if ($conn->query($update_sql) === TRUE) {
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
    <title>Изменить отчет</title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Изменить отчет</h1>
        <form method="post" action="" class="form-edit">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_name">Название отчета:</label>
                    <label class="input">
                        <input type="text" id="new_name" name="new_name" placeholder="Название" value="<?php echo $edit_row["Report_Name"]; ?>" required>
                    </label>

                    <label class="label" for="new_description">Описание:</label>
                    <label class="textarea">
                        <textarea id="new_description" name="new_description" required><?php echo $edit_row["Report_Description"]; ?></textarea>
                    </label>

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
                                    $selected = ($employee_id == $edit_row['Employee_ID']) ? 'selected' : '';
                                    echo "<option value='$employee_id' $selected>$employee_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                    
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