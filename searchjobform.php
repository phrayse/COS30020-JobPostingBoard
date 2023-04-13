<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Job Vacancy Search page" />
	<meta name="keywords"    content="jobsearch, job, search" />
	<meta name="author"      content="STUID, NAME" />
	<!--link href="css/style.css" rel="stylesheet"/-->
	<title>Search Job Vacancies</title>
</head>
<body>
	<main>
		<h1>Job Vacancy Search System</h1>
		<form id="jobSearch" method="get" action="searchjobprocess.php">
			<!-- This is generic search - search the entirety of each job post for a hit -->
			<p><label>
				<input id="search" type="text" name="search" />
			</label></p>
			
			<input type="submit" value="submit" />
			<input type="reset" value="clear" />
		</form>
		<p><a href="index.php">Back to index</a>
		<br><a href="postjobform.php">Post a job</a></p>
	</main>
</body>
</html>
