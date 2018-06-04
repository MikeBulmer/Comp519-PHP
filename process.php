<!--
Author: Michael Bulmer
Comp 519 Assignment 4
Submission 25/12/2017

This is the code that processes the users input on pressing the submit button.
-->

<?php
// Connect to database.
include 'database.php';

// Validation phone input
if (preg_match("/^0[ 0-9]{8,9}$/",$_REQUEST['phone'])) {
    $invalidPhone = FALSE;
}else {
    $invalidPhone = TRUE;
}

// Validate name input
if (!preg_match("/--|''|  /",$_REQUEST['user']) && preg_match("/^[a-zA-Z][-' a-zA-Z]+$/",$_REQUEST['user'])) {
    $invalidName = FALSE;
} else {
    $invalidName = TRUE;
}

// If input is invalid return user to form.
// HTML required is used to ensure the user must
// have choosen a class.

if ($invalidName || $invalidPhone ) {
    include 'main.php';
    exit();
}

$pdo->beginTransaction();
try
{
    // Check that there is still space avalible for the user.
    $stillSpace = "SELECT * FROM available WHERE sessionid=:sessionid and remain >0" ;
    $stillSpaceQry = $pdo->prepare($stillSpace);
    $stillSpaceQry->execute(array(':sessionid'=>$_REQUEST['sessionSelect']));

    // Return user to that original if session is now booked.
    if($stillSpaceQry->rowcount()<=0 )
    {
        echo "sorry that class is now full";
        include 'main.php';
        // Stop user trying to proceed with same booking again.
        unset($_REQUEST['classSelect']);
        $pdo->rollBack();
        exit();
        $pdo = NULL;

    }

    // Insert the booking into the database;
    $booking = "INSERT INTO booking VALUES (:sessionid, :user, :phone)";
    $bookingQRY = $pdo->prepare($booking);
    $bookingQRY->execute(array(':sessionid' => $_REQUEST['sessionSelect'], ':user'=>$_REQUEST['user'],':phone'=>$_REQUEST['phone'] ));

    // If unsuccessful display error and stop;
    if(!$bookingQRY) {
        echo "DATABASE ERROR";
        exit();
        $pdo = NULL;
    }

    echo "<html> <head> <link rel='stylesheet' href='gymstyle.css'> <title>Booking Successful</title>";
    echo "<link href='https://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet'>";
    echo "<link href='https://fonts.googleapis.com/css?family=Neucha' rel='stylesheet'>";
    echo "</head<<body><div><h3>Successful booking</h3>Booking details:";
    echo "<br />Class: ".$_REQUEST['classselect'];
    // get session details from sessionid
    $daytime = $stillSpaceQry->fetch();
    echo "<br />Session: ".$daytime['session'];
    echo "<br />Gym user: ".$_REQUEST['user'];
    echo "<br />Phone: ".$_REQUEST['phone'];
    echo "</div></body></html>";

    $pdo->commit();

} catch (Exception $e)
{
    $pdo->rollBack();
}
$pdo = NULL;

?>
