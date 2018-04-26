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

    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />

    <link href="/dashboard/images/favicon.png" rel="icon" type="image/png" />


  </head>
  <body>
    <img class="logo" src="images/Flixnet/flixnet_logo.png" alt="Flixnet Logo">

    <?php
    $x=$_POST['firstname'];
    $y=$_POST['lastname'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_test";

    // Create Connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check Connection
    if($conn->connect_error) {
      die("Connection Failed: " . $conn->connect_error);
    }
    echo "Connected successfully";

    // Insert into DB
    $sql = "INSERT INTO `user` (`fname`, `lname`) VALUES ('$x', '$y')";

    if($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "</br>" . $conn->error;
    }

    $conn->close();
    ?>
  </body>

</html>
