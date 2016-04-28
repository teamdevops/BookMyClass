<!DOCTYPE html>
<?php
session_start();
if (! empty($_SESSION['logged_in'])) {
    $name = $_SESSION['name'];
}
else {
     header('Location: index.html');
}
?>
<html>
<head>
    <title>DocTrack - Home</title>
    <link rel="stylesheet" type="text/css" href="css/theme.css">
</head>
<body>
    <h1><center>Book My Class</center></h1>
    <hr/>
    <div style="border-bottom:1px solid #000;">
        <div class="left"><h2>Hello, <?php echo $name; ?> !</h2></div>
        <div class="right"><h3><a href="logout.php"> Logout </a></h3></div>
    </div>
    <br/> <br/>
    <div class="textContainer">
        <p><h4> Please click on the link to book the class :</h4></p>
    <a href="http://localhost:9003/#/week"> BookMyClass</a>
    </div>
    </body
</html>
