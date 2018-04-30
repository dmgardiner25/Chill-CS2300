<?php 
require_once "config.php";

$email = $_SESSION['email'];

/* UNCOMMENT FOR LOGIN */
if(!isset($email) || empty($email)){
    header("location: signin.php");
    exit;
}

// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM prof WHERE email = '$email'");
$profrow = mysqli_fetch_assoc($result);
$pic=$picture=$profrow['picture'];
$pid = $profrow["pid"];
$result = mysqli_query($link, "SELECT * FROM subscription WHERE pid = '$pid'");
$subrow = mysqli_fetch_assoc($result);
$date = $subrow["start_date"];
$result = mysqli_query($link, "SELECT * FROM owner WHERE pid = '$pid'");
$ownerrow = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!empty($_SESSION["search"])) {
        $_SESSION["search"] = $_POST["search"];
        header("location: search.php");
    }
    $name = $profrow["name"];
    $email = $profrow["email"];
    $picture = $profrow["picture"];
    $addr = $ownerrow["addr"];
    $city = $ownerrow["city"];
    $state = $ownerrow["state"];
    $zip_code = $ownerrow["zip_code"];

    if(!empty($_POST["name"])) {
        $name = trim($_POST["name"]);
    } 

    if(!empty($_POST["email"])) {
        $email = trim($_POST["email"]);
        $_SESSION["email"] = $email;
    } 

    $picture = $_FILES['pic_to_upload']['name'];
    echo $picture;
    if (!$picture) {
        $picture = $pic;
    }

    $sql = "UPDATE prof SET name = ?, email = ?, picture = ? WHERE pid = '$pid'";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $picture);
        
        $target = "images/Flixnet/prof_pics/" . basename($picture);
            
        if (move_uploaded_file($_FILES['pic_to_upload']['tmp_name'], $target)) {
            $msg = "Image uploaded successfully";
        } else {
            $msg = "Failed to upload image";
        }

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    if(!empty($_POST["addr"])) {
        $addr = trim($_POST["addr"]);
    } 

    if(!empty($_POST["city"])) {
        $city = trim($_POST["city"]);
    } 

    if(!empty($_POST["state"])) {
        $state = trim($_POST["state"]);
    } 

    if(!empty($_POST["zip_code"])) {
        $zip_code = trim($_POST["zip_code"]);
    } 

    $sql = "UPDATE owner SET addr = ?, city = ?, state = ?, zip_code = ? WHERE pid = '$pid'";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssi", $addr, $city, $state, $zip_code);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            header('location: profile.php');
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    // Close statement
    mysqli_stmt_close($stmt);
}
// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>title</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="prof">
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
            <form class="form-inline my-2 my-lg-0 " method="POST">
                <input class="form-control mr-sm-2 right2" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-secondary btn-search right2" type="submit">Search</button>
                </button>
            </form>
            <div class="nav-item dropdown right2">
                <button class="btn btn-secondary dropdown-toggle prof-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="prof_img dropdown-toggle" <?php echo 'src="images/Flixnet/prof_pics/'.$pic.'"'; ?>>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row profile">
        <div class="col-md-3">
            <div class="profile-sidebar">
            <!-- SIDEBAR USERPIC -->
            <div class="profile-userpic">
                <img <?php echo 'src="images/Flixnet/prof_pics/'.$pic.'"'; ?> class="img-responsive" alt="">
            </div>
            <!-- END SIDEBAR USERPIC -->
            <!-- SIDEBAR USER TITLE -->
            <div class="profile-usertitle">
                <div class="profile-usertitle-name">
                <?php echo $profrow["name"]; ?>
                </div>
                <div class="profile-usertitle-job">
                Owner
                </div>
            </div>
            <!-- END MENU -->
            </div>
        </div>
        <form class="col-md-9 prof-info" method="POST" enctype="multipart/form-data">
            <div class= "basic-info">
            <h1>Basic Information</h1>
            <div class="p-border">
                <p class="basic-desc">Full Name</p>
                <div class="basic-div">
                    <input class="basic-input" type=text name=name>
                </div>    
                <p class="edit-desc">Email</p>
                <div class="basic-div">
                    <input class="basic-input" type=text name=email>
                </div>
                <p class="edit-desc">Profile Image</p>
                <div class="basic-div">
                    <input type="file" name="pic_to_upload" accept="image/gif, image/jpg, image/png">
                </div>
                <p class="edit-desc">Address</p>
                <div class="basic-div">
                    <input class="basic-input" type=text name=addr>
                </div>
                <p class="edit-desc">City</p>
                <div class="basic-div">
                    <input class="basic-input" type=text name=city>
                </div>
                <p class="edit-desc">State</p>
                <div class="basic-div">
                    <input class="basic-input" type=text name=state>
                </div>
                <p class="edit-desc zip">Zip Code</p>
                <div class="basic-div zip">
                    <input class="basic-input" type=text name=zip_code>
                </div>
            </div>
            </div>

            <div class="line"></div>

            <div class="sub-info">
            <h1>Subscription Infomation</h1>
            <div class="p-border">
                <p class="sub-desc sub">Subscription Type</p>
                <p class="sub-content sub"><?php echo $subrow["type"]; ?></p>
                <p class="sub-desc date">Start Date</p>
                <p class="sub-content date"><?php echo date("m/d/Y", strtotime($date)); ?></p>
                <p class="sub-desc time">Next Payment</p>
                <p class="sub-content time">
                <?php 
                    if(date("d") >= date( "d", strtotime($date))) {
                    echo date("m", strtotime("+1 month")) . "/" . date( "d", strtotime($date)) . "/" . date("Y");
                    } 
                    ?></p>
                <p class="sub-desc pay">Payment</p>
                <p class="sub-content pay">$<?php echo $subrow["cost"]; ?></p>
            </div>
            <input class="btn-left" type="submit" value="Confirm">
            </div>
        </form>
        </div>
    </div>
    <br>
    <br>
</body>
</html>