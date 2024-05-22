<head>
    <link rel="stylesheet" href="style.css">
</head>

<?php
    session_start();
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE);

    require 'functions/config.php';
?>

<?php
    if(isset($_POST['btnSubmit'])){
        $email = $_POST['txtEmail'];
        $password = $_POST['txtPassword'];

        $loginCheck = 0;

        if(empty($email) || empty($password)){
            if(empty($email)){
                $loginError = "Enter your email and try again.";
            }
            if(empty($password)){
                $loginError = "Enter your password and try again.";
            }

            if(empty($email) && empty($password)){
                $loginError = "Enter your email and password and try again.";
            }
        }
        else{
            $loginCheck += 1;
        }

        $account = $connection->execute_query('SELECT * FROM tbl_guestinfo WHERE user_email = ? LIMIT 1', [$email]);
        $fetchAccount = $account->fetch_assoc();

        if(!$fetchAccount && !empty($email) && !empty($password)){
            $loginError = "Email not found in database. Please try again.";
        }
        else{
            if($password != $fetchAccount['user_password']){
                $loginError = "Password does not match email. Please try again.";
            }
            else{
                $loginCheck += 1;
            }
        }

        if($loginCheck == 2){
            $_SESSION['name'] = $fetchAccount['user_name'];
            header('Location:home.php');
        }
    }
?>

<div class="header">
    <div class="header-title">
        <h1>ONLINE GUEST REGISTRATION</h1>
    </div>
    <div class="navbar">
        <a href="register.php" style="width:100%;">Register</a>
    </div>
</div>

<div class="content">
    <div class="login-container">
        <h2 style="text-align:center;">Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <table style="border:none;">
                <tr>
                    <td style="border:none;"><p style="font-weight:bold; color:white;">Email:</p></td>
                    <td style="border:none;"><input type="email" name="txtEmail" value="<?php echo $_POST['txtEmail'];?>"></td>
                </tr>
                <tr>
                    <td style="border:none;"><p style="font-weight:bold; color:white;">Password:</p></td>
                    <td style="border:none;"><input type="password" name="txtPassword" value="<?php echo $_POST['txtPassword'];?>"></td>
                </tr>
            </table>
            <input type="submit" value="Login" name="btnSubmit" style="padding:6% 12%;">
        </form>
    </div>

    <div class="error-container" style="<?php if(isset($_POST['btnSubmit']) && $loginCheck != 2){echo 'display:flex;';}?>">
        <p class="warning"><?php echo $loginError;?></p>
    </div>
</div>

<?php
    include_once 'template/footer.php';
?>