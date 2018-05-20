<?php

require_once 'app/helpers.php';
session_start();

if (isset($_SESSION['id'])) {
    header('Location: wall.php');
    die;
}

$title = 'Sign in page';
$error['email'] = $error['password'] = '';

if (isset($_POST['submit'])) {

    if (isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $email = trim($email);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password = trim($password);
        $passReg = "/^\w{5,12}$/";

        // Errors :
        if (!$email) {
            $error['email'] = ' * Required field';
        } elseif (!$password || !preg_match($passReg, $password)) {
            $error['password'] = ' * Required field';
        } else {

            $log = mysqli_connect('localhost', 'root', '', 'fakebook');
            mysqli_query($log, "SET NAMES utf8");
            $email = mysqli_real_escape_string($log, $email);
            $password = mysqli_real_escape_string($log, $password);
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $match = mysqli_query($log, $sql);

            if ($match && mysqli_num_rows($match) > 0) {
                $user = mysqli_fetch_assoc($match);

                if (password_verify($password, $user['password'])) {

                    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    if (isset($_POST['checkbox'])) {
                        setcookie(session_name(), session_id(), time() + 60 * 60 * 24 * 365, '/');
                    }
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['Fname'] = $user['Fname'];
                    $_SESSION['Lname'] = $user['Lname'];
                    $_SESSION['avatar'] = $user['avatar'];
                    header('Location: wall.php?sm=Welcome back');
                    die;
                } else {
                    $error['password'] = $error['email'] = ' * Detail is unvalid';
                }
            } else {
                $error['password'] = $error['email'] = ' * Detail is unvalid';
            }
        }
    }

    $token = csrf_token();
} else {

    $token = csrf_token();
}
?>

<?php include 'templates/site_header.php' ?>
<div class="content"><br><br><br><br><br><br>
    
    <form action="" method="POST">
        <input type="hidden" name="token" value="<?= $token; ?>">
        <h3>Members sign in:</h3>
        <label for="email"></label>
        <input type="text" name="email" id="email"  placeholder="Email" value="<?= old('email'); ?>">
        <span class="error"><?= $error['email'] ?></span><br><br>
        <label for="password"></label>
        <input type="password" name="password" id="password" placeholder="password">
        <span class="error"><?= $error['password'] ?></span><br><br>
        <input type="submit" name="submit" value="Render info">
        <label for="checkbox"></label>            
        <input type="checkbox" name="checkbox" checked="checked">Keep my details
        <br><br>
    </form>
    
</div>
<?php include 'templates/site_footer.php' ?>
