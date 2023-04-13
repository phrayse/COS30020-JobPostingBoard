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
