
<?php session_start();
$logged=true;
if (!isset($_SESSION['loggedin'])) {
    $logged=false;
}
$user_type="both";
if (isset($_SESSION['user_type'])) {
    $user_type=$_SESSION['user_type'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arena Realty</title>
    <link rel="stylesheet" href="project4_style.css" type="text/css">
</head>
<body>
    <div class="menu_bar">
        <div class="left_links">
            <div class="logo">
                <img src="logo.jpg" class="logo">
            </div>
            <div id="casino">
                <a href="index.php">Home</a>
            </div>
            <?php if ($logged == true && ($user_type=="both" || $user_type=="sell")) : ?>
                <div id="games">
                    <a href="myproperties.php"> My Properties</a>
                </div>
            <?php endif; ?>
            <?php if ($logged == true && ($user_type=="both" || $user_type=="buy")) : ?>
                <div id="games">
                    <a href="buyproperty.php"> Buy Property</a>
                </div>
            <?php endif; ?>
            <?php if ($logged == true && $_SESSION['username']==='admin') : ?>
                <div id="games">
                    <a href="admindashboard.php"> Admin Dashboard</a>
                </div>
            <?php endif; ?>
        </div>
        <?php if ($logged == false) : ?>
            <div class="right_links">
                <div id="login">
                    <a href="login.php">Log In</a>
                </div>
                <div class="join">
                    <a href="signup.php">Join Now</a>
                </div>
            </div>
        <?php else : ?>
            <div class="right_links">
                <div class="join">
                    <a href="profile.php?id=<?php echo $_SESSION["username"]; ?>">Profile</a>
                </div>
                <div class="join">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <br/>
    <div class="container">
