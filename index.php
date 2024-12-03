<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dynamic Image Selection and Removal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .error {
      color: red;
      font-size: 14px;
      display: none;
      margin-top: 5px;
    }

    .file-input-container {
      margin-bottom: 15px;
    }

    .btn-success {
      display: block;
      margin-top: 15px;
    }

    .mb-10 {
      margin-bottom: 10px !important;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <h2 class="text-center">Dynamic Image Selection and Removal</h2>
    <?php

      if (isset($_SESSION['success_count']) && $_SESSION['success_count'] > 0) {
                  echo "<div class='alert alert-success'>" .$_SESSION['success_count']." File(s) uploaded successfully.</div>";
      }
      if (isset($_SESSION['error_count']) && $_SESSION['error_count'] > 0) {
        foreach($_SESSION['errors'] as $msg) {
          echo "<div class='alert alert-danger'>" .$msg['message']."</div>";
        }
      }
    ?>
  </div>

  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header text-center bg-primary text-white">
        <h3>Upload Your Images</h3>
      </div>
      <div class="card-body">
        <p class="text-muted text-center">
          Select multiple images to upload. Supported formats: <strong>JPG, PNG, GIF</strong>.
        </p>
        <button type="button" id="add-more" class="btn btn-outline-success mb-3">Add More</button>
        <form action="upload.php" method="post" id="upload-form" enctype="multipart/form-data">
          <div class="input-container" id="input-container">
            <div class="row mb-10">
              <div class="col">
                <input type="file" class="form-control image-input" name="images[]" accept="image/*">
                <span class="error" id="error-1">Please select a file.</span>
              </div>
              <div class="col">
                <button type="button" class="btn btn-danger remove-field">Remove</button>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-success mt-30">Upload and Process</button>
        </form>
      </div>
      <div class="card-footer">
        <small class="text-muted text-center d-block">
          Maximum file size: 5MB per image.
        </small>
      </div>
    </div>

    <?php
if (isset($_SESSION['image_paths'])) {
?>
    <div class="card shadow">
      <div class="card-header text-center bg-warning text-white">
        <h3>Uploaded Images</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <?php
          
            $uploadedImages = $_SESSION['image_paths'];
            foreach ($uploadedImages as $image) {
        ?>

          <div class="col-md-4">
            <div class="thumbnail">
              <a href="<?php echo $image; ?>" target="_blank">
                <img src="<?php echo $image; ?>" alt="Lights" style="width:100%">
              </a>
            </div>
          </div>

          <?php
            }
          
        ?>
        </div>
      </div>

    </div>
    <?php
  }
    ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/scripts.js"></script>
</body>

</html>