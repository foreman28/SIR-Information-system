<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];

    $sql = "SELECT *, E.First_Name, E.Last_Name
    FROM Tasks T
    INNER JOIN Employees E ON T.Employee_ID = E.Employee_ID
    WHERE Project_ID = $project_id";
    $result = $conn->query($sql);


    $sql_project = "SELECT * FROM Projects WHERE Project_ID = $project_id";
    $result_project = $conn->query($sql_project);

    $name_project = $result_project->fetch_assoc()["Name"];
} else {
    $name_project = "";
    $sql = "SELECT *, E.First_Name, E.Last_Name
    FROM Tasks T
    INNER JOIN Employees E ON T.Employee_ID = E.Employee_ID";
    $result = $conn->query($sql);
}


if (isset($_GET['delete_task'])) {
    $task_id = $_GET['delete_task'];
    $delete_sql = "DELETE FROM Tasks WHERE Task_ID = $task_id";

    if ($conn->query($delete_sql) === TRUE) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}



?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Задачи <?php echo $name_project ? "проекта: " . $name_project : "" ?></title>
    <?php include 'components/head.php'; ?>
</head>

<body class="df body">
    <?php
    include 'components/sidebar.php';
    ?>

    <main class="main">
        <h1 class="title">Задачи <?php echo $name_project ? "проекта: " . $name_project : "" ?></h1>

        <div class="form-edit">
            <label class="input" style="opacity: 1; display: inline-flex;">
                <svg class="input-icon">
                    <use xlink:href="image/sprite.svg#search"></use>
                </svg>
                <input type="text" placeholder="Поиск" id="search-text" name="search" onkeyup="tableSearch()">
            </label>
        </div>

        <div class="table-content">
            <?php

            if ($result->num_rows > 0) { ?>
                <table cellspacing='0' id='table'>
                    <tr>
                        <th onclick='sortTable(0)'>ID <span class='arrow'></span></th>
                        <th onclick='sortTable(1)'>Задача <span class='arrow'></span></th>
                        <th onclick='sortTable(2)'>Имя <span class='arrow'></span></th>
                        <th onclick='sortTable(3)'>Фамилия <span class='arrow'></span></th>
                        <th>Изменить</th>
                        <th>Удалить</th>
                    </tr>
                    <?php

                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td class='text-center'><?php echo $row["Task_ID"] ?></td>
                            <td><a href="task.php?task_id=<?php echo $row["Task_ID"] ?>"><?php echo $row["Name"] ?></a></td>
                            <!-- <td title="<?php echo $row["Description"] ?>"><?php echo $row["Description"] ?></td> -->
                            <td><?php echo $row["First_Name"] ?></td>
                            <td><?php echo $row["Last_Name"] ?></td>
                            
                            <td>
                                <?php if ($_SESSION["user_status"] === 'admin' || $_SESSION["user_id"] === $row['Employee_ID']) { ?>
                                    <a href="edit_task.php?edit_task=<?php echo $row["Task_ID"] ?>">Изменить</a>
                                <?php } ?>
                                </td>
                            <td>
                                <?php if ($_SESSION["user_status"] === 'admin' || $_SESSION["user_id"] === $row['Employee_ID']) { ?>
                                    <a href="tasks.php?delete_task=<?php echo $row["Task_ID"] ?>" onclick='return confirm("Вы уверены, что хотите удалить этот проект?")'>Удалить</a>
                                <?php } ?>
                            </td>
                            

                        </tr>
                <?php

                    }
                    echo "</table>";
                } else {
                    echo "<div class='warning'>Нет задач</div>";
                }

                $conn->close();
                ?>
        </div>
        <div class="table-btns btns">
            <a class="btn" href="add_task.php?project_id=<?php echo $name_project ? $_GET['project_id'] : '' ?>">Добавить задачу</a>
        </div>

    </main>

    <script defer>
        var sortOrder = [];

        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("table");
            switching = true;

            while (switching) {
                switching = false;
                rows = table.getElementsByTagName("tr");

                for (i = 1; i < rows.length - 1; i++) {
                    shouldSwitch = false;

                    var cellX = rows[i].getElementsByTagName("td")[columnIndex];
                    var cellY = rows[i + 1].getElementsByTagName("td")[columnIndex];

                    var xIsNumeric = !isNaN(parseFloat(cellX.textContent));
                    var yIsNumeric = !isNaN(parseFloat(cellY.textContent));

                    if (xIsNumeric && yIsNumeric) {
                        x = parseFloat(cellX.textContent);
                        y = parseFloat(cellY.textContent);
                    } else {
                        x = cellX.textContent.toLowerCase();
                        y = cellY.textContent.toLowerCase();
                    }

                    if (sortOrder[columnIndex] === "asc") {
                        if (x > y) {
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if (x < y) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }

            if (sortOrder[columnIndex] === "asc") {
                sortOrder[columnIndex] = "desc";
            } else {
                sortOrder[columnIndex] = "asc";
            }

            resetArrowIcons();
            updateArrowIcon(columnIndex, sortOrder[columnIndex]);
        }

        function resetArrowIcons() {
            var arrowIcons = document.querySelectorAll(".arrow");
            arrowIcons.forEach(function(arrow) {
                arrow.innerHTML = "";
            });
        }

        function updateArrowIcon(columnIndex, sortOrder) {
            var arrowIcon = document.querySelector("th:nth-child(" + (columnIndex + 1) + ") .arrow");

            if (sortOrder === "asc") {
                arrowIcon.innerHTML = `<svg class='table-icon'><use xlink:href='image/sprite.svg#arrow'></use></svg>`;
            } else {
                arrowIcon.innerHTML = `<svg class='table-icon table-icon-wrap'><use xlink:href='image/sprite.svg#arrow'></use></svg>`;
            }
        }

        function tableSearch() {
            var phrase = document.getElementById('search-text');
            var table = document.getElementById('table');
            var regPhrase = new RegExp(phrase.value, 'i');
            var flag = false;
            for (var i = 1; i < table.rows.length; i++) {
                flag = false;
                for (var j = table.rows[i].cells.length - 1; j >= 0; j--) {
                    flag = regPhrase.test(table.rows[i].cells[j].innerHTML);
                    if (flag) break;
                }
                if (flag) {
                    table.rows[i].style.display = "";
                } else {
                    table.rows[i].style.display = "none";
                }

            }
        }
    </script>

</body>

</html>