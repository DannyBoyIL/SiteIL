<?php
require_once 'app/helpers.php';
session_start();

if (!users_verification()) {
    header('Location: signin.php');
    die;
}

$title = $_SESSION['Fname'] . ' ' . $_SESSION['Lname'];
$error = ['password' => '', 'Fname' => '', 'Lname' => '', 'image' => ''];
define('MAX_IMAGE_SIZE', 1024 * 1024 * 5);

if (isset($_POST['submit'])) {

    if (isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {

        $ex = ['jpg', 'jpeg', 'png', 'gif', 'img', 'bmp'];
        $uid = $_SESSION['id'];
        $Fname = filter_input(INPUT_POST, 'Fname', FILTER_SANITIZE_STRING);
        $Fname = trim($Fname);
        $Lname = filter_input(INPUT_POST, 'Lname', FILTER_SANITIZE_STRING);
        $Lname = trim($Lname);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password = trim($password);
        $con_password = filter_input(INPUT_POST, 'con_password', FILTER_SANITIZE_STRING);
        $con_password = trim($con_password);
        $passReg = "/^\w{5,12}$/";
        $log = mysqli_connect('localhost', 'root', '', 'fakebook');
        mysqli_query($log, "SET NAMES utf8");


        $permit = TRUE;

        if (!$Fname || mb_strlen($Fname) < 2 || mb_strlen($Fname) > 25) {

            $permit = FALSE;
            $error['Fname'] = 'A required name must contain 2 to 25 characters';
        }

        if (!$Lname || mb_strlen($Lname) < 2 || mb_strlen($Lname) > 25) {

            $permit = FALSE;
            $error['Lname'] = 'A required name must contain 2 to 25 characters';
        }

//        if (!$password || !preg_match($passReg, $password)) {
//
//            $error['password'] = 'A required name must contain 5 to 12 characters';
//        } elseif ($password != $con_password) {
//
//            $error['password'] = 'The confirmetion must match your enitial password';
//        }

        if (!empty($_FILES['image']['name'])) {

            if (is_uploaded_file($_FILES['image']['tmp_name'])) {

                if ($_FILES['image']['error'] == 0 && $_FILES['image']['size'] <= MAX_IMAGE_SIZE) {

                    $fileinfo = pathinfo($_FILES['image']['name']);

                    if (in_array(strtolower($fileinfo['extension']), $ex)) {

                        $error['image'] = 'Try a different picture';
                        $file_name = date('d.m.Y.H.i.s') . '-' . $_FILES['image']['name'];
                        $_SESSION['avatar'] = $file_name;
                        move_uploaded_file($_FILES['image']['tmp_name'], mkdir('/images/ ' . $file_name));
                        print_r($_FILES['image']['tmp_name']);
                    die;
                        $sql = "UPDATE users SET avatar = '$file_name' WHERE id = '$uid'";
                        $match = mysqli_query($log, $sql);
                    }
                }
            }
        } elseif (!empty($_SESSION['avatar']) && is_string($_SESSION['avatar']) && $_SESSION['avatar'] == $_SESSION['avatar']) {

            $file_name = $_SESSION['avatar'];
            $sql = "UPDATE users SET avatar = '$file_name' WHERE id = '$uid'";
            $match = mysqli_query($log, $sql);
        }

        if ($permit) {

            $Fname = mysqli_real_escape_string($log, $Fname);
            $Lname = mysqli_real_escape_string($log, $Lname);
//            $password = mysqli_real_escape_string($log, $password);
//            $password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET Fname = '$Fname', Lname = '$Lname' WHERE id = '$uid'"; //, password = '$password'
            $match = mysqli_query($log, $sql);
            if ($match && mysqli_affected_rows($log) == 1) {

                $_SESSION['Fname'] = $Fname;
                $_SESSION['Lname'] = $Lname;

                header('Location: wall.php?ms=Looking good &#9996;');
                die;
            }
        }
    }
    $token = csrf_token();
} else {

    $token = csrf_token();
}
?> 

<?php include 'templates/site_header.php' ?>

<div class="content">
    <h1>My Profile</h1>
    <p><?= $_SESSION['Fname'] . ' ' . $_SESSION['Lname'] ?> Article for my profile demo..</p>
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Update my details</h3>
        <div class="left-box">
            <input type="hidden" name="token" value="<?= $token; ?>">
            <label for="image"><img src="images/<?= $_SESSION['avatar']; ?>" border="0" width="250px"></label><br>
            <input type="file" name="image" id="image"><br>
            <span class="error"><?= $error['image'] ?></span>
        </div>
        <div class="right-box">
            <label for="Fname"></label>
            <input type="text" name="Fname" id="Fname"  placeholder="First name" value="<?= $_SESSION['Fname']; ?>">
            <span class="error"><?= $error['Fname'] ?></span><br><br>
            <label for="Lname"></label>
            <input type="Lname" name="Lname" id="Lname"   placeholder="Last name" value="<?= $_SESSION['Lname']; ?>">
            <span class="error"><?= $error['Lname'] ?></span><br><br>
            <!--            <label for="email"></label>
                        <input type="text" name="email" id="email"  placeholder="Email" value="<\?= $_SESSION['email']; ?>">
                        <span class="error"><\?= $error['email'] ?></span><br><br>
                        <label for="password"></label>
                        <input type="password" name="password" id="password" placeholder="New password">
                        <span class="error"><\?= $error['password'] ?></span><br><br>
                        <label for="con_password"></label>
                        <input type="password" name="con_password" id="con_password" placeholder="Confirem password">
                        <span class="error"><\?= $error['password'] ?></span><br><br>-->
            <input type="submit" name="submit" value="Update">
            <input type="button" value='Cancel' onclick=" window.location = 'wall.php'">
        </div>
    </form>
</div>

<?php include 'templates/site_footer.php' ?>
