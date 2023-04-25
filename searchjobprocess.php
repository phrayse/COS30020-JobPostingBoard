<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Job Search processing page" />
  <meta name="keywords"    content="jobsearch, job, search" />
  <meta name="author"      content="STUID, NAME" />
  <!--link href="css/style.css" rel="stylesheet"/-->
  <title>Search results</title>
</head>
<body>
  <?php
  $filename = "../../data/jobposts/jobs.txt";
  if (isset($_GET["search"])) {
    if (file_exists($filename)) {
			$search = $_GET["search"];
			// Split all-jobs-in-one-line string into individual jobs.
			$bigJobString = file_get_contents($filename);
			$jobArray = explode("\n", $bigJobString);
			$flagged = "";
			// Iterate through each individual job listing.
			foreach ($jobArray as $job) {
				if (stripos($job, $search) !== FALSE) {
					// Any matches get added to the $flagged variable. 
					$flagged .= "$job\n";
				}
    	}
			echo "<h1>Search results</h1>";
			if (! $flagged == "") {
				$flagged = applyFilter($flagged, $search);
				printflagged($flagged);
			} else {
				echo "<p><em>No search results</em>";
				echo "<br><a href=\"postjobform.php\">Add to the job listings</a>";
				echo "<br><a href=\"index.php\">Back to index</a></p>";
			}
    } else { 
			echo "<p><em>Error #10 - jobs.txt does not exist";
			echo "<br><a href=\"index.php\">back to index</a></p>";
    }
  } else {
		echo "<p><em>Error #11 - No search term entered</em>";
		echo "<br><a href=\"index.php\">Back to index</a>";
		echo "<br><a href=\"searchjobform\">Back to search</a></p>";
  }

	
	// Calls to distinct filters for each field, passing the narrowed list down through each statement.
	function applyFilter($flagged, $search) {
    // this could be done with far fewer variables but it's way easier debugging separately.
    $firstFilter = "";
    $secondFilter = "";
    $thirdFilter = "";
    $fourthFilter = "";
    $fifthFilter = "";

    // Search specific fields [All/PosID/Title].
    if ($_GET["fieldFilter"] == "any") {
        $firstFilter = $flagged;
    } else {
			// these should be using if ($_GET["fieldFilter"] == any/posID/title) instead of strpos.
			// that way i can remove the checkfilter function.
        if ($_GET["fieldFilter"] == "posID") {$i = 0; $firstFilter = filterOne($flagged, $firstFilter, $i, $search);}
        if ($_GET["fieldFilter"] == "title") {$i = 1; $firstFilter = filterOne($flagged, $firstFilter, $i, $search);}
    }

    // posType [Any/Full Time/Part Time].
    if ($_GET["posFilter"] == "any") {
        $secondFilter = $firstFilter;
    } else {
        if ($_GET["posFilter"] == "fTime") {$i = "fTime"; $secondFilter = filterTwo($firstFilter, $secondFilter, $i);}
        if ($_GET["posFilter"] == "pTime") {$i = "pTime"; $secondFilter = filterTwo($firstFilter, $secondFilter, $i);}
    }
    
    // conType [Any/Ongoing/Fixed Term].
    if ($_GET["conFilter"] == "any") {
        $thirdFilter = $secondFilter;
    } else {
        if ($_GET["conFilter"] == "ongoing") {$i = "ongoing"; $thirdFilter = filterThree($secondFilter, $thirdFilter, $i);}
        if ($_GET["conFilter"] == "fixedTerm") {$i = "fixedTerm"; $thirdFilter = filterThree($secondFilter, $thirdFilter, $i);}
    }
    
    // appType [Any/Postal/Email].
    if ($_GET["appFilter"] == "any") {
        $fourthFilter = $thirdFilter;
    } else {
        if ($_GET["appFilter"] == "postal") {$i = 6; $fourthFilter = filterFour($thirdFilter, $fourthFilter, $i);}
        if ($_GET["appFilter"] == "email") {$i = 7; $fourthFilter = filterFour($thirdFilter, $fourthFilter, $i);}
    }
    
    // Location search.
    if ($_GET["locationFilter"] == "any") {
        $fifthFilter = $fourthFilter;
    } else {
        $fifthFilter = filterFive($fourthFilter, $fifthFilter, $_GET["locationFilter"]);
    }

    return $fifthFilter;
	}
	
	
	
	// Field search
	function filterOne($flagged, $firstFilter, $i, $search) {
		$filterJobArray = explode("\n", $flagged);
    foreach ($filterJobArray as $filterJob) {
			$filterFieldArray = explode("\t", $filterJob);
			if (! $filterFieldArray[$i] == NULL) {
				if (! stripos($filterFieldArray[$i], $search) == FALSE) {
					$firstFilter .= "$filterJob\n";
				}
			}
    }
    return $firstFilter;
	}
	// Position type
	function filterTwo($firstFilter, $secondFilter, $i) {
    $filterJobArray = explode("\n", $firstFilter);
    foreach ($filterJobArray as $filterJob) {
			$filterFieldArray = explode("\n", $filterJob);
			if (! $filterFieldArray[0] == NULL) {
				if ($filterFieldArray[4] == $i) {
					$secondFilter .= "$filterJob\n";
				}
			}
    }
    return $secondFilter;
	}
	// Contract type
	function filterThree($secondFilter, $thirdFilter, $i) {
    $filterJobArray = explode("\n", $secondFilter);
    foreach ($filterJobArray as $filterJob) {
			$filterFieldArray = explode("\n", $filterJob);
			if (! $filterFieldArray[0] == NULL) {
				if ($filterFieldArray[5] == $i) {
					$thirdFilter .= "$filterJob\n";
				}
			}
    }
    return $thirdFilter;
	}
	// Application type
	function filterFour($thirdFilter, $fourthFilter, $i) {
    $filterJobArray = explode("\n", $thirdFilter);
    foreach ($filterJobArray as $filterJob) {
			$filterFieldArray = explode("\n", $filterJob);
			if (! $filterFieldArray[0] == NULL) {
				if ($filterFieldArray[$i] == TRUE) {
					$fourthFilter .= "$filterJob\n";
				}
			}
    }
    return $fourthFilter;
	}
	// Location
	function filterFive($fourthFilter, $fifthFilter, $i) {
    $filterJobArray = explode("\n", $fourthFilter);
    foreach ($filterJobArray as $filterJob) {
			$filterFieldArray = explode("\n", $filterJob);
			if (! $filterFieldArray[0] == NULL) {
				if ($filterFieldArray[8] == $i) {
					$fifthFilter .= "$filterJob\n";
				}
			}
    }
    return $fifthFilter;
	}
	
	
	// Print all results in a table.
  function printFlagged($flagged) {
		$flagJobArray = explode("\n", $flagged);
		echo "<p>Your search matched " .(count($flagJobArray)-1) ." listing";
		if (count($flagJobArray) > 2) {
			echo "s";
		}
		echo ".</p>";
		echo "<table>";
		// Split each individual job into distinct fields.
		foreach ($flagJobArray as $flagJob) {
			$flagFieldArray = explode("\t", $flagJob);
			if ($flagFieldArray[0] != NULL) {
				echo "<tr><td>Position ID: $flagFieldArray[0]</tr></td>";
				echo "<tr><td>Job title: $flagFieldArray[1]</tr></td>";
				echo "<tr><td>Description: $flagFieldArray[2]</tr></td>";
				echo "<tr><td>Closing date: $flagFieldArray[3]</tr></td>";
				echo "<tr><td>Position: $flagFieldArray[4]</tr></td>";
				echo "<tr><td>Contract: $flagFieldArray[5]</tr></td>";
				// There's gotta be a way cleaner way than this but whatever.
				if (($flagFieldArray[6] == TRUE) && ($flagFieldArray[7] == TRUE)) {
					echo "<tr><td>Application by post or email.</tr></td>";
				} else {
					if ($flagFieldArray[6] == TRUE) {
						echo "<tr><td>Application by post only.</tr></td>";
					}
					if ($flagFieldArray[7] == TRUE) {
						echo "<tr><td>Application by email only.</tr></td>";
					}
				}
				echo "<tr><td>Location: $flagFieldArray[8]</tr></td>";
			}
		}
		echo "</table>";
  }
  ?>
</body>
</html>
