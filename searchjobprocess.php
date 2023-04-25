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
				if (! $flagged == "") {
					printflagged($flagged);
				} else {
					echo "<p>No results matched the search criteria.</p>";
					echo "<p><a href=\"index.php\">Back to index</a>";
					echo "<br><a href=\"searchjobform\">Back to search</a></p>";
				}
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
		echo "<p>Your search matched " .(count($flagJobArray)-1) ." listing";
		if (count($flagJobArray) > 2) {
			echo "s";
		}
		echo ".</p>";
		// Split each individual job into distinct fields.
		foreach ($flagJobArray as $flagJob) {
			$flagFieldArray = explode("\t", $flagJob);
			if ($flagFieldArray[0] != NULL) {
				echo "<table>";
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
				echo "</table>";
				echo "<br>";
			}
		}
		echo "<p><a href=\"index.php\">Back to index</a>";
		echo "<br><a href=\"searchjobform.php\">Back to search</a></p>";
	}
	?>
</body>
</html>
