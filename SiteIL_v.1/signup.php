<?php
require_once 'app/helpers.php';
session_start();
$title = 'Sign up page';

$error = ['email' => '', 'password' => '', 'Fname' => '', 'Lname' => ''];

if (isset($_POST['submit'])) {

    if (isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {

        $Fname = filter_input(INPUT_POST, 'Fname', FILTER_SANITIZE_STRING);
        $Fname = trim($Fname);
        $Lname = filter_input(INPUT_POST, 'Lname', FILTER_SANITIZE_STRING);
        $Lname = trim($Lname);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $email = trim($email);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password = trim($password);
        $con_password = filter_input(INPUT_POST, 'con_password', FILTER_SANITIZE_STRING);
        $con_password = trim($con_password);
        $passReg = "/^\w{5,12}$/";
        $log = mysqli_connect('localhost', 'root', '', 'fakebook');
        mysqli_query($log, "SET NAMES utf8");
        $email = mysqli_real_escape_string($log, $email);

        $permit = TRUE;

        if (!$Fname || mb_strlen($Fname) < 2 || mb_strlen($Fname) > 25) {
            $permit = FALSE;
            $error['Fname'] = 'A required name must contain 2 to 25 characters';
        }

        if (!$Lname || mb_strlen($Lname) < 2 || mb_strlen($Lname) > 25) {
            $permit = FALSE;
            $error['Lname'] = 'A required name must contain 2 to 25 characters';
        }

        if (!$email) {
            $permit = FALSE;
            $error['email'] = ' * Required field';
        } elseif (compare_email($log, $email)) {
            $permit = FALSE;
            $error['email'] = ' * The email is taken';
        }

        if (!$password || !preg_match($passReg, $password)) {
            $permit = FALSE;
            $error['password'] = 'A required name must contain 5 to 12 characters';
        } elseif ($password != $con_password) {
            $permit = FALSE;
            $error['password'] = 'The confirmetion must match your enitial password';
        }

        if ($permit) {

            $Fname = mysqli_real_escape_string($log, $Fname);
            $Lname = mysqli_real_escape_string($log, $Lname);
            $email = mysqli_real_escape_string($log, $email);
            $password = mysqli_real_escape_string($log, $password);
            $password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users VALUES('', '$Fname', '$Lname', '$email', '$password', 'default.jpg', NOW())";
            $match = mysqli_query($log, $sql);

            if ($match && mysqli_affected_rows($log) == 1) {

                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['id'] = mysqli_insert_id($log);
                $_SESSION['Fname'] = $Fname;
                $_SESSION['Lname'] = $Lname;
                $_SESSION['email'] = $email;
                $_SESSION['avatar'] = 'default.jpg';
                header('Location: wall.php?sm=You have created an account with us');
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
    <form action="" method="POST">
        <h3>Create account</h3>
        <input type="hidden" name="token" value="<?= $token; ?>">
        <label for="Fname"></label>
        <input type="text" name="Fname" id="Fname"  placeholder="First name" value="<?= old('Fname'); ?>">
        <span class="error"><?= $error['Fname'] ?></span><br><br>
        <label for="Lname"></label>
        <input type="Lname" name="Lname" id="Lname"   placeholder="Last name" value="<?= old('Lname'); ?>">
        <span class="error"><?= $error['Lname'] ?></span><br><br>
        <label for="email"></label>
        <input type="text" name="email" id="email"  placeholder="Email" value="<?= old('email'); ?>">
        <span class="error"><?= $error['email'] ?></span><br><br>
        <label for="password"></label>
        <input type="password" name="password" id="password" placeholder="Password">
        <span class="error"><?= $error['password'] ?></span><br><br>
        <label for="con_password"></label>
        <input type="password" name="con_password" id="con_password" placeholder="Confirem password">
        <span class="error"><?= $error['password'] ?></span><br><br>
        <input type="submit" name="submit" value="Render info">
    </form>
</div>
<?php include 'templates/site_footer.php' ?>

