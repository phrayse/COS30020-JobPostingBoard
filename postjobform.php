<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Job Search Post page" />
	<meta name="keywords"    content="post, job, search" />
  <!-- Make sure to update stuid and name before submission --> 
	<meta name="author"      content="STUID, NAME" />
	<!--link href="css/style.css" rel="stylesheet"/-->
	<title>JobSearch Post Job</title>
</head>
<body>
  <main>
    <h1>Job Vacancy Posting System</h1>
      <!-- Make sure to update this link before uploading -->
      <form id="jobPostForm" method="post" action="https://mercury.swin.edu.au/cos30020/STUID/assign1/postjobprocess.php">
        <fieldset>
          
          <!-- Position ID. Text input type, NOT NULL, should be unique. 5 characters, P and four numbers -->
          <!-- Should this not be automatically set by server, not user? That way can autoassign unique ID -->
          <p><label>Position ID:
            <input id="posID" type="text" name="posID" pattern="[P][0-9]{5}"/>
          </label></p>

          <!-- Title. Text input type, NOT NULL. Max 20 alphanumeric incl. [ ,.!] -->
          <p><label>Title:
            <input id="title" type="text" name="title" pattern="[a-zA-Z0-9]+[a-zA-Z0-9,.! ]{1,20}"/>
          </label></p>
          
          <!-- Description. Text area type, NOT NULL. Max 260 characters -->
          <!-- should I implement scrubbing here to avoid potential injection -->
          <p><label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50" minlength="1" maxlength="260"></textarea>
          </p>
          
          <!-- Closing date. Text input type, NOT NULL, initial value is server's current date in dd/mm/yy -->
          <!-- php function to call the server and cop the date -->
          <!-- split the regex into a separate line cause it's very likely problematic -->
          <p><label>Closing Date:
            <?php
              echo "<input id=\"closeDate\" type=\"text\" name=\"closeDate\" value=\"date(\"d-m-y\")\"";
              echo " pattern=\"[0-9]{1,2}[/]{1}[0-9]{1,2}[/]{1}[0-9]{2}\" />";
            ?>
          </label></p>
          
          <!-- Position. Radio button type, 2 options -->
          <!-- Need to add some sort of validation here to ensure one option has been selected -->
          <p>Position:
            <label><input id="fullTime" type="radio" name="positionType" value="fullTime" />Full Time</label>
            <label><input id="partTime" type="radio" name="positionType" value="partTime" />Part Time</label>
          </p>
          
          <!-- Contract. Radio button type, 2 options -->
          <!-- Need to add some sort of validation here to ensure one option has been selected -->
          <p>Contract:
            <label><input id="ongoing" type="radio" name="contractType" value="ongoing" />On-going</label>
            <label><input id="fixedTerm" type="radio" name="contractType" value="fixedTerm" />Fixed Term</label>
          </p>
          
          <!-- Accept application. Checkbox input type, 2 options -->
          <p>Application by:
            <label><input id="post" type="checkbox" name="applicationType" value="post" />Post</label>
            <label><input id="mail" type="checkbox" name="applicationType" value="email" />Email</label>
          </p>
          
          <!-- Location. Select option type, initial value "---" with options for each state -->
          <p><label for="location">Location:</label>
            <select id="location" name="location">
              <option value="blank">---</option>
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

          <!-- Requirement 2: POST method for form submission is used -->
          <!-- need to put in the POST button and RESET button -->

          <!-- Requirement 3: Link to return to the Home page is provided -->
          <p>All fields are required. <a href="index.php">Return to Home Page</a></p>
        </fieldset>
      </form>
  </main>
</body>
</html>
