<?php 
require_once "config.php";

$email = $_SESSION['email'];

/* UNCOMMENT FOR LOGIN */
if(empty($email)){
    header("location: signin.php");
    exit;
}

// Get user's profile picture
$result = mysqli_query($link, "SELECT * FROM prof WHERE email = '$email'");
$row = mysqli_fetch_assoc($result);
$pic=$row['picture'];
$pid = $row["pid"];
$result = mysqli_query($link, "SELECT * FROM subscription WHERE pid = '$pid'");
$subrow = mysqli_fetch_assoc($result);
$date = $subrow["start_date"];

$result = mysqli_query($link, "SELECT * FROM owner WHERE pid = '$pid'");
$ownerrow = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["search"] = $_POST["search"];
    header("location: search.php");
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Chill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
    <link href="https://photos-3.dropbox.com/t/2/AAAe92EgbXgeonk-d36KcegkK_uiCpftIRR-QH8Gno83Uw/12/312984599/png/32x32/1/_/1/2/favicon.png/EOq-pswEGJDOCiACKAI/WtlLIt8vWQXV1rwCYWxmCBEtFF87kuX-cFklLNMCp2k?preserve_transparency=1&size=2048x1536&size_mode=3" rel="icon" type="image/png" />
  </head>
  <body class="prof">
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
                <li class="nav-item">
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
                <?php echo $row["name"]; ?>
              </div>
              <div class="profile-usertitle-job">
                Owner
              </div>
            </div>
            <!-- END MENU -->
          </div>
        </div>
        <div class="col-md-9 prof-info">
          <div class= "basic-info">
            <h1>Basic Information</h1>
            <div class="p-border">
              <p class="basic-desc">Full Name</p>
              <p class="basic-content"><?php echo $row["name"]; ?></p>
              <p class="basic-desc">Email</p>
              <p class="basic-content"><?php echo $row["email"]; ?></p>
              <p class="basic-desc pic">Profile Image</p>
              <p class="basic-content pic"><?php echo $row["picture"]; ?></p>
              <p class="basic-desc ad">Address</p>
              <p class="basic-content ad"><?php echo $ownerrow["addr"]; ?></p>
              <p class="basic-desc city">City</p>
              <p class="basic-content city"><?php echo $ownerrow["city"]; ?></p>
              <p class="basic-desc state">State</p>
              <p class="basic-content state"><?php echo $ownerrow["state"]; ?></p>
              <p class="basic-desc zip">Zip Code</p>
              <p class="basic-content zip"><?php echo $ownerrow["zip_code"]; ?></p>
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
                  else {
                    echo date("m") . "/" . date( "d", strtotime($date)) . "/" . date("Y");
                  }
                  ?></p>
              <p class="sub-desc pay">Payment</p>
              <p class="sub-content pay">$<?php echo $subrow["cost"]; ?></p>
            </div>
            <input class="btn-left" type="button" value="Edit Information" onclick="location.href = 'edit.php';">
            <input class="btn-right" type="button" value="Delete Account" onclick="location.href = 'delete.php';">
          </div>
        </div>
      </div>
    </div>
    <br>
    <br>
  </body>
</html>