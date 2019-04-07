<?php

  session_start();

  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]===true){
    header("location: index.html");
    exit;
  }

  require_once "config.php";

  $username = $password = $username_err = $password_err = "";

  if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
      $username_err = "Please enter username";
    }
    else{
      $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
      $password_err = "Please enter your password";
    }
    else{
      $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){

      $sql = "SELECT id, username, password FROM users WHERE username = ?";

      if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s", $param_username);
        $param_username = $username;
        if($stmt->execute()){
          $stmt->store_result();

          if($stmt->num_rows==1){
            $stmt->bind_result($id,$username,$password2);
            if($stmt->fetch()){
              if($password==$password2){
                session_start();

                $_SESSION["loggedin"]=true;
                $_SESSION["id"]= $id;
                $_SESSION["username"]=$username;

                header("location: index.html");
              }
              else{
                $password_err = "Incorrect password";
              }
            }
          }

        else{
          $username_err = "No account with that username";
        }
      }
      else{
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
    <body class ="light">
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
                      <a class="nav-link" href="#">Account</a>
                   </li>
                </ul>
             </div>
          </nav>
       </header>
       <!--Form for login-->
       <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class= "container" style="text-align:center">
             <label for="username"><b>Username</b></label>
             <br/>
             <input type="text" placeholder="Enter Username" value="<?php echo $username; ?>" name="username" autofocus required/>
             <span id="usernameError" class="error"><?php echo $username_err; ?></span>
             <br/>
             <label for="password"><b>Password</b></label>
             <br/>
             <input type="password" placeholder="Enter Password" name="password" required/>
             <span id="passwordError" class="error"><?php echo $password_err; ?></span>
             <br/>
             <input type="submit" value = "Login" style = "margin-top: 20px"/>
             <p>Don't have an account? <a href="register.php">Click here to register an account</a>.</p>
          </div>
       </form>
       <script type="text/javascript">
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
       </script>
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
