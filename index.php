<!DOCTYPE html>
<html>
  <head>
    <title>Method ID Generator</title>
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
      <input type="submit" value="Submit">
    </form>
     <a href="tokens.php">
    <button id="link">Click here to Tokenize Payment Method</button>
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
