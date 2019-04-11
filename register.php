<?php

require_once "config.php";

$username = $password = $confirm_pass = $username_err = $password_err = $confirm_pass_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $username_err = "Username taken already";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Error occured, please try again";
            }
        }

        $stmt->close();
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Please enter at least 8 characters";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_pass"]))) {
        $confirm_pass_err = "Please confirm your password";
    } else {
        $confirm_pass = trim($_POST["confirm_pass"]);
        if (empty($password_err) && ($password != $confirm_pass)) {
            $confirm_pass_err = "Passwords did not match";
        }
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_pass_err)) {

        $sql = "INSERT INTO users (username, password) VALUES (?,?)";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ss", $param_username, $param_password);

            $param_username = $username;
            $param_password = $password;

            if ($stmt->execute()) {
                header("location: login.php");
            } else {
                echo "Error occured, please try again";
            }
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="author" content = "Matt (mjc5gh) and Hill (ehd3kh)">
      <meta name="description" content = "paint by numbers">
      <title>Kulay</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
      <link rel="stylesheet" href="styles/main.css">
      <style>
         .error {font-weight: bold;color: red}
      </style>
   </head>
   <body class ="light" id="body">
      <!--General navbar-->
      <header>
         <nav class="navbar navbar-expand-lg  navbar-dark">
            <a class="navbar-brand" href="index.html">
            <span style='color: red'>K</span>
            <span style='color: orange'>U</span>
            <span style='color: yellow'>L</span>
            <span style='color: limegreen'>A</span>
            <span style='color: deepskyblue'>Y</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
               <ul class="navbar-nav">
                  <li class="nav-item">
                     <a class="nav-link" href="index.html">Upload</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="gallery.html">Gallery</a>
                  </li>
                  <li class="nav-item active">
                     <a class="nav-link" href="account.php">Account</a>
                  </li>
               </ul>
            </div>
         </nav>
      </header>
      <!--Form for login-->
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
         <div class= "container" style="text-align:center">
            <h2>Create Account</h2>
            <label for="username"><b>Username</b></label>
            <br/>
            <input type="text" name="username"  value="<?php echo $username; ?>">
            <span id="usernameError" class="error"><?php echo $username_err; ?></span>
            <br/>
            <label for="password"><b>Password</b></label>
            <br/>
            <input type="password" name="password"  value="<?php echo $password; ?>">
            <br/>
            <span id="passwordError" class="error"><?php echo $password_err; ?></span>
            <br/>
            <label for="confirm_pass"><b>Confirm Password</b></label>
            <br/>
            <input type="password" name="confirm_pass"  value="<?php echo $confirm_pass; ?>">
            <br/>
            <span id="confirmPasswordError" class="error"><?php echo $confirm_pass_err; ?></span>
            <br/>
            <input type="submit" value = "Submit" style = "margin-top: 20px"/>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
         </div>
      </form>
      <!--<script type="text/javascript">
         //JS function that uses an eventListner to alert the user if the entered username is less than 4 characters
           function usernameLength() {
             var uName = document.getElementById("usernameError")
             if(this.value.length < 4){
               uName.textContent = "Username must be at least four characters"
             }
             else{
               uName.textContent = ""
             }
           }
           var egg = document.getElementById("username");
           egg.addEventListener('blur', usernameLength, false);

           function checkLogin(){
             alert("egg")

           }
      </script>-->
      <script type="text/javascript">
        //Nightmode function for the nightmode button
        function nightMode(){
          var body = document.getElementById("body")
          var theme = body.className
          var newClass = body.className  == "dark" ? "light" : "dark"
          body.className = newClass
          var nightBut = document.getElementById("night-mode")
          nightBut.value = theme == "dark" ? "Night Mode" : "Day Mode"
          document.cookie = 'theme=' + (newClass == 'light' ? 'light-mode' : 'dark-mode')
        }
        function isDark(){
          return document.cookie.match(/theme=dark-mode/i) != null
        }
        function persistentTheme(){
          var body = document.getElementById("body")
          body.className = isDark() ? 'dark' : 'light'
        }
        //Rainbow function toggled when KULAY logo is clicked
        function rainboMode(){
          var body = document.getElementById("body")
          var theme = body.className
          body.className = theme == "rainbow" ? "light" : "rainbow"
        }
        (function() {
          persistentTheme()
        })();
      </script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
   </body>
</html>
