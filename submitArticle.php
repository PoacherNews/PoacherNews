<?php
  //header("Location: ../editorHistory.php");
  session_start();
  $action = empty($_POST['action']) ? '' : $_POST['action'];
  $submit = empty($_POST['submit']) ? '' : $_POST['submit'];
  $save = empty($_POST['save']) ? '' : $_POST['save'];
  /*
  if(empty($_POST['title']) || empty($_POST['category']) || file_exists(!($_FILES['image']['temp_name']))){
    echo "error";
    require 'editorpage.php';
    exit;
  } else {
      print "success!";
  }
  */
    include 'util/db.php';
    include('util/articleUtils.php');
    include('util/userUtils.php');

    $articleData = getArticleByID($_GET['articleid'], $db);
    print $articleData['ArticleID'];

  function dbConnect() {
    include 'util/db.php';
    include('util/articleUtils.php');
    include('util/userUtils.php');
    // Check connection
    if ($db->connect_error)
    {
	   die("Connection failed: " . $db->connect_error);
    }
    else{
        return $db;
    }
  } // End of dbConnect

  
  if(isset($action)){
      // Get articleid
      if($submit == "Yes") { // If the user wants to submit
          submitArticle();
          // relocate
      } else if($save == "Save") { // If the user wants to save
          saveArticle();
          // relocate
      }
  } else {
    relocate(); //Go back to editorHistory.php
  }

  

  function submitArticle() {
      $title = empty($_POST['title']) ? '' : $_POST['title'];
      $category = empty($_POST['category']) ? '' : $_POST['category'];
      //Will take either file upload or contents from div
      $body = empty($_POST['body']) ? '' : $_POST['body']; //TODO
	  // $body = readTextFile();
      $authorid = getAuthorID();
      $is_draft = 1; // true
      $is_submitted = 1; // true
	  $views = 0; // true
      $db = dbConnect();
      $stmt = $db->stmt_init();
      
      if (!$stmt->prepare("INSERT INTO Article(UserID, Headline, Body, Category, Views, IsDraft, IsSubmitted) VALUES(?, ?, ?, ?, ?, ?, ?)")) {
          echo "Error preparing statement: \n";
          print_r($stmt->error_list);
          exit;
      }

      if (!$stmt->bind_param('isssiii', $authorid, $title, $body, $category, $views, $is_draft, $is_submitted)) {
          echo "Error binding parameters: \n";
          print_r($stmt->error_list);
          exit;
      }

      if(!$stmt->execute()){
            echo "Error Inserting: \n";
            echo nl2br(print_r($stmt->error_list, true), false);
            exit;
      }
      
      echo "Article: " . $title . " submitted successfully.";
  } //End of submitArticle

  function saveArticle() { //TODO: $views = 0
      $title = empty($_POST['title']) ? '' : $_POST['title'];
      $category = empty($_POST['category']) ? '' : $_POST['category'];
      $body = empty($_POST['body']) ? '' : $_POST['body'];
      $authorid = getAuthorID();
      //$filepath = uploadImage();
      // $body = readTextFile();
      $is_draft = 1; // true
      $is_submitted = 0; // false
      $db = dbConnect();
      $stmt = $db->stmt_init();
      
      // Check if the draft has an articleid or is a new submission
      
      if (!$stmt->prepare("INSERT INTO Article(UserID, Headline, Body, Category, IsDraft, IsSubmitted) VALUES(?, ?, ?, ?, ?, ?)")) {
          echo "Error preparing statement: \n";
          print_r($stmt->error_list);
          exit;
      }
      if (!$stmt->bind_param('isssii', $authorid, $title, $body, $category, $is_draft, $is_submitted)) {
          echo "Error binding parameters: \n";
          print_r($stmt->error_list);
          exit;
      }
      if(!$stmt->execute()){
            echo "Error Inserting: \n";
            echo nl2br(print_r($stmt->error_list, true), false);
            exit;
      }
      echo "Article: " . $title . " saved successfully.";
  } //End of saveArticle

  function readTextFile() {
    if (isset( $_FILES['document'])) {
        // Upload .docx, .doc, .txt
        $app_docx = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
        $app_doc = "application/msword";
        $text_plain = "text/plain";
        switch($_FILES['document']['type']) {
			case $app_docx: //TODO: Single/Double quotes needed to be changed; Parsing links
                $source_file = $_FILES['document']['tmp_name'];
				//Parse word docx by opening up zip file then display it
				$zip = new ZipArchive;
				$dataFile = 'word/document.xml';
				// Open the archive file
				if (true === $zip->open($source_file)) {
					// If true, search for the data file in archive
					if (($index = $zip->locateName($dataFile)) !== false) {
						$data = $zip->getFromIndex($index); // getFromIndex leaks memory.. beware especially if in a long loop!
						$zip->close(); // Closing zip file doesnt help but it will do
						$dom = new DOMDocument;
						$dom->loadXML($data, LIBXML_NOENT
							| LIBXML_XINCLUDE
							| LIBXML_NOERROR
							| LIBXML_NOWARNING);
						$xmldata = $dom->saveXML();
						// Strip the p, u, i, and b tags
						$contents = strip_tags($xmldata, '<w:p><w:u><w:i><w:b>');
						// REG EX to insert proper tags for u, i, b
						$contents = preg_replace("/(<(\/?)w:(.)[^>]*>)\1*/", "<$2$3>", $contents);

						$dom = new DOMDocument;
						@$dom->loadHTML($contents, LIBXML_HTML_NOIMPLIED  | LIBXML_HTML_NODEFDTD);
						$contents = $dom->saveHTML();
						// REG EX to clean and even up the tags
						$contents = preg_replace('~<([ibu])>(?=(?:\s*<[ibu]>\s*)*?<\1>)|</([ibu])>(?=(?:\s*</[ibu]>\s*)*?</?\2>)|<p></p>~s', "", $contents);

						return $contents;
					}
					$zip->close();
				}
				// If fails return null;
				return null;
                break;
                
            case $app_doc:
				$source_file = $_FILES['document']['tmp_name'];
				if(file_exists($source_file)) {
					if(($fh = fopen($source_file, 'r')) !== false ) {
						$headers = fread($fh, 0xA00);
						$n1 = ( ord($headers[0x21C]) - 1 );// 1 = (ord(n)*1) ; Document has from 0 to 255 characters
						$n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );// 1 = ((ord(n)-8)*256) ; Document has from 256 to 63743 characters
						$n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );// 1 = ((ord(n)*256)*256) ; Document has from 63744 to 16775423 characters
						$n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );// 1 = (((ord(n)*256)*256)*256) ; Document has from 16775424 to 4294965504 characters
						$textLength = ($n1 + $n2 + $n3 + $n4);// Total length of text in the document
						$extracted_plaintext = fread($fh, $textLength);
						
						$extracted_plaintext = (strip_tags($extracted_plaintext,’‘));
						//Including all of the characters
						$map = array(
									chr(0x8A) => chr(0xA9), chr(0x8C) => chr(0xA6),
									chr(0x8D) => chr(0xAB), chr(0x8E) => chr(0xAE),
									chr(0x8F) => chr(0xAC), chr(0x9C) => chr(0xB6),
									chr(0x9D) => chr(0xBB), chr(0xA1) => chr(0xB7),
									chr(0xA5) => chr(0xA1), chr(0xBC) => chr(0xA5),
									chr(0x9F) => chr(0xBC), chr(0xB9) => chr(0xB1),
									chr(0x9A) => chr(0xB9), chr(0xBE) => chr(0xB5),
									chr(0x9E) => chr(0xBE), chr(0x80) => '&euro;',
									chr(0x82) => '&sbquo;', chr(0x84) => '&bdquo;',
									chr(0x85) => '&hellip;', chr(0x86) => '&dagger;',
									chr(0x87) => '&Dagger;', chr(0x89) => '&permil;',
									chr(0x8B) => '&lsaquo;', chr(0x91) => '&lsquo;',
									chr(0x92) => '&rsquo;', chr(0x93) => '&ldquo;',
									chr(0x94) => '&rdquo;', chr(0x95) => '&bull;',
									chr(0x96) => '&ndash;', chr(0x97) => '&mdash;',
									chr(0x99) => '&trade;', chr(0x9B) => '&rsquo;',
									chr(0xA6) => '&brvbar;', chr(0xA9) => '&copy;',
									chr(0xAB) => '&laquo;', chr(0xAE) => '&reg;',
									chr(0xB1) => '&plusmn;', chr(0xB5) => '&micro;',
									chr(0xB6) => '&para;', chr(0xB7) => '&middot;',
									chr(0xBB) => '&raquo;',
								);
    					$result = html_entity_decode(mb_convert_encoding(strtr($extracted_plaintext, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
						
						return $result;
					} else {
						return false;
					}
				} else {
					return false;
				}
				
                break;
                
            case $text_plain:
                $source_file = $_FILES['document']['tmp_name'];
				$body = file_get_contents($source_file);
                $map = array(
							chr(0x8A) => chr(0xA9), chr(0x8C) => chr(0xA6),
							chr(0x8D) => chr(0xAB), chr(0x8E) => chr(0xAE),
							chr(0x8F) => chr(0xAC), chr(0x9C) => chr(0xB6),
							chr(0x9D) => chr(0xBB), chr(0xA1) => chr(0xB7),
							chr(0xA5) => chr(0xA1), chr(0xBC) => chr(0xA5),
							chr(0x9F) => chr(0xBC), chr(0xB9) => chr(0xB1),
							chr(0x9A) => chr(0xB9), chr(0xBE) => chr(0xB5),
							chr(0x9E) => chr(0xBE), chr(0x80) => '&euro;',
							chr(0x82) => '&sbquo;', chr(0x84) => '&bdquo;',
							chr(0x85) => '&hellip;', chr(0x86) => '&dagger;',
							chr(0x87) => '&Dagger;', chr(0x89) => '&permil;',
							chr(0x8B) => '&lsaquo;', chr(0x91) => '&lsquo;',
							chr(0x92) => '&rsquo;', chr(0x93) => '&ldquo;',
							chr(0x94) => '&rdquo;', chr(0x95) => '&bull;',
							chr(0x96) => '&ndash;', chr(0x97) => '&mdash;',
							chr(0x99) => '&trade;', chr(0x9B) => '&rsquo;',
							chr(0xA6) => '&brvbar;', chr(0xA9) => '&copy;',
							chr(0xAB) => '&laquo;', chr(0xAE) => '&reg;',
							chr(0xB1) => '&plusmn;', chr(0xB5) => '&micro;',
							chr(0xB6) => '&para;', chr(0xB7) => '&middot;',
							chr(0xBB) => '&raquo;', chr(0xd4) => '&lsquo;',
							chr(0xd5) => '&rsquo;', chr(0xd2) => '&ldquo;',
							chr(0xd3) => '&rdquo;',
						);
				$result = html_entity_decode(mb_convert_encoding(strtr($body, $map), 'UTF-8', 'ISO-8859-1'), ENT_QUOTES, 'UTF-8');
				return $result;
                break;
                
            default:
                print "Error occured while uploading file: ".$_FILES['document']['name'];
                print " Invalid  file extension, should be docx, doc, or txt."."<br/>";
                break;
        }
    }
  }// End of readTextFile

  function uploadImage() {
      // Limit file size
      if ($_FILES['image']['size'] > 10000000) {
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


      $filepath = sprintf('res/img/%s.%s',
          // Encrypt file name to avoid user selected name
          sha1_file($_FILES['image']['tmp_name']),
          // Use valid file extension
          $ext
      );

      $db = dbConnect();
      $stmt = $db->stmt_init();

      if(!$stmt->prepare("SELECT Image FROM Article WHERE Image=?")){
        echo "Error preparing statement: \n";
        print_r($stmt->error_list);
        exit;
      }

      if(!$stmt->bind_param('s', $filepath)){
        echo "Error binding parameters: \n";
        print_r($stmt->error_list);
        exit;
      }

      $stmt->execute();
      // get result
      $result = $stmt->get_result();

      if($result->num_rows > 0){
        echo "File name already exists. Please choose different name";
        exit;
      }

      // Move file form temp folder
      if (!move_uploaded_file(
          $_FILES['image']['tmp_name'],
          $filepath
      )) {
          throw new RuntimeException('Failed to move uploaded file.');
      }

      return $filepath;
  } // End of uploadImage

  function getAuthorID(){
    $username = empty($_SESSION['username']) ? 'error' : $_SESSION['username'];
    $db = dbConnect();

    $stmt = $db->stmt_init();

    if(!$stmt->prepare("SELECT UserID FROM User WHERE Username=?")){
      echo "Error preparing statement: \n";
      print_r($stmt->error_list);
      exit;
    }

    if(!$stmt->bind_param('s', $username)){
      echo "Error binding parameters: \n";
      print_r($stmt->error_list);
      exit;
    }

    $stmt->execute();
    // get result
    $result = $stmt->get_result();

    if($result->num_rows != 1){
      echo "Error finding username: '$username'";
      exit;
    }

    $row = $result->fetch_assoc();
    return $row['UserID'];
  } // End of getAuthorID

  function relocate() {
        header("Location: /editorHistory.php");
        exit;
  }
?>
