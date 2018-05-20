<?php
require_once 'app/helpers.php';
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: signin.php');
    die;
}

$title = 'Edit post';
$error = '';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$uid = $_SESSION['id'];

if ($id && is_numeric($id)) {

    $log = mysqli_connect('localhost', 'root', '', 'fakebook');
    mysqli_query($log, "SET NAMES utf8");

    $sql = "SELECT * FROM wall WHERE id = $id AND user_id = $uid";
    $match = mysqli_query($log, $sql);

    if ($match && mysqli_num_rows($match) == 1) {

        $posts = mysqli_fetch_assoc($match);
        $_REQUEST['title'] = $posts['title'];
        $_REQUEST['article'] = $posts['article'];
    } else {

        die;
    }
} else {

    header('Location: signin.php');
    die;
}

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

        $NewTitle = mysqli_real_escape_string($log, $NewTitle);
        $NewArticle = mysqli_real_escape_string($log, $NewArticle);
        $sql = "UPDATE wall SET title = '$NewTitle', article = '$NewArticle' " .
                " WHERE id = $id";
        mysqli_query($log, "SET NAMES utf8");
        $match = mysqli_query($log, $sql);

        if ($match) {

            header('Location: wall.php?sm=Your post has been updated');
        }
    }
}

?>

<?php include 'templates/site_header.php' ?>
<div class="content">
    <form action="" method="POST">
        <h3>Edit your post:</h3>
        <label for="title"></label>
        <input type="text" name="title" id="title"  placeholder="New Title" value="<?= old('title'); ?>"><br><br>
        <label for="article"></label>
        <textarea rows="10" cols="123" name="article" placeholder="New Article" style="resize: both; overflow: auto;"><?= old('article'); ?></textarea><br>
        <input type="submit" name="submit" value="Update">
        <input type="button" value="Cancel" onclick="window.location='wall.php'">
        <span class="error"><?= $error ?></span>
    </form>
</div>
<?php include 'templates/site_footer.php' ?>
