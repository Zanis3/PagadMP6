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
    <div class="view-table">
        <div class="menu-header">
            <h2>VIEWING RECORDS</h2>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" style="flex-direction:row;gap:20px;">
            <select name="slcSortView">
                <option value="" selected>None</option>
                <option value="Yahoo">Yahoo</option>
                <option value="Gmail">Gmail</option>
                <option value="Name (Ascending)">Name (Ascending)</option>
                <option value="Name (Descending)">Name (Descending)</option>
            </select>
            <input type="submit" value="Go" name="btnGoSort" style="padding:10px 25px;">
            <input type="search" name="txtSearch" placeholder="Search records" value="<?php echo $_POST['txtSearch'];?>">
            <input type="submit" value="Search" name="btnSearch" style="padding:10px 25px;">
        </form>
        <table style="width:90%;">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Date of Registration</th>
            </tr>

            <?php
            $sort = $_POST['slcSortView'];
            $sortLine = $connection->execute_query('SELECT * FROM tbl_guestinfo');
            $sortMessage = 'USER ID';
            
            if(isset($_POST['btnGoSort'])){
                if($sort == "Yahoo"){
                    $sortLine = $connection->execute_query("SELECT * FROM tbl_guestinfo WHERE user_email REGEXP '.*@yahoo\\.com$'");
                    $sortMessage = 'EMAIL (YAHOO)';
                }
                elseif($sort == "Gmail"){
                    $sortLine = $connection->execute_query("SELECT * FROM tbl_guestinfo WHERE user_email REGEXP '.*@gmail\\.com$'");
                    $sortMessage = 'EMAIL (GMAIL)';
                }
                elseif($sort == "Name (Ascending)"){
                    $sortLine = $connection->execute_query('SELECT * FROM tbl_guestinfo ORDER BY user_name ASC');
                    $sortMessage = 'NAME (ASCENDING)';
                }
                elseif($sort == "Name (Descending)"){
                    $sortLine = $connection->execute_query('SELECT * FROM tbl_guestinfo ORDER BY user_name DESC');
                    $sortMessage = 'NAME (DESCENDING)';
                }
            }

            if(isset($_POST['btnSearch'])){
                $searchItem = $_POST['txtSearch'];

                $sortLine = $connection->execute_query("SELECT * FROM tbl_guestinfo WHERE user_name LIKE ?", ["%$searchItem%"]);
                $sortMessage = 'SEARCHED RECORD';
            }

            $fetchAccounts = $sortLine;

            while($row = $fetchAccounts->fetch_assoc()){
                echo '<tr>';
                echo '<td>'.$row['userID'].'</td>';
                echo '<td>'.$row['user_name'].'</td>';
                echo '<td>'.$row['user_email'].'</td>';
                echo '<td>'.$row['user_dateofregistration'].'</td>';
                echo '</tr>';
            }
            ?>

        </table>
        <p>SORTING BY: <?php echo $sortMessage;?></p>
    </div>
</div>

<?php
    include_once 'template/footer.php';
?>