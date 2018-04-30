<?php
require_once 'config.php';

$email = $_SESSION['email'];
$movie = "";

/* UNCOMMENT FOR LOGIN */
if(!isset($email) && empty($email)){
    header("location: signin.php");
    exit;
}

// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM prof WHERE email = '$email'");
$row = mysqli_fetch_assoc($result);
$pic=$row['picture'];

$vid = $_SESSION['vid'];
// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM inventory WHERE vid = '$vid'");
$vid_info = mysqli_fetch_assoc($result);
$title=$vid_info['name'];

function asDollars($value) {
    return '$' . number_format($value, 2);
}
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
        <a class="navbar-brand nav-img" href="/">
            <img class="nav-img" src="images/Flixnet/flixnet_logo.png" alt="Flixnet Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home<a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shows.php">TV Shows</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="movies.php">Movies</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2 right" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-search btn-secondary right" type="submit">Search</span>
                </button>
            </form>
            <div class="nav-item dropdown right">
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
        <h1 class="title"><?php echo $title ?></h1>
        <div class="video-player">
            <iframe width="1000" height="563" src="<?php echo $vid_info['video_link'] ?>" align="middle" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
        <div class="vid-info">
            <hr>
            <p><?php echo $vid_info['description'] ?></p>
            <div class="generic-info">
                <p><strong>Director</strong>: <?php echo $vid_info['director'] ?></p>
                <p><strong>Rating</strong>: <?php echo $vid_info['rating'] ?></p>
                <p><strong>Genre</strong>: <?php echo $vid_info['genre'] ?></p>
            </div>
            <?php 
                $actors = "";
                $result = mysqli_query($link, "SELECT * FROM actors WHERE vid = '$vid'");
                while($vid_info = mysqli_fetch_assoc($result)) {
                    $actors .= $vid_info['actor'] . ", ";
                }
                $actors = substr($actors, 0, strlen($actors)-2);
                echo "<p><strong>Actors</strong>: ".$actors."</p>"
            ?>
            <?php 
                $result = mysqli_query($link, "SELECT * FROM movie WHERE vid = '$vid'");
            ?>
            <?php if (mysqli_num_rows($result)) : 
                $vid_info = mysqli_fetch_assoc($result); ?>
                <div class="spec-info">
                    <p><strong>Budget</strong>: <?php echo asDollars($vid_info['budget']) ?></p>
                    <p><strong>Sequel</strong>: <?php echo $vid_info['sequel'] ?></p>
                    <p><strong>Budget</strong>: <?php echo $vid_info['hours'] . "h " . $vid_info['minutes'] . "min" ?></p>
                </div>
            <?php else : 
                $result = mysqli_query($link, "SELECT * FROM tv_show WHERE vid = '$vid'");
                $vid_info = mysqli_fetch_assoc($result); ?>
                <div class="spec-info">
                    <p><strong>Season</strong>: <?php echo $vid_info['season_no'] ?></p>
                    <p><strong>Episode</strong>: <?php echo $vid_info['episode_no'] ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>