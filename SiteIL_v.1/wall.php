<?php
require_once 'app/helpers.php';
session_start();

if (!users_verification()) {
    header('Location: signin.php');
    die;
}

$title = 'Wall page';
$posts = [];
$userID = $_SESSION['id'];
$log = mysqli_connect('localhost', 'root', '', 'fakebook');
mysqli_query($log, "SET NAMES utf8");

$sql = "SELECT u.Fname, u.Lname, u.avatar, w.id, w.user_id, w.title, w.article, DATE_FORMAT(w.time,'%d/%m/%Y') date FROM wall AS w " .
        " JOIN users AS u ON u.id = w.user_id " .
        " ORDER BY w.time DESC";

$match = mysqli_query($log, $sql);

if ($match && mysqli_num_rows($match) > 0) {

    $posts = mysqli_fetch_all($match, MYSQLI_ASSOC);
}
?>

<?php include 'templates/site_header.php' ?>
</div>
<div class="content container-fluid">
    <div class="row">
        <div class="col-md-3">
            <h1>Welcome back</h1>
            <img src="images/<?= $_SESSION['avatar'] ?>" class="img-fluid" max-width="100%" width="260px" border="0" title="<?= $_SESSION['Fname'] ?> <?= $_SESSION['Lname'] ?>" alt="<?= $_SESSION['Fname'] ?> <?= $_SESSION['Lname'] ?>">
            <h3><?= $_SESSION['Fname'] ?> <?= $_SESSION['Lname'] ?></h3>
            <input type="button" value="+ write something.." onclick="window.location = 'add.php'"><hr>
            <img src="images/herbs/herb_socity.png" class="img-fluid" max-width="100%" title="herb_socity" alt="herb_socity" width="300px" border="0"/>
            <br><br><img src="images/herbs/herbs_sketch.jpg" class="img-fluid" max-width="100%" title="herbs_sketch" alt="herbs_sketch" width="300px" border="0"/>
            <hr>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" width="300px" height="auto" src="https://www.youtube.com/embed/8PZWbk-Y9Ns" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
        <div class="col-md-9">
            <?php if (isset($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <br><div class="post-bx">
                        <h3><img src="images/<?= $post['avatar'] ?>" width="50px" border="0"> <?= htmlentities($post['title']) ?></h3>
                        <p><?= str_replace("\n", "<br>", htmlentities($post['article'])) ?></p>
                        <hr>
                        <span class="post-details">
                            Composer <?= $post['Fname'] ?> <?= $post['Lname'] ?> | <?= $post['date'] ?>
                        </span>
                        <?php if ($userID == $post['user_id']): ?>
                            <span class="post-edit">
                                <a href="edit.php?id=<?= $post['id'] ?>">Edit</a>
                                <a href="delete.php?post=<?= $post['id'] ?>">Delete</a>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'templates/site_footer.php' ?>
