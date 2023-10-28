<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $sql = "SELECT * FROM Tasks WHERE Task_ID = $task_id";
    $result_task = $conn->query($sql);

    if ($result_task->num_rows > 0) {
        while ($row_task = $result_task->fetch_assoc()) {
            $task_name = $row_task["Name"];
            $task_description = $row_task["Description"];

            if ($row_task["Employee_ID"]) {
                $employeeID = $row_task["Employee_ID"];
                $sql_employee = "SELECT * FROM Employees WHERE Employee_ID = $employeeID";
                $result_employee = $conn->query($sql_employee);

                if ($result_employee->num_rows > 0) {
                    while ($row_employee = $result_employee->fetch_assoc()) {
                        $employee = $row_employee["First_Name"] . " " . $row_employee["Last_Name"];
                    }
                }
            }

            if ($row_task["Project_ID"]) {
                $projectID = $row_task["Project_ID"];
                $sql_project = "SELECT * FROM Projects WHERE Project_ID = $projectID";
                $result_project = $conn->query($sql_project);

                if ($result_project->num_rows > 0) {
                    while ($row_project = $result_project->fetch_assoc()) {
                        $project = $row_project["Name"];
                    }
                }
            }

            if ($row_task["Equipment_ID"]) {
                $equipmentID = $row_task["Equipment_ID"];
                $sql_equipment = "SELECT * FROM Equipment WHERE Equipment_ID = $equipmentID";
                $result_equipment = $conn->query($sql_equipment);

                if ($result_equipment->num_rows > 0) {
                    while ($row_equipment = $result_equipment->fetch_assoc()) {
                        $equipment = $row_equipment["Name"];
                    }
                }
            }
            else {
                $equipment = "- - -";
            }
        }
    }
} else {
    $name_project = "";
    $sql = "SELECT * FROM Tasks";
    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Задача: <?php echo $task_name ? $task_name : "" ?></title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Задача: <?php echo $task_name ? $task_name : "" ?></h1>

        <div class="content">
            <?php echo $task_description ? "<div class='text'>" . $task_description . "</div>" : "<div class='warning'>Описания нету</div>" ?>

            <div class="side">

                <div class="side-item">
                    Сотрудник: <span><?php echo $employee; ?></span>
                </div>

                <div class="side-item">
                    Проект: <span><?php echo $project; ?></span>
                </div>

                <div class="side-item">
                    Оборудование: <span><?php echo $equipment; ?></span>
                </div>

            </div>

        </div>

    </main>

</body>

</html>