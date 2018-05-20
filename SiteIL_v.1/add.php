<?php
require_once 'app/helpers.php';
session_start();

if (!users_verification()) {
    header('Location: signin.php');
    die;
}

$title = 'Add post';
$error = '';

if (isset($_POST['submit'])) {

    $NewTitle = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $NewTitle = trim($NewTitle);
    $NewArticle = filter_input(INPUT_POST, 'article', FILTER_SANITIZE_STRING);
    $NewArticle = trim($NewArticle);

    if (!$NewTitle) {
        $error = 'Are you sure you want to save without a title?';
    } elseif (!$NewArticle) {
        $error = 'Are you sure you want to save the article without a body?';
    } else {
        $log = mysqli_connect('localhost', 'root', '', 'fakebook');
        $NewTitle = mysqli_real_escape_string($log, $NewTitle);
        $NewArticle = mysqli_real_escape_string($log, $NewArticle);
        mysqli_query($log, "SET NAMES utf8");
        $useID = $_SESSION['id'];
        $sql = "INSERT INTO wall VALUES('',$useID, '$NewTitle', '$NewArticle', NOW())";
        $match = mysqli_query($log, $sql);
        if ($match && mysqli_affected_rows($log) > 0) {
            header('Location: wall.php?sm=Your post has been saved');
            die;
        }
    }
}
?>

<?php include 'templates/site_header.php' ?>
<div class="content form-group">
    <form method="POST" action="">
        <h3>Write something:</h3>
        <label for="title"></label>
        <input type="text" name="title" id="title"  placeholder="Your Title"><br><br>
        <label for="article"></label>
        <textarea class="form-control" rows="5" id="comment" name="article" placeholder="Your Article"></textarea><br>
        <input type="submit" name="submit" value="Update">
        <input type="button" value="Cancel" onclick="window.location = 'wall.php'">
        <span class="error"><?= $error ?></span>
    </form>
</div>
<?php include 'templates/site_footer.php' ?>
