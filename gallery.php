<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="author" content = "Matt (mjc5gh) and Hill (ehd3kh)">
      <meta name="description" content = "paint by numbers">
      <title>Kulay</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
      <link rel="stylesheet" href="styles/gallery.css">
      <link rel="stylesheet" href="styles/main.css">
   </head>
   <body id="body"class="light">
      <!-- body is default the light class, matching up with the other pages but a night mode is not implemented on the gallery page yet-->
      <header>
         <nav class="navbar navbar-expand-lg  navbar-dark" id="barboy">
            <a class="navbar-brand" href="index.html">
            <span style='color: red'>K</span>
            <span style='color: orange'>U</span>
            <span style='color: yellow'>L</span>
            <span style='color: limegreen'>A</span>
            <span style='color: deepskyblue'>Y</span>
            </a>
            <!--Collapsible navbar used to condense the navbar when using smaller screen sizes-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
               <ul class="navbar-nav">
                  <li class="nav-item">
                     <a class="nav-link" href="index.html">Upload</a>
                  </li>
                  <li class="nav-item active">
                     <a class="nav-link" href="#">Gallery</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="account.php">Account</a>
                  </li>
               </ul>
            </div>
         </nav>
      </header>
      <!--Grid to show the gallery of user images, if you click on an image it opens a modal window that shows the image
         with it's true size and aspect ratio.-->
      <div class="grid">
         <?php
         session_start();
            require_once "config.php";
            $id = $_SESSION["id"];

            $query = $mysqli->query("SELECT * FROM images2 WHERE userid = $id");

            if($query->num_rows >0){
              while($row = $query->fetch_assoc()){
                $imageURL = 'modified/'.$row['image'];
                ?>

                <img src="<?php echo $imageURL; ?>"/>
                <?php
              }
            }
          ?>
      </div>
      <!-- Modal class div, opens when imaages are clicked on-->
      <div id="mod" class="modal">
         <span class="close">&times;</span>
         <img class="modal-content" id="img01">
         <div id="caption"></div>
      </div>
      <!-- function that expands the image into a modal window focusing on that image-->
      <script type="text/javascript">
         function modalExpand(imageID){
           var modal = document.getElementById('mod');
           var img = document.getElementById(imageID);
           var modalImg = document.getElementById("img01");
           img.onclick = function(){
             modal.style.display = "block";
             modalImg.src = this.src;
           }

           var span = document.getElementsByClassName("close")[0];
           span.onclick = function() {
             modal.style.display = "none";
           }
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
