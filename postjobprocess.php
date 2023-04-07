<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Job Post processing page" />
  <meta name="keywords"    content="jobpost, job, search" />
  <meta name="author"      content="STUID, NAME" />
  <!--link href="css/style.css" rel="stylesheet"/-->
  <title>PROCESSING</title>
</head>
<body>
  <?php
  // This page checks the input data, writes the data to a text file, and generates the corresponding HTML output.
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
  
  // this line should ensure that every field has a response, but there might be a problem with the checkboxes idk yet
  if (count(array_filter($_POST)) != count($_POST)) {
    $posID = $_POST["posID"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $closeDate = $_POST["closeDate"];
    $positionType = $_POST["positionType"];
    $contractType = $_POST["contractType"];
    $location = $_POST["location"];
    // probs have to fuck around with these two to get them right
    $applicationByPost = $_POST["postal"];
    $applicationByEmail = $_POST["email"];

    validate($posID, $title, $description, $closeDate, $positionType, $contractType, $location, $applicationByPost, $applicationByEmail);
  }

  // Validate function to make the main body cleaner looking.
  function validate($posID, $title, $description, $closeDate, $positionType, $contractType, $location, $applicationByPost, $applicationByEmail) {
    $posIDPattern = "/^[P]\d\d\d\d$/";
    $titlePattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9,.! ]{1,20}$/";
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
    if ($location != ("blank" || NULL)) {
      $matchCounter++;
    }
    if (($matchCounter == 7) && (($applicationByPost != NULL) || ($applicationByEmail != NULL))) {
      return true;
    } else {
      return false;
    }
  }

  ?>
</body>
</html>
