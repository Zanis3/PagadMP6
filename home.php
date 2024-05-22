<head>
    <link rel="stylesheet" href="style.css">
</head>

<?php
    session_start();
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE);

    require 'functions/config.php';
    include_once 'template/header.php';
?>

<div class="content">
    <div class="menu">
        <div class="menu-header">
            <h2>Welcome <?php echo $_SESSION['name'].'!'?></h2>
            <p style="padding-top:0;margin-top:0;">What would you like to do today?</p>
        </div>

        <?php
            if(isset($_POST['btnView'])){
                header('Location: view.php');
            }

            if(isset($_POST['btnSearch'])){
                header('Location: search.php');
            }

            if(isset($_POST['btnUpdate'])){
                header('Location: update.php');
            }

            if(isset($_POST['btnDelete'])){
                header('Location: delete.php');
            }

            if(isset($_POST['btnLogout'])){
                session_destroy();
                header('Location: index.php');
            }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" style="width:100%;display:flex;flex-direction:column;gap:10px;">
            <input type="submit" name="btnView" value="View Records" class="button-view">
            <input type="submit" name="btnSearch" value="Search Records" class="button-view">
            <input type="submit" name="btnUpdate" value="Update Record" class="button-view">
            <input type="submit" name="btnDelete" value="Delete Record" class="button-view">
            <input type="submit" name="btnLogout" value="Logout Account" class="button-view">
        </form>
    </div>
</div>

<?php
    include_once 'template/footer.php';
?>