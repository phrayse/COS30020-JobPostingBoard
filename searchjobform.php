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
			<!-- Search bar -->
			<p><label>
				<input id="search" type="text" name="search" />
			</label></p>
			
			<h3>Filter your search:</h3>
            <p>
            <strong>Limit search to:</strong>
                <label><input id="fieldFilter" type="radio" name="fieldFilter" value="any" required checked />Any field</label>
                <label><input id="positionFilter" type="radio" name="fieldFilter" value="posID" />Position ID</label>
                <label><input id="titleFilter" type="radio" name="fieldFilter" value="title" />Job Title</label>
            <br>
            <strong>Position:</strong>
                <label><input id="posFilter" type="radio" name="posFilter" value="any" required checked />Any</label>
                <label><input id="fTimeFilter" type="radio" name="posFilter" value="fTime" />Full Time</label>
                <label><input id="pTimeFilter" type="radio" name="posFilter" value="pTime" />Part Time</label>
            <br>
            <strong>Contract:</strong>
                <label><input id="conFilter" type="radio" name="conFilter" value="any" required checked />Any</label>
                <label><input id="ongoingFilter" type="radio" name="conFilter" value="ongoing" />Ongoing</label>
                <label><input id="fixedTermFilter" type="radio" name="conFilter" value="fixedTerm" />Fixed Term</label>
            <br>
            <strong>Application:</strong>
                <label><input id="appFilter" type="radio" name="appFilter" value="any" required checked />Any</label>
                <label><input id="postalFilter" type="radio" name="appFilter" value="postal" />Post</label>
                <label><input id="emailFilter" type="radio" name="appFilter" value="email" />Email</label>
            <br>
            <strong>Location:</strong>
                <label for="locationFilter">Location:</label>
										<select id="locationFilter" name="locationFilter" required>
												<option value="any" selected>Any</option>
												<option value="ACT">ACT</option>
												<option value="NSW">NSW</option>
												<option value="NT">NT</option>
												<option value="QLD">QLD</option>
												<option value="SA">SA</option>
												<option value="TAS">TAS</option>
												<option value="VIC">VIC</option>
												<option value="WA">WA</option>
										</select>
            </p>
            
            <input type="submit" value="submit" />
            <input type="reset" value="clear" />
        </form>
		<p><a href="index.php">Back to index</a>
		<br><a href="postjobform.php">Post a job</a></p>
	</main>
</body>
</html>
