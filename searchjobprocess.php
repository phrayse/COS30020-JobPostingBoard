<!-- this page is cooked somewhere, the search results aren't getting cut off by current date. I'll fix it at some point -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Job Search Results" />
    <meta name="keywords"    content="job, search, results" />
    <meta name="author"      content="STUID, NAME" />
    <link href="style.css" rel="stylesheet" />
    <title>Search Results</title>
</head>
<body>
    <?php
    $filename = "../../data/jobposts/jobs.txt";
    if (isset($_GET["search"])) {
        // Probably better to do !file_exists and have the error messages first.
        if (file_exists($filename)) {
            $search = $_GET["search"];
            // Split all-jobs-in-one-line string into individual jobs.
            $bigJobString = file_get_contents($filename);
            $jobArray = explode("\n", $bigJobString);
            $flagged = "";
            $currentDate = time();

            $toIndex = "<p><a href=\"index.php\">Back to Index</a></p>";
            $toSearch = "<p><a href=\"searchjobform.php\">Back to Search</a></p>";
            $toPost = "<p><a href=\"postjobform.php\">Add to the job listings</a></p>";

            // Iterate through each individual job listing.
            foreach ($jobArray as $job) {
                // Limit search terms to the PosID, Title, and Description
                $subjob = array_slice(explode("\t", $job), 0, 3);
                $subjobString = implode("\t", $subjob);
                // Convert closedate element to Unix timestamp, then compare to current date.
                $jobDate = strtotime(DateTime::createFromFormat('d/m/y', explode("\t", $job)[3])->format('Y-m-d'));
                if ($jobDate >= $currentDate) {
                    if (stripos($subjobString, $search) !== FALSE) {
                        // Any matches get added to the $flagged variable. 
                        $flagged .= "$job\n";
                    }
                }
            }

            echo "<h1>Search results</h1>";
            if (!empty($flagged)) {
                $flagged = applyFilter($flagged, $search);
                if (!empty($flagged)) {
                    $flaggedArray = explode("\n", $flagged);
                    $flaggedArray = array_filter($flaggedArray);
                    usort($flaggedArray, function($a, $b) {
                        $dateA = strtotime(DateTime::createFromFormat('d/m/y', explode("\t", $a)[3])->format('Y-m-d'));
                        $dateB = strtotime(DateTime::createFromFormat('d/m/y', explode("\t", $b)[3])->format('Y-m-d'));
                        return $dateA - $dateB;
                    });
                    printflagged(implode("\n", $flaggedArray));
                } else {
                    // this whole else-chain can probs be cut down entirely
                    echo "<p>No results matched the search criteria.</p>";
                    echo $toIndex;
                    echo $toSearch;
                }
            } else {
                echo "<p><em>No search results</em>";
                echo $toIndex;
                echo $toPost;
            }
        } else { 
            echo "<p><em>Error #10 - jobs.txt does not exist";
            echo $toIndex;
        }
    } else {
        echo "<p><em>Error #11 - No search term entered</em>";
        echo $toIndex;
        echo $toSearch;
    }


    // Calls distinct filters for each field, passing the narrowed list down through each statement.
    function applyFilter($flagged, $search) {
        // Limit search fields.
        $i = $_GET["fieldFilter"];
        switch ($i) {
            case "any":
                break;
            case "posID":
                $flagged = filterOne($flagged, 0, $search);
                break;
            case "title":
                $flagged = filterOne($flagged, 1, $search);
                break;
        }

        // Position type.
        $i = $_GET["posFilter"];
        switch ($i) {
            case "any":
                break;
            case "fTime":
                $flagged = filterTwo($flagged, "fullTime");
                break;
            case "pTime":
                $flagged = filterTwo($flagged, "partTime");
                break;
        }

        // Contract type.
        $i = $_GET["conFilter"];
        switch ($i) {
            case "any":
                break;
            case "ongoing":
                $flagged = filterThree($flagged, "ongoing");
                break;
            case "fixedTerm":
                $flagged = filterThree($flagged, "fixedTerm");
                break;
        }

        // Application type.
        $i = $_GET["appFilter"];
        switch ($i) {
            case "any":
                break;
            case "postal":
                $flagged = filterFour($flagged, 6);
                break;
            case "email":
                $flagged = filterFour($flagged, 7);
                break;
        }

        // Location search.
        if (! $_GET["locationFilter"] == "any") {
            $flagged = filterFive($flagged, $_GET["locationFilter"]);
        }
        return $flagged;
    }


    // Filters 1 through 5.
    // Field search
    function filterOne($flagged, $i, $search) {
        $filterJobArray = explode("\n", $flagged);
        $flagged = "";
        foreach ($filterJobArray as $filterJob) {
            $filterFieldArray = explode("\t", $filterJob);
            if ($filterFieldArray[0] != NULL) {
                if (stripos($filterFieldArray[$i], $search) !== FALSE) {
                    $flagged .= "$filterJob\n";
                }
            }
        }
        return $flagged;
    }
    // Position type
    function filterTwo($flagged, $i) {
        $filterJobArray = explode("\n", $flagged);
        $flagged = "";
        foreach ($filterJobArray as $filterJob) {
            $filterFieldArray = explode("\t", $filterJob);
            if ($filterFieldArray[0] != NULL) {
                if ($filterFieldArray[4] == $i) {
                    $flagged .= "$filterJob\n";
                }
            }
        }
        return $flagged;
    }
    // Contract type
    function filterThree($flagged, $i) {
        $filterJobArray = explode("\n", $flagged);
        $flagged = "";
        foreach ($filterJobArray as $filterJob) {
            $filterFieldArray = explode("\t", $filterJob);
            if ($filterFieldArray[0] != NULL) {
                if ($filterFieldArray[5] == $i) {
                    $flagged .= "$filterJob\n";
                }
            }
        }
        return $flagged;
    }
    // Application type
    function filterFour($flagged, $i) {
        $filterJobArray = explode("\n", $flagged);
        $flagged = "";
        foreach ($filterJobArray as $filterJob) {
            $filterFieldArray = explode("\t", $filterJob);
            if ($filterFieldArray[0] != NULL) {
                if ($filterFieldArray[$i] == TRUE) {
                    $flagged .= "$filterJob\n";
                }
            }
        }
        return $flagged;
    }
    // Location
    function filterFive($flagged, $i) {
        $filterJobArray = explode("\n", $flagged);
        $flagged = "";
        foreach ($filterJobArray as $filterJob) {
            $filterFieldArray = explode("\t", $filterJob);
            if ($filterFieldArray[0] != NULL) {
                if ($filterFieldArray[8] == $i) {
                    $flagged .= "$filterJob\n";
                }
            }
        }
        return $flagged;
    }

    // Print all results in a table.
    function printFlagged($flagged) {
        $flagJobArray = explode("\n", $flagged);
        echo "<p>Your search matched " .(count($flagJobArray)) ." listing";
        if (count($flagJobArray) != 1) {
            echo "s";
        }
        echo ".</p>";
        // Split each individual job into distinct fields.
        $output = "";
        foreach ($flagJobArray as $flagJob) {
            $flagFieldArray = explode("\t", $flagJob);
            // Check for null, false, or empty string.
            if (!empty($flagFieldArray[0])) {
                // Concatenate HTML outputs to $output variable to reduce echo calls.
                $output .= 	"<table>".
                "<tr>".
                "<td>".
                "<p><strong>Position ID:</strong> {$flagFieldArray[0]}</p>".
                "<p><strong>Job Title:</strong> {$flagFieldArray[1]}</p>".
                "<p><strong>Description:</strong> {$flagFieldArray[2]}</p>".
                "<p><strong>Closing Date:</strong> {$flagFieldArray[3]}</p>".
                "<p><strong>Position:</strong> {$flagFieldArray[4]}</p>".
                "<p><strong>Contract:</strong> {$flagFieldArray[5]}</p>".
                "<p><strong>Location:</strong> {$flagFieldArray[8]}</p>".
                "<p><strong>Apply via:</strong> ";
                if ($flagFieldArray[6]) {
                    $output .= "Post";
                    if ($flagFieldArray[7]) {
                        $output .= " & Email";
                    }
                } elseif ($flagFieldArray[7]) {
                    $output .= "Email";
                }
                $output .= 	"</p>".
                "</td>".
                "</tr>".
                "</table>".
                "<br>";
            }
        }
        echo $output;
        echo $toIndex;
        echo $toSearch;
    }
    ?>
</body>
</html>