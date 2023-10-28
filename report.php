<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['report_id'])) {
    $report_id = $_GET['report_id'];

    $sql = "SELECT * FROM Reports WHERE Report_ID = $report_id";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $report_name = $row["Report_Name"];
        $report_description = $row["Report_Description"];
    }

} else {
    $name_project = "";
    $sql = "SELECT * FROM Reports";
    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Отчет: <?php echo $report_name ? $report_name : "" ?></title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Отчет: <?php echo $report_name ? $report_name : "" ?></h1>

        <div>
            <?php echo $report_description ? "<div class='text'>" . $report_description . "</div>" : "<div class='warning'>Описания нету</div>" ?>
        </div>

    </main>

</body>

</html>