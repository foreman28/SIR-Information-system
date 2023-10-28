<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['project_id'])) {
    $project_id_to_add = $_GET['project_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST["new_name"];
    $new_description = $_POST["new_description"];
    
    if ($_SESSION["user_status"] !== 'admin') {
        $new_employee_id =  $_SESSION["user_id"];
    }
    else {
        $new_employee_id = $_POST["new_employee_id"];
    }
    
    $new_project_id = $_POST["new_project_id"];
    
    $new_equipment_id = 'NULL';
    
    if ($_POST["new_equipment_id"] != "null") {
        $new_equipment_id = $_POST["new_equipment_id"];
    }

    $insert_sql = "INSERT INTO Tasks (Name, Description, Employee_ID, Project_ID, Equipment_ID) VALUES ('$new_name', '$new_description', '$new_employee_id', '$new_project_id', $new_equipment_id)";

    if ($conn->query($insert_sql) === TRUE) {
        $str = $project_id_to_add ? '?project_id=' . $_GET['project_id'] : '';
        header("Location: tasks.php$str");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Добавить новую задачу</title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Добавить новую задачу</h1>
        <form method="post" action="" class="form-edit">
            <div class="form-edit__boxs">
                <div class="form-edit__box">
                    <label class="label" for="new_name">Название задачи:</label>
                    <label class="input">
                        <input type="text" id="new_name" name="new_name" required>
                    </label>

                    <label class="label" for="new_description">Описание:</label>
                    <label class="textarea">
                        <textarea id="new_description" name="new_description" required></textarea>
                    </label>
                </div>

                <div class="form-edit__box">
                    
                    
                <?php if ($_SESSION["user_status"] === 'admin') { ?>
                
                    <label class="label" for="new_employee_id">Сотрудник:</label>
                    <label class="select">
                        <select id="new_employee_id" name="new_employee_id" required>
                            <?php
                            $sql = "SELECT Employee_ID, First_Name, Last_Name FROM Employees";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $employee_id = $row['Employee_ID'];
                                    $employee_name = $row['First_Name'] . ' ' . $row['Last_Name'];
                                    echo "<option class='option' value='$employee_id'>$employee_name</option>";
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
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $project_id = $row['Project_ID'];
                                    $project_name = $row['Name'];
                                    $selected = ($project_id == $project_id_to_add) ? 'selected' : '';
                                    echo "<option value='$project_id' $selected>$project_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </label>
                    
                    
                    <label class="label" for="new_equipment_id">Оборудование:</label>
                    <label class="select">
                        <select id="new_equipment_id" name="new_equipment_id">
                        <option value="null" <?php echo ($edit_row['Equipment_ID'] === '') ? 'selected' : ''; ?> >- - -</option>
                            <?php
                            $sql = "SELECT Equipment_ID, Name FROM Equipment";
                            $result = $conn->query($sql);

                            $sql_2 = "SELECT Equipment_ID FROM Tasks";
                            $result_2 = $conn->query($sql_2);
                            $arr_equipment = [];

                            while ($row_2 = $result_2->fetch_assoc()) {
                                if ($row_2['Equipment_ID'] != 0) {
                                    $arr_equipment[] += $row_2['Equipment_ID'];
                                }
                            }


                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $equipment_id = $row['Equipment_ID'];
                                    $equipment_name = $row['Name'];
                                    $selected = ($equipment_id == $edit_row['Equipment_ID']) ? 'selected' : '';

                                    if (!in_array($equipment_id, $arr_equipment) || $selected) {
                                        $value = ($equipment_id === '') ? 'NULL' : $equipment_id;
                                        echo "<option value=\"$value\" $selected>$equipment_name</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>
            </div>

            <button class="btn" type="submit">Сохранить</button>
        </form>
    </main>
</body>

</html>