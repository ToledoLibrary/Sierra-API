<?php include 'functions.php';?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Tools</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="XXXX" crossorigin="anonymous">
    <link rel="stylesheet" href="imagestyles.css">
  </head>
  <body>

    <div class="container my-5">
      <div class="col-lg-8 px-0">
        
        <p class="fs-5" style="font-weight:700;">Image Display Code Generator</p>
        <p class="fs-7">Get HTML code to be used on a blog post or website.<br><br>Enter ISBN, Encore Permanent Links, or a combination of both in the box below. Each item must be on a separate line.</p>
        <form action="codegen.php" method="post">
          <textarea name="textdata" rows="15" cols="65"></textarea><br><br>

          <span style="font-weight:700;">Image size:</span>&nbsp;&nbsp;&nbsp;
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="imagesize" id="sizeS" value="S">
            <label class="form-check-label" for="images1">S</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="imagesize" id="sizeM" value="M" checked>
            <label class="form-check-label" for="images3">M</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="imagesize" id="sizeL" value="L">
            <label class="form-check-label" for="images5">L</label>
          </div>
        <br><br>

          <span style="font-weight:700;">Number of images per row:</span>&nbsp;&nbsp;&nbsp;
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="displayimages" id="images1" value="1">
            <label class="form-check-label" for="images1">1</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="displayimages" id="images3" value="3">
            <label class="form-check-label" for="images3">3</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="displayimages" id="images5" value="5" checked>
            <label class="form-check-label" for="images5">5</label>
          </div>
        <br><br>
        <span style="font-weight:700;">Include summary:</span>&nbsp;&nbsp;&nbsp;
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="summary" id="summyes" value="Y">
            <label class="form-check-label" for="summar">Yes</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="summary" id="sumno" value="N" checked>
            <label class="form-check-label" for="summary">No</label>
          </div>
        <br><br>
          
          <input class="btn btn-secondary" type="submit" name="Generate" value="Generate Code"/>
        </form> 
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>


