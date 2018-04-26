<?php
// Include config file
require_once 'config.php';

// If user is already logged in
if(isset($_SESSION['email']) && !empty($_SESSION['email'])){
    header("location: index.php");
    exit;
}

// Define variables and initialize with empty values
$email       = $password = $confirm_password = $type = "";
$email_err   = $password_err = $confirm_password_err = $name_err = "";
$holder_name = $addr = $city = $state = $month = $year = $card_no = $sec_code = $zip_code = "";
$name        = $password = $msg = $picture = "";
$type_err = $holder_err = $addr_err = $city_err = $state_err = $card_err = $month_err = $year_err = $sec_err = $zip_err = "";
$valid = true;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $result = mysqli_query($link, "SELECT pid FROM prof ORDER BY pid DESC LIMIT 1");
    $row    = mysqli_fetch_assoc($result);
    $pid    = $row["pid"] + 1;
    
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        // Prepare a select statement
        $sql = "SELECT pid FROM prof WHERE email = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST['password']);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = 'Please confirm password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_password_err = 'Password did not match.';
        }
    }
    
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = 'Please enter a name.';
    } else {
        $name = trim($_POST['name']);
    }
    
    // Picture upload
    $picture = $_FILES['pic_to_upload']['name'];

    echo "Picture: " . $picture;

    if (!$picture) {
        $picture = "empty_profile.gif";
    }

    // Check input errors before inserting in database
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        
        // Prepare an insert statement
        $sql = "INSERT INTO prof (name, email, picture, password) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $name, $param_email, $picture, $param_password);
            
            // Set parameters
            $param_email    = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // image file directory
            $target = "images/Flixnet/prof_pics/" . basename($picture);
            
            if (move_uploaded_file($_FILES['pic_to_upload']['tmp_name'], $target)) {
                $msg = "Image uploaded successfully";
            } else {
                $msg = "Failed to upload image";
            }
            
            // Attempt to execute the prepared statement
            if (!mysqli_stmt_execute($stmt)) {
                echo "Something went wrong. Please try again later. (Prof)";
            }
        }
    }
    
    if(!empty($_POST['radio'])) {
        $type=$_POST['radio'];
    } else {
        $type_err = 'Please choose a subscription.';
        $valid = false;
    }

    // Create subscription
    $sql = "INSERT INTO subscription (cost, type, length, pid) VALUES (?, ?, ?, ?)";
    
    if($valid) {
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            if ($type == "Basic") {
                $cost = 7.99;
            } elseif ($type == "Standard") {
                $cost = 10.99;
            } else {
                $cost = 13.99;
            }
            
            $length = 1;
            
            mysqli_stmt_bind_param($stmt, "dsii", $cost, $type, $length, $pid);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                
            } else {
                echo "Something went wrong. Please try again later. (Subscription)";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate holder_name
    if (empty(trim($_POST['holder_name']))) {
        $holder_err = 'Please enter a name.';
        $valid = false;
    } else {
        $holder_name = trim($_POST['holder_name']);
    }

    // Validate address
    if (empty(trim($_POST['addr']))) {
        $addr_err = 'Please enter an address.';
        $valid = false;
    } else {
        $addr = trim($_POST['addr']);
    }

    // Validate city
    if (empty(trim($_POST['city']))) {
        $city_err = 'Please enter a city.';
        $valid = false;
    } else {
        $city = trim($_POST['city']);
    }

    // Validate state
    if (empty(trim($_POST['state']))) {
        $state_err = 'Please enter a state.';
        $valid = false;
    } else {
        if ($city_err) {
            $state_err = "hi";
        }
        $state = trim($_POST['state']);
    }

    if(($state_err !== "hi" && $state_err) && !$city_err) {
        $city_err = "hi";
    }

    // Validate confirm password
    if (empty(trim($_POST['card_no'])) || strlen(trim($_POST['card_no'])) !== 16) {
        $card_err = 'Please enter valid card number.';
        $valid = false;
    } else {
        $card_no = trim($_POST['card_no']);
    }

    // Validate month
    if (isset($_POST['month'])) {
        $month = trim($_POST['month']);
    } else {
        $month_err = 'Please enter a month.';
        $valid = false;
    }

    // Validate month
    if (isset($_POST['year'])) {
        $year = trim($_POST['year']);
        if($month_err) {
            $year_err = "hi";
        }
    } else {
        $year_err = 'Please enter a year.';
        $valid = false;
    }

    if(($year_err !== "hi" && $year_err) && !$month_err) {
        $month_err = "hi";
    }

    // Validate security code
    if (empty(trim($_POST['sec_code'])) || strlen(trim($_POST['sec_code'])) !== 3) {
        $sec_err = 'Please enter a valid CVV.';
        $valid = false;
    } else {
        $sec_code = trim($_POST['sec_code']);
    }

    // Validate zipcode
    if (empty(trim($_POST['zip_code'])) || strlen(trim($_POST['zip_code'])) !== 5) {
        $zip_err = 'Please enter a valid zip code.';
        $valid = false;
    } else {
        if ($sec_err) {
            $zip_err = "hi";
        }
        $zip_code = trim($_POST['zip_code']);
    }

    if(($zip_err !== "hi" && $zip_err) && !$sec_err) {
        $sec_err = "hi";
    }
    
    $sql = "INSERT INTO owner (pid, holder_name, addr, city, state, card_no, month, year, sec_code, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($valid) {
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "issssissii", $pid, $holder_name, $addr, $city, $state, $card_no, $month, $year, $sec_code, $zip_code);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                $_SESSION['email'] = $email;
                header("location: /");
            } else {
                echo "Something went wrong. Please try again later. (Owner)";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
    <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"
    />
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.prof_pic_upload')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100);
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>

<body class="register">
    <img class="logo" src="images/Flixnet/flixnet_logo.png" alt="Flixnet Logo">
    <div class="sign-up">
        <h1>Sign Up</h1>
        <form class="info" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="input email">
                <p>Email</br></p>
                <input type="text" name="email">
                <span class="help2"></br><?php echo $email_err; ?></span>
            </div>
            <?php if($email_err) : ?>
                <style>
                    .email input[type=text] {
                        border: 1px solid #B00500;
                    }
                </style>
            <?php endif; ?>
            <div class="input pass">
                <p></br>Password</br></p>
                <input type="password" name="password"></br>
                <span class="help"><?php echo $password_err; ?></span>
            </div>
            <?php if($password_err) : ?>
                <style>
                    .pass input[type=password] {
                        border: 1px solid #B00500;
                    }
                </style>
            <?php endif; ?>
            <div class="input confirm">
                <p>Confirm Password</br></p>
                <input type="password" name="confirm_password"></br>
                <span class="help"><?php echo $confirm_password_err; ?></span>
            </div>
            <?php if($confirm_password_err) : ?>
                <style>
                    .confirm input[type=password] {
                        border: 1px solid #B00500;
                    }
                </style>
            <?php endif; ?>
            <div class="input name">
                <p>Name</br></p>
                <input type="text" name="name">
                <span class="help2"></br><?php echo $name_err; ?></span>
            </div>
            <?php if($name_err) : ?>
                <style>
                    .name input[type=text] {
                        border: 1px solid #B00500;
                    }
                </style>
            <?php endif; ?>
            <div class="upload_prof_pic">
                <p>Profile Picture</p>
                <input type="file" onchange="readURL(this);" name="pic_to_upload" accept="image/gif, image/jpg, image/png">
                <img class="prof_pic_upload" src="images/Flixnet/prof_pics/empty_profile.gif" alt="prof_pic">
            </div>
            <div class="plans">
                <p>Subscription Plan (1 year)</p>
                <input type="radio" name="radio" value="Basic"><label class="radio-label">Basic - $7.99</br></label>
                <input type="radio" name="radio" value="Standard"><label class="radio-label">Standard - $10.99</br></label>
                <input type="radio" name="radio" value="Premium"><label class="radio-label">Premium - $13.99</label>
                <span class="help"></br><?php echo $type_err; ?></span>
            </div>
            <div class="cc_info input">
                <div class="input holder">
                    <p>Name on card</p>
                    <input type="text" name="holder_name">
                    <span class="help2"></br><?php echo $holder_err; ?></span>
                </div>
                <?php if($holder_err) : ?>
                <style>
                    .holder input[type=text] {
                        border: 1px solid #B00500;
                    }
                </style>
                <?php endif; ?>
                <div class="input addr">
                    <p>Billing Address</p>
                    <input type="text" name="addr">
                    <span class="help2"></br><?php echo $addr_err; ?></span>
                </div>
                <?php if($addr_err) : ?>
                <style>
                    .addr input[type=text] {
                        border: 1px solid #B00500;
                    }
                </style>
                <?php endif; ?>
                <div class="not-select sbs">
                    <div class="input city">
                        <p>City</p>
                        <input type="text" name="city">
                        <span class="help2 help-city"></br><?php echo $city_err; ?></span>
                    </div>
                    <?php if($city_err && $city_err !== "hi") : ?>
                    <style>
                        .city input[type=text] {
                            border: 1px solid #B00500;
                        }
                    </style>
                    <?php elseif($state_err) : ?>
                    <style>
                        .help-city {
                            color: #F3F3F3;
                        }
                    </style>
                    <?php endif; ?>
                </div>
                <div class="not-select sbs">
                    <div class="input state">
                        <p>State</p>
                        <input type="text" name="state">
                        <span class="help2 help-state"></br><?php echo $state_err; ?></span>
                    </div>
                    <?php if($state_err && $state_err !== "hi") : ?>
                    <style>
                        .state input[type=text] {
                            border: 1px solid #B00500;
                        }
                    </style>
                    <?php elseif($city_err) : ?>
                    <style>
                        .help-state {
                            color: #F3F3F3;
                        }
                    </style>
                    <?php endif; ?>
                </div>
                <div class="input card">
                    <p>Card Number</p>
                    <input type="text" name="card_no">
                    <span class="help2"></br><?php echo $card_err; ?></span>
                </div>
                <?php if($card_err) : ?>
                <style>
                    .card input[type=text] {
                        border: 1px solid #B00500;
                    }
                </style>
                <?php endif; ?>
                <div class="exp-date sbs month">
                    <p>Expiration Date</p>
                    <select name="month">
                    <?php 
                        print '<option value="" selected disabled hidden>MM</option>';
                        foreach (range(1,12) as $x) {
                            $x = str_pad($x, 2, "0", STR_PAD_LEFT);
                            print '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
                        }
                    ?>
                    </select>
                    <span class="help2 help-month"></br><?php echo $month_err; ?></span>
                </div>
                <?php if($month_err && $month_err !== "hi") : ?>
                    <style>
                        .month select {
                            border: 1px solid #B00500;
                        }
                    </style>
                <?php elseif($year_err) : ?>
                    <style>
                        .help-month {
                            color: #F3F3F3;
                        }
                    </style>
                <?php endif; ?>
                <div class="exp-date sbs year">
                    <select name="year">
                    <?php 
                        print '<option value="" selected disabled hidden>YYYY</option>';
                        foreach (range(2018, 2023) as $x) {
                            print '<option value="'.$x.'"'.($x === $already_selected_value ? ' selected="selected"' : '').'>'.$x.'</option>';
                        }
                    ?>
                    </select>
                    <span class="help2 help-year"><?php echo $year_err; ?></span>
                </div>
                <?php if($year_err && $year_err !== "hi") : ?>
                    <style>
                        .year select {
                            border: 1px solid #B00500;
                            margin-bottom: -11px;
                        }
                    </style>
                <?php elseif($month_err) : ?>
                    <style>
                        .help-year {
                            color: #F3F3F3;
                        }
                    </style>
                <?php endif; ?>
                <div class="not-select sbs">
                    <div class="input sec">
                        <p>Security Code</p>
                        <input type="text" name="sec_code">
                        <span class="help2 help-sec"></br><?php echo $sec_err; ?></span>
                    </div>
                    <?php if($sec_err && $sec_err !== "hi") : ?>
                    <style>
                        .sec input[type=text] {
                            border: 1px solid #B00500;
                        }
                    </style>
                    <?php elseif($zip_err) : ?>
                    <style>
                        .help-sec {
                            color: #F3F3F3;
                        }
                    </style>
                    <?php endif; ?>
                </div>
                <div class="not-select sbs">
                    <div class="input zip">
                        <p>Zip Code</p>
                        <input type="text" name="zip_code">
                        <span class="help2 help-zip"></br><?php echo $zip_err; ?></span>
                    </div>
                    <?php if($zip_err && $zip_err !== "hi") : ?>
                    <style>
                        .zip input[type=text] {
                            border: 1px solid #B00500;
                        }
                    </style>
                    <?php elseif($sec_err) : ?>
                    <style>
                        .help-zip {
                            color: #F3F3F3;
                        }
                    </style>
                    <?php endif; ?>
                </div>
            </div>
            </br></br>
            <input class="sign-up-button" type="submit" value="Sign Up">
        </form>
        </div>
</body>

</html>