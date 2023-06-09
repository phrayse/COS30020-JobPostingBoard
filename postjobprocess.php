<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Job Post processing page" />
    <meta name="keywords"    content="post, processing" />
    <meta name="author"      content="STUID, NAME" />
    <link href="style.css" rel="stylesheet" />
    <title>PROCESSING</title>
</head>
<body>
    <?php
    // This page validates input data and writes it to a text file in a specified directory.
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

    $backToForm = "<p><a href=\"postjobform.php\">Back to form</a></p>";
    if (!$applicationByPost && !$applicationByEmail) {
        echo "<p>Please ensure all form fields have been filled.</p>";
        echo $backToForm;
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
        if (!$valid) {
            echo "<p><em>$validateError</em></p>";
            echo $backToForm;
        } else {
            // Filewriting happens here.
            // Check jobposts directory exists, or create if needed.
            umask(0007);
            $dir = "../../data/jobposts";
            if (! is_dir($dir)) {
                mkdir($dir, 02770);
            }

            // Concatenate all form fields into one string.
            $fullJobString = "$posID\t$title\t$description\t$closeDate\t$positionType\t$contractType\t$applicationByPost\t$applicationByEmail\t$location\n";
            // Create/append jobs.txt
            $filename = "../../data/jobposts/jobs.txt";
            $handle = fopen($filename, "a");

            // Check posID is unique.
            if (isUnique($filename, $posID)) {
                if (fwrite($handle, $fullJobString)>0) {
                    echo "<p>Listing added.";
                    echo "<br><a href=\"index.php\">Home</a></p>";
                    echo $backToForm;
                } else {
                    echo "<p>Error #09 - failed to create listing.</p>";
                    echo $backToForm;
                }
                fclose($handle);
            } else {
                echo "<p>Error #08 - position ID already in use.</p>";
                echo $backToForm;
            }
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
        if (!empty($description)) {
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
        if (!empty($location)) {
            $matchCounter++;
        } else {
            $validateError .= "<p>Error #07 - location invalid</p>";
        }
        if (($matchCounter == 7) && ($applicationByPost || $applicationByEmail)) {
            return true;
        } else {
            $validateError .= "<p>Validation error</p>";
        return $validateError;
        }
    }

    // Split the all-jobs-in-one string into individual jobs, then compare the first 5 characters of each with posID.
    function isUnique($filename, $positionID) {
        $bigJobString = file_get_contents($filename);
        $jobArray = explode("\n", $bigJobString);
        foreach ($jobArray as $job) {
            if (strncmp($job, $positionID, 5) == 0) {
                return FALSE;
            }
        }
        return TRUE;
    }
    ?>
</body>
</html>