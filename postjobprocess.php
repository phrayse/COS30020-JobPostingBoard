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
  // This page validates input data and writes it to a text file in a specified directory.
  print_r(array_filter($_POST));
  
  // Check checkboxes checked.
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
    echo "<p>Please ensure all form fields have been filled.";
    echo "<br><a href=\"postjobform.php\">Back to form</a></p>";
  } else {
    // initialise variables from form.
    $posID = sanitise($_POST["posID"]);
    $title = sanitise($_POST["title"]);
    $description = sanitise($_POST["description"]);
    $closeDate = sanitise($_POST["closeDate"]);
    $positionType = sanitise($_POST["positionType"]);
    $contractType = sanitise($_POST["contractType"]);
    $location = sanitise($_POST["location"]);
    
    // Call validate() to make sure every field meets criteria.
    $valid = validate($posID, $title, $description, $closeDate, $positionType, $contractType, $location, $applicationByPost, $applicationByEmail);
    if ($valid != TRUE) {
      echo "<p><em>$validateError</em></p>";
      echo "<p><a href=\"jobpostform.php\">Back to form</a></p>";
    } else {
      echo "valid is true";
      
      // Filewriting happens here.
			// Check jobposts directory exists, or create if needed.
			umask(0007);
			$dir = "../../data/jobposts";
			if (! is_dir($dir)) {
				mkdir($dir, 02770);
			}
			
			// Concatenate all form fields into one string.
			$fullJobString = "$posID\t$title\t$descriptio\t$closeDate\t$positionType\t$contractType\t$applicationByPost\t$applicationByEmail\t$location\n";
			// Create/append jobs.txt
			$filename = "../../data/jobposts/jobs.txt";
			$handle = fopen($filename, "a");
			if (fwrite($handle, $fullJobString)>0) {
				echo "<p>Listing added.";
				echo "<br><a href=\"index.php\">Home</a>";
				echo "<br><a href=\"postjobform.php\">Advertise another position</a></p>";
			} else {
				echo "<p>Failed to create listing.";
				echo "<br><a href=\"postjobform.php\">Back to form</a></p>";
			}
    }

    // Sanitise() cleans the form inputs.
    function sanitise($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
  
    // Validate() holds the patterns and validation tests.
    function validate($posID, $title, $description, $closeDate, $positionType, $contractType, $location, $applicationByPost, $applicationByEmail) {
      $posIDPattern = "/^[P]\d\d\d\d$/";
      $titlePattern = "/^[a-zA-Z0-9][a-zA-Z0-9,.! ]{0,19}$/";
      $closeDatePattern = "/^\d{1,2}\/\d{1,2}\/\d{2}$/";
      $matchCounter = 0;
      $validateError = "";
      
      if (preg_match($posIDPattern, $posID)) {
        $matchCounter++;
      } else {
        $validateError .= "<p>Error #01 - position ID invalid</p>";
      }
      if (preg_match($titlePattern, $title)) {
        $matchCounter++;
      } else {
        $validateError .= "<p>Error #02 - title invalid</p>";
      }
      if (!$description == "") {
        $matchCounter++;
      } else {
        $validateError .= "<p>Error #03 - description invalid</p>";
      }
      if (preg_match($closeDatePattern, $closeDate)) {
        $matchCounter++;
      } else {
        $validateError .= "<p>Error #04 - close date invalid</p>";
      }
      if (($positionType == "fullTime") || ($positionType == "partTime")) {
        $matchCounter++;
      } else {
        $validateError .= "<p>Error #05 - position type invalid</p>";
      }
      if (($contractType == "fixedTerm") || ($contractType == "ongoing")) {
        $matchCounter++;
      } else {
        $validateError .= "<p>Error #06 - contract type invalid</p>";
      }
      if ($location != (("") || (NULL))) {
        $matchCounter++;
      } else {
        $validateError .= "<p>Error #07 - location invalid</p>";
      }
      if (($matchCounter == 7) && (($applicationByPost == TRUE) || ($applicationByEmail == TRUE))) {
        return true;
      } else {
        $validateError .= "<p>Validation error</p>";
        return $validateError;
      }
    }
	}
  ?>
</body>
</html>
