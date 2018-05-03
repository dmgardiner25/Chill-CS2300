<?php
// Include config file
require_once 'config.php';

// If user is already logged in
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    header("location: index.php");
    exit;
}

// Define variables and initialize with empty values
$email     = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if username is empty
    if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email.";
    } else {
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }
    
    if ($password_err || $email_err) {
        $login_err = '';
    }
    
    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT email, password FROM prof WHERE email = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $email, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['email'] = $email;
                            header("location: index.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $login_err = 'Sorry, we can\'t find an account with this email address. Please try again or <a href="signup.php">create a new account</a>.';
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Use title if it's in the page YAML frontmatter -->
    <title>Chill</title>
    <meta name="description" content="XAMPP is an easy to install Apache distribution containing MariaDB, PHP and Perl." />
    <meta name="keywords" content="xampp, apache, php, perl, mariadb, open source distribution" />
    <meta http-equiv="Cache-control" content="no-cache">
    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
    <link href="https://photos-3.dropbox.com/t/2/AAAe92EgbXgeonk-d36KcegkK_uiCpftIRR-QH8Gno83Uw/12/312984599/png/32x32/1/_/1/2/favicon.png/EOq-pswEGJDOCiACKAI/WtlLIt8vWQXV1rwCYWxmCBEtFF87kuX-cFklLNMCp2k?preserve_transparency=1&size=2048x1536&size_mode=3" rel="icon" type="image/png" />
</head>

<body class="log-in">
    <img class="logo" src="images/Flixnet/chill_logo.png" alt="Chill Logo">
    <div class="login">
        <h1>Sign In</h1>
        <form class="info" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="login-wrapper">
                <div class="login-help">
                    <?php echo $login_err; ?>
                </div>
            </div>
            <?php if($login_err) : ?>
            <style>
                .login-wrapper {
                    width: 90%;
                    margin-top: 30px;
                    background: #999999;
                    border-radius: 2px;
                    justify-content: center;
                    }

                .login-help {
                    padding-top: 17px;
                    padding-bottom: 17px;
                    width: 93%;
                    margin: auto;
                    position: relative;
                    background: transparent;
                    font-size: 16px;
                    color: white;
                    }

                .login-help a {
                    text-decoration: underline;
                    background: transparent;
                    color: white;
                    width: 90%;
                }
            </style>
            <?php endif; ?>
            <div class="input email">
                <p>Email</br></p>
                <input type="text" name="email" value="<?php echo $email; ?>">
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
                <input type="password" name="password">
                <span class="help"></br><?php echo $password_err; ?></span>
            </div>
            <?php if($password_err) : ?>
            <style>
                .pass input[type=password] {
                    border: 1px solid #B00500;
                }
            </style>
            <?php endif; ?>
            </br></br>
            <p class="extra_link">Forgot your email or password?</p>
            <!--<input type="submit" value="Sign In" formaction="submit.php">-->
            <input type="submit" value="Sign In"></br></br><hr>
            <p>New to Netflix? <a href="signup.php">Sign up now.</a></p></br>
        </form>
    </div>

</body>

</html>