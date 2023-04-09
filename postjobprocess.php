<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Job Post processing page" />
  <meta name="keywords"    content="post, processing" />
  <meta name="author"      content="STUID, NAME" />
  <!--link href="css/style.css" rel="stylesheet"/-->
  <title>PROCESSING</title>
</head>
<body>
  <?php
  // This page checks the input data, writes the data to a text file, and generates the corresponding HTML output.
  print_r(array_filter($_POST));
  
  // Check the checkboxes are checked.
  if (isset($_POST["postal"])) {
    $applicationByPost = $_POST["postal"];
  } else {
    $applicationByPost = FALSE;
  }
  
  if (isset($_POST["email"])) {
    $applicationByEmail = $_POST["email"];
  } else {
    $applicationByEmail = FALSE;
  }
  
  if (($applicationByPost == FALSE) && ($applicationByEmail == FALSE)) {
    echo "<h1><strong>Cooked!</strong></h1>";
    echo "<p>Please ensure an application method has been selected.";
    echo "<br><a href=\"postjobform.php\">Try again</a></p>;
  } else {
    // initialise variables from form.
    $posID = $_POST["posID"];
    $title = $_POST["title"];
    $description = htmlspecialchars($_POST["description"]);
    $closeDate = $_POST["closeDate"];
    $positionType = $_POST["positionType"];
    $contractType = $_POST["contractType"];
    $location = $_POST["location"];
    
    // Call validate() to make sure every field meets criteria.
    $valid = validate($posID, $title, $description, $closeDate, $positionType, $contractType, $location, $applicationByPost, $applicationByEmail);
    if ($valid == FALSE) {
      echo "valid is false";
    } else {
      echo "valid is true";
      
      // rest of the program goes here.
      // it'll be the filewriting stuff plus checks for unique posID.
  }

  // Validate function to make the main body cleaner looking.
  function validate($posID, $title, $description, $closeDate, $positionType, $contractType, $location, $applicationByPost, $applicationByEmail) {
    $posIDPattern = "/^[P]\d\d\d\d$/";
    $titlePattern = "/^[a-zA-Z0-9][a-zA-Z0-9,.! ]{0,19}$/";
    $closeDatePattern = "/^\d{1,2}\/\d{1,2}\/\d{2}$/";
    $matchCounter = 0;
    
    if (preg_match($posIDPattern, $posID)) {
      $matchCounter++;
    }
    if (preg_match($titlePattern, $title)) {
      $matchCounter++;
    }
    if ($description != NULL) {
      $matchCounter++;
    }
    if (preg_match($closeDatePattern, $closeDate)) {
      $matchCounter++;
    }
    if ($positionType != NULL) {
      $matchCounter++;
    }
    if ($contractType != NULL) {
      $matchCounter++;
    }
    if ($location != (("" || NULL))) {
      $matchCounter++;
    }
    if (($matchCounter == 7) && (($applicationByPost == TRUE) || ($applicationByEmail == TRUE))) {
      return true;
    } else {
      return false;
    }
  }

  ?>
  
  <?php
  /* Task 3 Requirements:
  Req 1:
  1a) All fields must be supplied and valid for the PHP page to allow saving of the job vacancy to a text file.
  1b) Date must also be validated to conform to the dd/mm/yy format.
  1c) Position ID must be checked for uniqueness within the text file.
  Req 2:
  creation of the "jobposts" directory on Mercury server to store the job vacancy text file is automatically handled by the PHP script, if it does not exist.
  Req 3:
  Each vacancy should be saved in jobs.txt - each record should be one line, with a \n for each new line.
  Each field in a record should be separated by a tab (\t).
  The accept application by is stored as two separate columns.
  Req 4:
  Confirmation message should be generated for this entry followed by a link to return to the home page once the vacancy is stored successfully in the file.
  */
  ?>
  
</body>
</html>
