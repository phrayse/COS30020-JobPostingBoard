<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Job Search Post page" />
	<meta name="keywords"    content="post, job, form" />
  <!-- Make sure to update stuid and name before submission --> 
	<meta name="author"      content="STUID, NAME" />
	<!--link href="css/style.css" rel="stylesheet"/-->
	<title>JobSearch Post Job</title>
</head>
<body>
	<main>
    <h1>Job Vacancy Posting System</h1>
		<form id="jobPostForm" method="post" action="postjobprocess.php">
			<fieldset>

				<!-- Position ID. Text input type, NOT NULL, should be unique. 5 characters, P and four numbers -->
				<!-- Should this not be automatically set by server, not user? That way can autoassign unique ID -->
				<p><label>Position ID:
					<input id="posID" type="text" name="posID" pattern="^P\d{4}$" required/>
				</label></p>

				<!-- Title. Text input type, NOT NULL. Max 20 alphanumeric incl. [ ,.!] -->
				<p><label>Title:
					<input id="title" type="text" name="title" pattern="^[a-zA-Z0-9][a-zA-Z0-9,.! ]{0,19}$" required/>
				</label></p>

				<!-- Description. Text area type, NOT NULL. Max 260 characters -->
				<!-- should I implement scrubbing here to avoid potential injection -->
				<p><label for="description">Description:</label>
					<textarea id="description" name="description" rows="4" cols="50" minlength="1" maxlength="260" required></textarea>
				</p>

				<!-- Closing date. Text input type, NOT NULL, initial value is server's current date in dd/mm/yy -->
				<!-- php function to call the server and cop the date -->
				<!-- split the regex into a separate line cause it's very likely problematic -->
				<p><label>Closing Date:
					<?php
						$date = date("d/m/y");
						echo "<input id=\"closeDate\" type=\"text\" name=\"closeDate\" value=$date pattern=\"^\d{1,2}/\d{1,2}/\d{2}$\" required />";
					?>
				</label></p>

				<!-- Position. Radio button type, 2 options -->
				<p>Position:
					<label><input id="fullTime" type="radio" name="positionType" value="fullTime" required />Full Time</label>
					<label><input id="partTime" type="radio" name="positionType" value="partTime" />Part Time</label>
				</p>

				<!-- Contract. Radio button type, 2 options -->
				<p>Contract:
					<label><input id="ongoing" type="radio" name="contractType" value="ongoing" required />On-going</label>
					<label><input id="fixedTerm" type="radio" name="contractType" value="fixedTerm" />Fixed Term</label>
				</p>

				<!-- Accept application by. Checkbox input type, 2 options -->
				<p>Application by:
					<label><input id="postal" type="checkbox" name="postal" value=TRUE />Post</label>
					<label><input id="email" type="checkbox" name="email" value=TRUE />Email</label>
				</p>

				<!-- Location. Select option type, initial value "---" with options for each state -->
				<p><label for="location">Location:</label>
					<select id="location" name="location" required>
						<option value="" selected disabled hidden>---</option>
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

				<p>
					<input type="submit" value="Post"/>
					<input type="reset" value="Reset"/>
				</p>

				<!-- Requirement 3: Link to return to the Home page is provided -->
				<p>All fields are required. <a href="index.php">Return to Home Page</a></p>
			</fieldset>
		</form>
  </main>
</body>
</html>
