<?php
require_once 'app/helpers.php';
session_start();
$title = 'Delete';

if (!users_verification()) {
    header('Location: signin.php');
    die;
}

if (isset($_POST['submit'])) {

    $post = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_STRING);
    if ($post && is_numeric($post)) {
        $userID = $_SESSION['id'];
        $log = mysqli_connect('localhost', 'root', '', 'fakebook');
        $post = mysqli_real_escape_string($log, $post);
        $sql = "DELETE FROM wall WHERE id = $post AND user_id = $userID";
        $match = mysqli_query($log, $sql);
        if ($match && mysqli_affected_rows($log) == 1) {
            header('Location: wall.php?sm=Your post was removed');
            die;
        }
    }
}
?>

<?php include 'templates/site_header.php' ?>
<div class="content">
    <form action="" method="POST">
        <input type="hidden" name="token" value="<?= $token; ?>">
        <h3>Do you want to delete your post?</h3>
        <input type="submit" name="submit" value="Delete">
        <input type="button" value="Cancel" onclick="window.location = 'wall.php';">
    </form>
</div>
<?php include 'templates/site_footer.php' ?>