<?php  
require_once 'app/helpers.php';
session_start();

if (!users_verification()) {
    header('Location: signin.php');
    die;
}

$title = 'Edit your avatar';
$error = '';
define('MAX_IMAGE_SIZE', 1024 * 1024 * 5);

if (isset($_POST['submit'])) {

    $ex = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

    if (!empty($_FILES['image']['name'])) {

        if (is_uploaded_file($_FILES['image']['tmp_name'])) {

            if ($_FILES['image']['error'] == 0 && $_FILES['image']['size'] <= MAX_IMAGE_SIZE) {

                $fileinfo = pathinfo($_FILES['image']['name']);
                
                if (in_array(strtolower($fileinfo['extension']), $ex)) {
                    
                    $file_name = date('Y.m.d.H.i.s') . '-' . $_FILES['image']['name'];
                    $_SESSION['user_avatar'] = $file_name;
                    move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $file_name);
                    $link = mysqli_connect('localhost', 'root', '', 'fakebook');
                    mysqli_query($log, "SET NAMES utf8");
                    $uid = $_SESSION['id'];
                    $sql = "UPDATE users SET avatar = '$file_name' WHERE id = '$uid'";
                    $result = mysqli_query($link, $sql);

                    if ($result && mysqli_affected_rows($link) == 1) {
                        $_SESSION['avatar'] = $file_name;
                        header('location: wall.php?sm=Your profile image updated');
                        exit;
                    }
                }
            }
        }
    }
}

?>

<?php include 'templates/site_header.php' ?>

<div class="content"><br><br><br><br><br>
    <h1>My Profile</h1>
    <p><?= $_SESSION['Fname'] . ' ' . $_SESSION['Lname'] ?> Article for my profile demo..</p>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="image">Image profile:</label><br>
        <input type="file" name="image" id="image"><br><br>
        <input type="submit" name="submit" value="Upload image">
        <input type="button" value="Cancel" onclick="window.location = 'wall.php';">
        <span class="error"><?= $error; ?></span>
    </form>
</div>

<?php include 'templates/site_footer.php' ?>

