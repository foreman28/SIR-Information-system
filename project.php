<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (isset($_GET['delete_project']) && $_SESSION["user_status"] === 'admin') {
    $project_id = $_GET['delete_project'];

    $delete_sql = "DELETE FROM Projects WHERE Project_ID = $project_id";

    if ($conn->query($delete_sql) === TRUE) {
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
        <h1 class="title">Проекты</h1>
        
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
            $sql = "SELECT * FROM Projects";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) { ?>
                <table cellspacing='0' id='table'>
                    <tr>
                        <th onclick='sortTable(0)'>ID <span class='arrow'></span></th>
                        <th onclick='sortTable(1)'>Название <span class='arrow'></span></th>
                        <th>Дата начала</th>
                        <th>Дата окончания</th>
                        <th>Описание</th>
                        
                        <?php if ($_SESSION["user_status"] === 'admin') { ?>
                        <th>Изменить</th>
                        <th>Удалить</th>
                        <?php } ?>
                    </tr>
                <?php

                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td class='text-center'><?php echo $row["Project_ID"] ?></td>
                        <td><a href="tasks.php?project_id=<?php echo $row["Project_ID"] ?>"><?php echo $row["Name"] ?></a></td>
                        <td><?php echo $row["Start_Date"] ?></td>
                        <td><?php echo $row["End_Date"] ?></td>
                        <td title="<?php echo $row["Description"]?>"><?php echo $row["Description"] ?></td>
                    
                        <?php if ($_SESSION["user_status"] === 'admin') { ?>
                        <td>
                            <a href="edit_project.php?edit_project=<?php echo $row["Project_ID"] ?>">Изменить</a>
                        </td>
                        <?php } ?>
                        
                        <?php if ($_SESSION["user_status"] === 'admin') { ?>
                        <td>
                            <a href="project.php?delete_project=<?php echo $row["Project_ID"] ?>" onclick='return confirm("Вы уверены, что хотите удалить этот проект?")'>Удалить</a>
                        </td>
                        <?php } ?>
                        
                    </tr>
                    <?php
                }
                echo "</table>";
            } else {
                echo "<div class='warning'>Нет проектов</div>";
            }

            $conn->close();
                ?>
        </div>
        <div class="table-btns btns">
            <?php if ($_SESSION["user_status"] === 'admin') { ?>
                <a class="btn" href="add_project.php">Добавить проект</a>
            <?php } ?>
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