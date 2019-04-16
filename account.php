<?php

    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"]!==true){
      header("location: login.php");
      exit;
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
    <body id="body"class ="light">
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
                      <a class="nav-link" href="gallery.php">Gallery</a>
                   </li>
                   <li class="nav-item active">
                      <a class="nav-link" href="#">Account</a>
                   </li>
                </ul>
             </div>
          </nav>
       </header>
       <div class ="container" style="text-align:center">
         <h1>Welcome to Kulay, <b> <?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>

       <p>
         <a href="logout.php" class="btn btn-danger"> Click here to logout</a>
       </p>
       </div>
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
           console.log(document.cookie)
           console.log(body.className)
         }
         (function() {
           persistentTheme()
         })();
       </script>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
       <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    </body>
 </html>
