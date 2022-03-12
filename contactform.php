<?php
require './vendor/autoload.php';
if (isset($_POST['Email'])) {

    $email_to = "mjkid221@gmail.com";

    function problem($error)
    {
        echo "We're sorry, but there were error(s) found with the form you submitted. ";
        echo "These errors appear below.<br><br>";
        echo $error . "<br><br>";
        echo "Please go back and fix these errors.<br><br>";
        die();
    }

    // validation expected data exists
    if (
        !isset($_POST['Name']) ||
        !isset($_POST['Email']) ||
        !isset($_POST['Message']) ||
        !isset($_POST['Subject'])
    ) {
        problem("We're sorry, but there appears to be a problem with the form you submitted.");
    }

    $name = $_POST['Name']; // required
    $email_id = $_POST['Email']; // required
    $message = $_POST['Message']; // required
    $email_subject = $_POST['Subject'];

    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $email_id)) {
        $error_message .= 'The Email address you entered does not appear to be valid.<br>';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $name)) {
        $error_message .= 'The Name you entered does not appear to be valid.<br>';
    }

    if (strlen($message) < 2) {
        $error_message .= 'The Message you entered do not appear to be valid.<br>';
    }

    if (strlen($email_subject) < 1) {
        $error_message .= 'The Subject you entered do not appear to be valid.<br>';
    }

    if (strlen($error_message) > 0) {
        problem($error_message);
    }

    $email_message = "Form details below.\n\n";

    function clean_string($string)
    {
        $bad = array("content-type", "bcc:", "to:", "cc:", "href");
        return str_replace($bad, "", $string);
    }

    $email_message .= "Name: " . clean_string($name) . "\n";
    $email_message .= "Email: " . clean_string($email_id) . "\n";
    $email_message .= "Message: " . clean_string($message) . "\n";

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom($email_to, $name);
    $email->setSubject($email_subject);
    $email->addTo($email_to, "MJ");
    $email->addContent("text/plain", $email_message);

    $API_key = getenv('SENDGRID_API_KEY');

    $sendgrid = new \SendGrid($API_key);
    try {
        $response = $sendgrid->send($email);
        // printf("Response status: %d\n\n", $response->statusCode());

        $headers = array_filter($response->headers());
        // echo "Response Headers\n\n";
        // foreach ($headers as $header) {
        //     echo '- ' . $header . "\n";
        // }
    } catch (Exception $e) {
        // echo 'Caught exception: '. $e->getMessage() ."\n";
    }
?>
    <!-- The email form is currently under maintenance. Please refer to the contact details on the website  -->
    Thank you for staying in touch. I will be contacting you within 3-5 business days.

<?php
}
?>