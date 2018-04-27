<?php
require_once 'config.php';

$email = $_SESSION['email'];

/* UNCOMMENT FOR LOGIN */
if(!isset($email) && empty($email)){
    header("location: signin.php");
    exit;
}

// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM prof WHERE email = '$email'");
$row = mysqli_fetch_assoc($result);
$pic=$row['picture'];
?>

<!doctype html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
</head>
<body class="video-page">
<nav class="navbar navbar-expand-lg">
        <a class="navbar-brand nav-img" href="#">
            <img class="nav-img" src="images/Flixnet/flixnet_logo.png" alt="Flixnet Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home<a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">TV Shows</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Movies</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-search btn-secondary" type="submit">Search</span>
                </button>
            </form>
            <div class="nav-item dropdown">
                <button class="btn btn-secondary dropdown-toggle prof-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="prof_img dropdown-toggle" <?php echo 'src="images/Flixnet/prof_pics/'.$pic.'"'; ?>>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="video">
        <div class="video-info">
            <h1>Title</h1>
        </div>
        <div class="video-player">
            <iframe width="1000" height="563" src="https://www.youtube.com/embed/2MpUj-Aua48" align="middle" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>
</body>

</html>