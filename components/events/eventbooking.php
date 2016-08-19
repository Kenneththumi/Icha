<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$person = new user();

if($_GET['from']=='newregistration')
{
    $this->loadextraClass('registration');
    $register = new registration;

    $subscriber = $register->registerSubscriber($_POST);
    $person->login($subscriber['username'], $subscriber['password']);
}

$eventid = $_GET['id'];
$userid = $_SESSION['userid'];

$eventchk = $this->runquery("SELECT count(*) FROM eventbooking WHERE eventid='$eventid' AND userid='$userid'",'single');

if($eventchk['count(*)']=='0')
{
    $bookevent = array(
        'eventid' => $eventid,
        'userid' => $userid
    );
    
    $this->dbinsert('eventbooking',$bookevent);//send notification email
    $details = $person->returnUserSourceDetails($_SESSION['sourceid'], 'subscriber');

    $html = '<p>Greetings,</p>
    <p>A user has just booked the following event:</p>
    <p><strong>Event:</strong></p>
    <p><strong>Partipant Name: '.$details['name'].'</strong></p>
    <p><strong>Telephone: '.$details['mobileno'].'</strong></p>
    <p><strong>Email: address: '.$details['email'].'</strong></p>
    <p>Please follow up this booking</p>
    <p><strong>Thanks</strong></p>
    <p>'.SITENAME.'</p>';

    $txt = strip_tags($html);

    $this->multiPartMail(MAILADMIN,'ICHA Event Booking Details',$html,$txt,MAILFROM,SITENAME);

    redirect($_POST['url'].'&msgvalid=The_event_booking_has_been_entered');
}
else
{
    redirect('?content=com_events&folder=same&file=eventdetails&id='.$eventid.'&msgerror=The_event_booking_has_already_been_entered');
}


?>
