<?php
// Include config file
require_once 'config.php';


$email = $_SESSION['email'];

/* UNCOMMENT FOR LOGIN */
if(!isset($email) && empty($email)){
    header("location: signin.php");
    exit;
}

$_SESSION["vid"] = "";

// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM prof WHERE email = '$email'");
$row = mysqli_fetch_assoc($result);
$pic=$row['picture'];

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Use title if it's in the page YAML frontmatter -->
    <title>Flixnet</title>

    <meta name="description" content="XAMPP is an easy to install Apache distribution containing MariaDB, PHP and Perl." />
    <meta name="keywords" content="xampp, apache, php, perl, mariadb, open source distribution" />
    <meta http-equiv="Cache-control" content="no-cache">

    <link href="/dashboard/images/favicon.png" rel="icon" type="image/png" />

    <!-- BOOSTRAP -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <!-- OWL CAROUSEL -->
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,400italic,300italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="assets/css/docs.theme.min.css">
    <link rel="stylesheet" href="assets/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/owlcarousel/assets/owl.theme.default.min.css">
    <script src="assets/vendors/jquery.min.js"></script>
    <script src="assets/owlcarousel/owl.carousel.js"></script>

    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />


</head>

<body class="home">
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
    <div class="content">
        <div class="carousel popular">
            <!-- INSERT VIDEOS
            insert into inventory (name, director, rating, genre, picture, popular) values
            Picture: 900x460
            -->

            <h1>Popular on Flixnet CANCEL SUBSCRIPTION BEE MOVIE</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory WHERE popular = 1");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<a href=\"watch.php\"></a><img src=\"images/Flixnet/movie_posters/".$row['picture']."\" onclick=\"send_to_watch(".$row["vid"].")\"></a>";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel movies">
            <h1>Movies</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory, movie WHERE inventory.vid = movie.vid");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel">
            <h1>TV Shows</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory, tv_show WHERE inventory.vid = tv_show.vid");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel movies">
            <h1>Comedy</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory ORDER BY RAND() LIMIT 2");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel movies">
            <h1>Horror</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory, movie WHERE inventory.vid = movie.vid");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel movies">
            <h1>Action</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory ORDER BY RAND() LIMIT 2");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel movies">
            <h1>Crime</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory ORDER BY RAND() LIMIT 2");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel movies">
            <h1>Biography</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory ORDER BY RAND() LIMIT 2");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel movies">
            <h1>Romance</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory ORDER BY RAND() LIMIT 2");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel movies">
            <h1>Sci-Fi</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory ORDER BY RAND() LIMIT 2");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <div class="carousel children">
            <h1>Children</h1>
            <div class="loop owl-carousel">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM inventory ORDER BY RAND() LIMIT 2");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class=\"item\">";
                        echo "<img src=\"images/Flixnet/movie_posters/".$row['picture']."\">";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                $('.loop').owlCarousel({
                    center: true,
                    items: 2,
                    loop: true,
                    margin: 10,
                    autoHeight: true,
                    autoWidth: true,
                    responsive: {
                        600: {
                            items: 4
                        }
                    }
                });
            });
        </script>
    </div>
    <script>
        $(document).ready(function () {
            $(".owl-carousel").owlCarousel();
        });
    </script>
    <script>
        function send_to_watch(vid) {
            console.log(vid);
            $.ajax({
                method: "POST",
                url: "setseshvid.php",
                data:{action: vid},
            });
        window.location = "watch.php";
        }
    </script>
</body>
</html>