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
				$filter = "";
				$filter = checkFilter($filter);
				if (! $filter == "") {
					$flagged = applyFilter($flagged, $filter, $search);
				}
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

	// This is unnecessary - should be integrated into applyFilter().
	function checkFilter($filter) {
    if ($_GET["fieldFilter"] == "any") {$filter .= "00;";}
    if ($_GET["fieldFilter"] == "posID") {$filter .= "01;";}
    if ($_GET["fieldFilter"] == "title") {$filter .= "02;";}

    if ($_GET["posFilter"] == "any") {$filter .= "10;";}
    if ($_GET["posFilter"] == "fTime") {$filter .= "11;";}
    if ($_GET["posFilter"] == "pTime") {$filter .= "12;";}

    if ($_GET["conFilter"] == "any") {$filter .= "20;";}
    if ($_GET["conFilter"] == "ongoing") {$filter .= "21;";}
    if ($_GET["conFilter"] == "fixedTerm") {$filter .= "22;";}

    if ($_GET["appFilter"] == "any") {$filter .= "30;";}
    if ($_GET["appFilter"] == "postal") {$filter .= "31;";}
    if ($_GET["appFilter"] == "email") {$filter .= "32;";}
		
		if (! $_GET["locationFilter"] == "any") {$filter .= "40;";}
	
		return $filter;
	}
	
	
	
	//
	function applyFilter($flagged, $filter, $search) {
    // this could be done with far fewer variables but it's way easier debugging separately.
    $firstFilter = "";
    $secondFilter = "";
    $thirdFilter = "";
    $fourthFilter = "";
    $fifthFilter = "";

    // Search specific fields [All/PosID/Title].
    if (! strpos($filter, "00;") == FALSE) {
        $firstFilter = $flagged;
    } else {
			// these should be using if ($_GET["fieldFilter"] == any/posID/title) instead of strpos.
			// that way i can remove the checkfilter function.
        if (! strpos($filter, "01;") == FALSE) {$i = 0; $firstFilter = filterOne($flagged, $firstFilter, $i, $search);}
        if (! strpos($filter, "02;") == FALSE) {$i = 1; $firstFilter = filterOne($flagged, $firstFilter, $i, $search);}
    }

    // posType [Any/Full Time/Part Time].
    if (! strpos($filter, "10;") == FALSE) {
        $secondFilter = $firstFilter;
    } else {
        if (! strpos($filter, "11;") == FALSE) {$i = "fTime"; $secondFilter = filterTwo($firstFilter, $secondFilter, $i);}
        if (! strpos($filter, "12;") == FALSE) {$i = "pTime"; $secondFilter = filterTwo($firstFilter, $secondFilter, $i);}
    }
    
    // conType [Any/Ongoing/Fixed Term].
    if (! strpos($filter, "20;") == FALSE) {
        $thirdFilter = $secondFilter;
    } else {
        if (! strpos($filter, "21;") == FALSE) {$i = "ongoing"; $thirdFilter = filterThree($secondFilter, $thirdFilter, $i);}
        if (! strpos($filter, "22;") == FALSE) {$i = "fixedTerm"; $thirdFilter = filterThree($secondFilter, $thirdFilter, $i);}
    }
    
    // appType [Any/Postal/Email].
    if (! strpos($filter, "30;") == FALSE) {
        $fourthFilter = $thirdFilter;
    } else {
        if (! strpos($filter, "31;") == FALSE) {$i = 6; $fourthFilter = filterFour($thirdFilter, $fourthFilter, $i);}
        if (! strpos($filter, "32;") == FALSE) {$i = 7; $fourthFilter = filterFour($thirdFilter, $fourthFilter, $i);}
    }
    
    // Location search.
    if (strpos($filter, "40;") == FALSE) {
        $fifthFilter = $fourthFilter;
    } else {
        $i = $_GET["locationFilter"];
        $fifthFilter = filterFive($fourthFilter, $fifthFilter, $i);
    }

    return $fifthFilter;
	}
	
	
	
	//
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
