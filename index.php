<!DOCTYPE html>
<html>
  <head>
    <title>Method ID Generator</title>
    <style>
        /* Base button styles */
       
          #myhash {
            height: 50px;
            width: 350px;
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s ease-in-out;
            float: right; /* Align the button to the right side */
            margin-right: 150px;
            
        }

        /* Button styles on hover */
        #myhash:hover {
            background-color: black;
            color: white;
            transform: scale(1.1); 
        }
    </style>
    <link rel="stylesheet" href="methodid.css">
  </head>
  <body>
    <h1>Method ID Generator</h1>
    <form action="getmethodID.php" method="post" enctype="multipart/form-data">
      <label for="file">Upload Excel File:</label>
      <div>
      <input type="file" name="file" id="file">
      <button id="cancel" type="button">&#10006;</button>
      </div>
      <br><br>
        <label for="sourceKey">Source Key:</label>
        <input type="text" name="sourceKey" id="sourceKey">
     <br><br>
     <label for="pin">PIN:</label>
      <input type="password" name="pin" id="pin" style="height: 30px;"> 
      <br><br>
      <input type="submit" value="Submit">
    </form>
  

     <a href="tokens.php">
    <button id="link">Click here to Tokenize Payment Method</button>
    </a>

    <a href="hash.php">
    <button id="myhash" >Generate Hash  to continue</button>
</a>


    <div id="popup" class="popup">
    <div class="popup-content">
      <div class="popup-header">
        <span class="close">&times;</span>
        <h2>Are you sure?</h2>
      </div>
      <div class="popup-body">
        <button id="yes-btn" class="yes-btn">Yes</button>
        <button id="no-btn" class="no-btn">No</button>
      </div>
    </div>
  </div>

  
    <script src="methodid.js"></script>
  </body>

</html>
