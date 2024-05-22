<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'functions/config.php';
include_once 'template/header.php';

$deleteError = '';
$updateCheck = false;
$deletedname = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['btnSearchID'])) {
        $searchID = $connection->prepare('SELECT * FROM tbl_guestinfo WHERE userID = ? LIMIT 1');
        $searchID->bind_param('i', $_POST['txtSearchID']);
        $searchID->execute();
        $idInfo = $searchID->get_result()->fetch_assoc();

        if($idInfo) {
            $ID = $idInfo['userID'];
            $deletedname = $idInfo['user_name'];
            $email = $idInfo['user_email'];
            $date = $idInfo['user_dateofregistration'];
            $updateCheck = true;
        } 
        else {
            $deleteError = 'No results found...';
        }
        $searchID->close();
    }

    if(isset($_POST['btnDelete'])) {
        if (empty($_POST['rdoDelete'])) {
            $deleteError = 'Please select an option before continuing.';
        } 
        elseif ($_POST['rdoDelete'] == 'No') {
            $deleteError = 'You selected no.';
        } 
        else {
            $_SESSION['deleted_name'] = $deletedname;
            $delete = $connection->prepare("DELETE FROM tbl_guestinfo WHERE userID = ?");
            $delete->bind_param('i', $_POST['txtUserID']);
            $delete->execute();
            $delete->close();

            header('Location: deletesuccessful.php');
            exit();
        }
    }
}
?>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<div class="content">
    <div class="delete" style="gap:10px;">
        <div class="menu-header">
            <h2>DELETE RECORD</h2>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="number" name="txtSearchID" placeholder="Search User ID here." value="<?php echo isset($_POST['txtSearchID']) ? htmlspecialchars($_POST['txtSearchID']) : ''; ?>">
            <input type="submit" value="Search" name="btnSearchID" style="padding:10px 25px;">
            <table style="border:none;">
                <?php if($updateCheck): ?>
                    <tr>
                        <td style="text-align:right; border:none;">User ID: </td>
                        <td style="border:none;"><?php echo htmlspecialchars($ID);?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right; border:none;">Name: </td>
                        <td style="border:none;"><?php echo htmlspecialchars($deletedname);?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right; border:none;">Email: </td>
                        <td style="border:none;"><?php echo htmlspecialchars($email);?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right; border:none;">Date of Registration: </td>
                        <td style="border:none;"><?php echo htmlspecialchars($date);?></td>
                    </tr>
                <?php endif; ?>
            </table>
            <?php if($updateCheck): ?>
                <p>Deleting <?php echo htmlspecialchars($deletedname); ?>'s record. Are you sure?</p>
                <span>
                    <input type="radio" value="Yes" name="rdoDelete"> Yes
                    <input type="radio" value="No" name="rdoDelete"> No
                </span>
                <input type="hidden" name="txtUserID" value="<?php echo htmlspecialchars($ID); ?>">
                <input type="submit" value="Delete" name="btnDelete" style="padding:10px 25px;">
            <?php endif; ?>
        </form>
    </div>

    <?php if(!empty($deleteError)): ?>
        <div class="error-container" style="display:flex;">
            <p class="warning"><?php echo htmlspecialchars($deleteError); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php
include_once 'template/footer.php';
?>