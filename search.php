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
    <div class="search">
        <div class="menu-header">
            <h2>SEARCH RECORD</h2>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <input type="number" name="txtSearchID" placeholder="Search User ID here." value="<?php echo $_POST['txtSearchID'];?>">
            <input type="submit" value="Search" name="btnSearchID" style="padding:10px 25px;">
        </form>
        <table style="border:none;">
            <?php
                if(isset($_POST['btnSearchID'])){
                    $searchID = $connection->execute_query('SELECT * FROM tbl_guestinfo WHERE userID = ? LIMIT 1', [$_POST['txtSearchID']]);
                    $idInfo = $searchID->fetch_assoc();

                    $ID = $idInfo['userID'];
                    $name = $idInfo['user_name'];
                    $email = $idInfo['user_email'];
                    $date = $idInfo['user_dateofregistration'];

                    if($searchID->num_rows == 1){
                        echo '<tr><td style="text-align:right">User ID: </td><td>'.$ID.'</td></tr>';
                        echo '<tr><td style="text-align:right">Name: </td><td>'.$name.'</td></tr>';
                        echo '<tr><td style="text-align:right">Email: </td><td>'.$email.'</td></tr>';
                        echo '<tr><td style="text-align:right">Date of Registration: </td><td>'.$date.'</td></tr>';
                    }
                    else{
                        echo '<tr><td>No results found...</td></tr>';
                    }
                }
            ?>
        </table>
    </div>
</div>

<?php
    include_once 'template/footer.php';
?>