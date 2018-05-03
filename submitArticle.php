<? php
  // TODO;
  // Clean up submitted strings
  // Insert articles in database
  // Add preview function for editor.php
  
  $title = empty($_POST['title']) ? '' : $_POST['title'];
  $category = empty($_POST['category']) ? '' : $_POST['category'];
  $body = empty($_POST['body']) ? '' : $_POST['body'];
  $filepath = uploadImage();

  function uploadImage() {
    // Limit file size
    if ($_FILES['image']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // Check if image is valid
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
      $finfo->file($_FILES['image']['tmp_name']),
      array(
          //valid file extensions
          'jpg' => 'image/jpeg',
          'png' => 'image/png',
          'gif' => 'image/gif',
      ),
      true
    )) {
      throw new RuntimeException('Invalid file format.');
    }

    $filepath = sprintf('/images/%s.%s',
        // Encrypt file name to avoid user selected name
        sha1_file($_FILES['image']['tmp_name']),
        // Use valid file extension
        $ext
    );
    // Move file form temp folder
    if (!move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $filepath
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }



    return $filepath;
  }
?>
