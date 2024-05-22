<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);

require 'functions/config.php';
include_once 'template/header.php';
?>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<div class="content">
    <div class="search">
        <div class="menu-header">
            <h2><?php echo isset($_SESSION['deleted_name']) ? htmlspecialchars($_SESSION['deleted_name']) : 'The record'; ?>'s Record has been deleted.</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnBack'])) {
                    header('Location: home.php');
                    exit();
                }
                ?>
                <input type="submit" value="Back" name="btnBack" style="padding:10px 25px;">
            </form>
        </div>
    </div>
</div>

<?php
include_once 'template/footer.php';
?>
