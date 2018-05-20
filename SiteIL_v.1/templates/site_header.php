<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $title; ?></title>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    </head>
    <body>
        <div class="page-wrapper container-fluid">
            <div class="bg container-fluid">
                <div class="header container">
                    <ul>
                        <li><a href="./">SiteIL</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="wall.php?sm=Welcome back &#x270A;">Blog</a></li>
                        <?php if (!isset($_SESSION['id'])): ?>
                            <li><a href="signin.php">Signin</a></li>
                            <li><a href="signup.php">Signup</a></li>
                        <?php else: ?>
                            <li>
                                <a href="test.php"><img src="images/<?= $_SESSION['avatar']; ?>"  border="0" width="50px"> <?= htmlentities($_SESSION['Fname']) . ' ' . htmlentities($_SESSION['Lname']) ?> </a>
                            </li>
                            <li><a href="logout.php?sm=">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if (isset($_GET['sm'])): ?>
                <div id="sm-box"><h1><?= $_GET['sm']; ?></h1></div>
                <?php endif; ?>