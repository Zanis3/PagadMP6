<head>
    <link rel="stylesheet" href="style.css">
</head>

<?php
    session_start();
    ini_set('display_errors', 0);
    #error_reporting(E_ALL & ~E_NOTICE);

    require 'functions/config.php';
?>

<?php
    if(isset($_POST['btnRegister'])){
        $firstName = $_POST['txtFirstName'];
        $lastName = $_POST['txtLastName'];
        $email = $_POST['txtEmail'];
        $password = $_POST['txtPassword'];
        $success = false;

        $registerCheck = 0;

        if(empty($email) || empty($password) || empty($firstName) || empty($lastName)){
            $registerError = "The following fields are empty: ";
            if(empty($email)){
                $registerError = $registerError . "<br>Email";
            }
            if(empty($password)){
                $registerError = $registerError . "<br>Password";
            }
            if(empty($firstName)){
                $registerError = $registerError . "<br>First Name";
            }
            if(empty($lastName)){
                $registerError = $registerError . "<br>Last Name";
            }
        }
        else{
            $registerCheck += 1;
        }

        $account = $connection->execute_query('SELECT * FROM tbl_guestinfo WHERE user_email = ? LIMIT 1', [$email]);
        $fetchAccount = $account->fetch_assoc();

        if($fetchAccount['user_email'] == $email && !empty($email)){
            $registerError = "Email already in database. Please login.";
        }
        else{
            $registerCheck += 1;
        }

        if($registerCheck == 2){

            $fullName = ucfirst($lastName . ", " . $firstName);
            $dateOfReg = date('Y-m-d');

            $registerAccount = $connection->prepare('INSERT INTO tbl_guestinfo (user_name, user_email, user_password, user_dateofregistration) VALUES (?, ?, ?, ?)');
            $registerAccount->bind_param('ssss', $fullName, $email, $password, $dateOfReg);
            $registerAccount->execute();
            $userID = $registerAccount->insert_id;
            $registerAccount->close();

            $addUserFullName = $connection->prepare('INSERT INTO tbl_guestname (userID, guestname_last, guestname_first) VALUES (?, ?, ?)');
            $addUserFullName->bind_param('sss', $userID, $lastName, $firstName);
            $addUserFullName->execute();
            $addUserFullName->close();

            $success = true;
            $registerError = "Account registration successful! Please login to continue.";
        }
    }
?>

<div class="header">
    <div class="header-title">
        <h1>ONLINE GUEST REGISTRATION</h1>
    </div>
    <div class="navbar">
        <a href="index.php" style="width:100%;">Login</a>
    </div>
</div>

<div class="content">
    <div class="login-container" style="height:45%">
        <h2 style="text-align:center;">Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <table style="border:none;">
                <tr>
                    <td style="border:none;"><p style="font-weight:bold; color:white;">First Name:</p></td>
                    <td style="border:none;"><input type="text" name="txtFirstName" value="<?php echo $_POST['txtFirstName'];?>"></td>
                </tr>
                <tr>
                    <td style="border:none;"><p style="font-weight:bold; color:white;">Last Name:</p></td>
                    <td style="border:none;"><input type="text" name="txtLastName" value="<?php echo $_POST['txtLastName'];?>"></td>
                </tr>
                <tr>
                    <td style="border:none;"><p style="font-weight:bold; color:white;">Email:</p></td>
                    <td style="border:none;"><input type="email" name="txtEmail" value="<?php echo $_POST['txtEmail'];?>"></td>
                </tr>
                <tr>
                    <td style="border:none;"><p style="font-weight:bold; color:white;">Password:</p></td>
                    <td style="border:none;"><input type="password" name="txtPassword" value="<?php echo $_POST['txtPassword'];?>"></td>
                </tr>
            </table>
            <input type="submit" value="Register" name="btnRegister" style="padding:6% 12%;">
        </form>
    </div>

    <div class="error-container" style="<?php if(isset($_POST['btnRegister']) && $registerCheck != 2){echo 'display:flex;';}?>">
        <p class="<?php $success ? 'success' : 'warning';?>"><?php echo $registerError;?></p>
    </div>
</div>

<?php
    include_once 'template/footer.php';
?>