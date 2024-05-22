<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'functions/config.php';
include_once 'template/header.php';

$updateCheck = false;
$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['btnSearchID'])) {
        $getID = (int)$_POST['txtSearchID'];
        $updateRecord = $connection->prepare('SELECT * FROM tbl_guestinfo WHERE userID = ? LIMIT 1');
        $updateRecord->bind_param('i', $getID);
        $updateRecord->execute();
        $userData = $updateRecord->get_result()->fetch_assoc();
        
        $updateFullName = $connection->prepare('SELECT * FROM tbl_guestname WHERE userID = ? LIMIT 1');
        $updateFullName->bind_param('i', $getID);
        $updateFullName->execute();
        $fullNameData = $updateFullName->get_result()->fetch_assoc();
        
        if ($userData && $fullNameData) {
            $updateCheck = true;
        } else {
            $message = 'No results found...';
        }
        
        $updateRecord->close();
        $updateFullName->close();
    }

    if(isset($_POST['btnUpdateRecord'])) {
        $userID = (int)$_POST['txtUserID'];
        $newFirstName = $_POST['txtFirstNameUpdate'];
        $newLastName = $_POST['txtLastNameUpdate'];
        $newEmail = $_POST['txtEmailUpdate'];
        $newFullName = $newLastName . ", " . $newFirstName;

        $updateDataInfo = $connection->prepare("UPDATE tbl_guestinfo SET user_name = ?, user_email = ? WHERE userID = ?");
        $updateDataInfo->bind_param('ssi', $newFullName, $newEmail, $userID);
        $updateDataInfo->execute();
        $updateDataInfo->close();

        $updateNameInfo = $connection->prepare("UPDATE tbl_guestname SET guestname_last = ?, guestname_first = ? WHERE userID = ?");
        $updateNameInfo->bind_param('ssi', $newLastName, $newFirstName, $userID);
        $updateNameInfo->execute();
        $updateNameInfo->close();

        $message = "Record has been updated.";
        $updateCheck = false;
    }
}
?>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<div class="content">
    <div class="update">
        <div class="menu-header">
            <h2>UPDATE RECORD</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="gap:20px;">
                <input type="number" name="txtSearchID" placeholder="Search User ID here." value="<?php echo isset($_POST['txtSearchID']) ? htmlspecialchars($_POST['txtSearchID']) : ''; ?>">
                <input type="submit" value="Search" name="btnSearchID" style="padding:10px 25px;">
                <table style="border:none">
                    <?php if($updateCheck): ?>
                        <tr>
                            <td style="text-align:right; border:none">User ID: </td>
                            <td style="text-align:left; border:none"><?php echo $getID; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none">Last Name: </td>
                            <td style="text-align:left; border:none"><input type="text" name="txtLastNameUpdate" value="<?php echo htmlspecialchars($fullNameData['guestname_last']); ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none">First Name: </td>
                            <td style="text-align:left; border:none"><input type="text" name="txtFirstNameUpdate" value="<?php echo htmlspecialchars($fullNameData['guestname_first']); ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none">Email Address: </td>
                            <td style="text-align:left; border:none"><input type="email" name="txtEmailUpdate" value="<?php echo htmlspecialchars($userData['user_email']); ?>"></td></tr>
                        <tr>
                            <td style="text-align:right; border:none">Registration Date: </td>
                            <td style="text-align:left; border:none"><?php echo htmlspecialchars($userData['user_dateofregistration']); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td style="border:none;"><?php echo $message; ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
                <?php if($updateCheck): ?>
                    <input type="hidden" name="txtUserID" value="<?php echo $getID; ?>">
                    <input type="submit" value="Update" name="btnUpdateRecord" style="padding:10px 25px;">
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php
include_once 'template/footer.php';
?>