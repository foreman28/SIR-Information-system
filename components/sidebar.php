<sidebar class="sidebar">
    <div class="sidebar__logo">
        <a href="project.php"><img src="./image/logo.svg" alt="logo"></a>
    </div>

    <div class="sidebar__nav">
        <?php if (isset($_SESSION["user_id"])) { ?>
            <nav class="nav">
                <ul class="nav__list">
                    <li class="nav__item <?php echo ($_SERVER['REQUEST_URI'] == "/project.php") ? 'active' : '' ?>">
                        <a class="nav__link" href="project.php">
                            <svg class="nav__icon">
                                <use xlink:href="image/sprite.svg#suitcase"></use>
                            </svg>
                            <span>Проекты</span>
                        </a>
                    </li>

                    <li class="nav__item <?php echo ($_SERVER['REQUEST_URI'] == "/tasks.php") ? 'active' : '' ?>">
                        <a class="nav__link" href="tasks.php">
                            <svg class="nav__icon">
                                <use xlink:href="image/sprite.svg#file"></use>
                            </svg>
                            <span>Задачи</span>
                        </a>
                    </li>

                    <li class="nav__item <?php echo ($_SERVER['REQUEST_URI'] == "/equipment.php") ? 'active' : '' ?>">
                        <a class="nav__link" href="equipment.php">
                            <svg class="nav__icon">
                                <use xlink:href="image/sprite.svg#tools"></use>
                            </svg>
                            <span>Оборудование</span>
                        </a>
                    </li>

                    <li class="nav__item <?php echo ($_SERVER['REQUEST_URI'] == "/employees.php") ? 'active' : '' ?>">
                        <a class="nav__link" href="employees.php">
                            <svg class="nav__icon">
                                <use xlink:href="image/sprite.svg#user-group"></use>
                            </svg>
                            <span>Сотрудники</span>
                        </a>
                    </li>

                    <li class="nav__item <?php echo ($_SERVER['REQUEST_URI'] == "/reports.php") ? 'active' : '' ?>">
                        <a class="nav__link" href="reports.php">
                            <svg class="nav__icon">
                                <use xlink:href="image/sprite.svg#report"></use>
                            </svg>
                            <span>Отчеты</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php } ?>
    </div>

    <?php if (isset($_SESSION["user_id"])) { ?>
        <a href="profile.php" class="sidebar__profile nav__link <?php echo ($_SERVER['REQUEST_URI'] == "/profile.php") ? 'active' : '' ?>">
            <svg class="sidebar__profile__icon">
                <use xlink:href="image/sprite.svg#avatar"></use>
            </svg>
            <div class="sidebar__profile__content">

                <div class="sidebar__profile__name"><?php echo $_SESSION["user_name"]; ?></div>

                <div class="sidebar__profile__status <?php echo ($_SESSION["user_status"] == "admin") ? 'admin' : 'user'; ?>"><?php echo $_SESSION["user_status"]; ?></div>
            </div>
        </a>
    <?php } ?>

    <div class="sidebar__other">
        <div class="nav">
            <ul class="nav__list">
                <li class="nav__item">
                    <a class="nav__link" href="logout.php">
                        <svg class="nav__icon">
                            <use xlink:href="image/sprite.svg#logout"></use>
                        </svg>
                        <span>Выход</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</sidebar>