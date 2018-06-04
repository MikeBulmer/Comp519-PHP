<!--
Author: Michael Bulmer
Comp 519 Assignment 4
Submission 25/12/2017

Main displays the form to the user with data from the database.
Adjusts to show validation messages.
-->

<!DOCTYPE html>
<html>
  <head>
    <title>Gym Class Booking System</title>
    <link rel="stylesheet" href="gymstyle.css">
    <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Neucha" rel="stylesheet">

  </head>
  <body>
      <div>
    <h1>Book a Class</h1>
<?php

// Stop undefined variable errors
if (!isset($_REQUEST['classselect'])) {
    $_REQUEST['classselect'] ="";
}

// Gets classes that have places available to book
$classResult = $pdo->query("select DISTINCT name from available where remain>0");
echo "<br />";

// No space to book
if ($classResult->rowcount()== 0) {
    echo "Sorry, all classes are fully booked this week";
}
else {
    echo "The following classes are available to book:";
    // When a class is selected gym.php runs.
    echo "<form action='gym.php' method='POST' id='classform' >";
    echo "<select name='classselect' onchange='classform.submit()' form_id='classform' required> ";
    echo "<option value='' disabled selected> Select: </option>";

    foreach ($classResult as $key => $value) {
        echo "<option value='".$value["name"]."'";

        // If returning to the page from a validation error sets
        // the select option that the user have already choosen.

        if ($_REQUEST['classselect'] === $value["name"]){
            echo " selected ";
        }
        echo ">".$value["name"]."</option>";
    }
    echo "</select>   </form>";
}
echo "<form action='process.php' method='POST' id='mainform' >";

// Hidden input allows passing of values from the class select form to the main form.
echo "<input type='hidden' name='classselect' value='".$_REQUEST['classselect']."' >";

// If a class have been selected then display session details.
if (isset($_REQUEST['classselect'])) {
    // Only selects sessions with space.
    $sessionResult = $pdo->query("SELECT * FROM available where name='".$_REQUEST['classselect']."' and remain>0;" );
    echo "<select name='sessionSelect'  form_id='mainform'> ";

    foreach ($sessionResult as $key => $value) {
        echo "<option value='".$value["sessionid"]."'";
        // If returning to the page from a validation error sets
        // the select option that the user have already choosen.
        if ((int)$_REQUEST['sessionSelect'] == (int)$value["sessionid"]) {
            echo " selected ";
        }
        echo ">".$value["session"]."</option>";
    }
    echo "</select> ";
}

else {
    echo "<select name='sessionSelect'  form_id='mainform' disabled> ";
    echo "<option> please select a class</option>";
    echo "</select> ";
}
echo "<br />";

// Name input
echo "<br /> ";
if ($invalidName) {
    echo "<p> Please enter a valid name</p>";
}

echo "<label for='user'>Please enter your name <input type='text' length='50' name='user'";

// If a class have not yet been selected that disabled
if (!isset($_REQUEST['classselect'])){
    echo " disabled ";
}

// Keep value in input if valid
if (!$invalidName && isset($_REQUEST['user'])){
    echo "value = '".$_REQUEST['user']."' ";
}
echo "></label> <br />";

// Phone input
echo "<br />" ;
if ($invalidPhone) {
    echo "<p> Please enter a valid phone number</p>";
}
echo "<label for='user'>Please enter your phone number <input type='text' length='10' maxlength='10' name='phone'";

// If a class have not yet been selected that disabled
if (!isset($_REQUEST['classselect'])){
    echo " disabled ";
}
// Keep value in input if valid
if (!$invalidPhone && isset($_REQUEST['phone'])){
    echo "value = '".$_REQUEST['phone']."' ";
}
echo "></label> <br />";

echo "<button action='submit'> submit </button>";


?>
    </form>
</div>
</body>
</html>
