<?php
require_once 'config.php';

$email = $_SESSION['email'];
$movie = "";

/* UNCOMMENT FOR LOGIN */
if(empty($email)){
    header("location: signin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["search"] = $_POST["search"];
    header("location: search.php");
}

// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM prof WHERE email = '$email'");
$row = mysqli_fetch_assoc($result);
$pic=$row['picture'];

$vid = $_SESSION['vid'];

// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM prof WHERE email = '$email'");
$row = mysqli_fetch_assoc($result);
$pic=$row['picture'];

$vid = $_SESSION['vid'];

$result_actor = mysqli_query($link, "SELECT * FROM actors WHERE vid = '$vid'");

$result = mysqli_query($link, "SELECT * FROM movie WHERE vid = '$vid'");
$movie_info = mysqli_fetch_assoc($result);

$result = mysqli_query($link, "SELECT * FROM tv_show WHERE vid = '$vid'");
$show_info = mysqli_fetch_assoc($result);

// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM inventory WHERE vid = '$vid'");
$vid_info = mysqli_fetch_assoc($result);

function asDollars($value) {
    return '$' . number_format($value, 2);
}
?>

<!doctype html>
<head>
<title>Chill</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
    <link href="https://photos-3.dropbox.com/t/2/AAAe92EgbXgeonk-d36KcegkK_uiCpftIRR-QH8Gno83Uw/12/312984599/png/32x32/1/_/1/2/favicon.png/EOq-pswEGJDOCiACKAI/WtlLIt8vWQXV1rwCYWxmCBEtFF87kuX-cFklLNMCp2k?preserve_transparency=1&size=2048x1536&size_mode=3" rel="icon" type="image/png" />
</head>
<body class="video-page">
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand nav-img" href="index.php">
            <img class="nav-img" src="images/Flixnet/chill_logo.png" alt="Chill Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home<a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shows.php">TV Shows</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="movies.php">Movies</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" method="POST">
                <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-secondary btn-search" type="submit">Search</button>
                </button>
            </form>
            <div class="nav-item dropdown">
                <button class="btn btn-secondary dropdown-toggle prof-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="prof_img dropdown-toggle" <?php echo 'src="images/Flixnet/prof_pics/'.$pic.'"'; ?>>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="profile.php">Profile</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="video">
        <h1 class="title"><?php echo $vid_info['name'] ?></h1>
        <div class="video-player">
            <iframe width="1000" height="563" src="<?php echo $vid_info['video_link'] ?>" align="middle" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
        <div class="vid-info">
            <hr>
            <p><?php echo $vid_info['description'] ?></p>
            <?php 
                $actors = "";
                while($actor_info = mysqli_fetch_assoc($result_actor)) {
                    $actors .= $actor_info['actor'] . ", ";
                }
                $actors = substr($actors, 0, strlen($actors)-2);
                echo "<p><strong>Actors</strong>: ".$actors."</p>";
            ?>
            <div class="generic-info">
                <p><strong>Director</strong>: <?php echo $vid_info['director'] ?></p>
                <p><strong>Rating</strong>: <?php echo $vid_info['rating'] ?></p>
                <p><strong>Genre</strong>: <?php echo $vid_info['genre'] ?></p>
            </div>
            <?php if ($movie_info) : ?>
                <div class="spec-info">
                    <p><strong>Budget</strong>: <?php echo asDollars($movie_info['budget']) ?></p>
                    <p><strong>Sequel</strong>: <?php echo $movie_info['sequel'] ?></p>
                    <p><strong>Length</strong>: <?php echo $movie_info['hours'] . "h " . $movie_info['minutes'] . "min" ?></p>
                </div>
            <?php else : ?>
                <div class="spec-info">
                    <p><strong>Season</strong>: <?php echo $show_info['season_no'] ?></p>
                    <p><strong>Episode</strong>: <?php echo $show_info['episode_no'] ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>