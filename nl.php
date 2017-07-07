<?php
  setlocale(LC_CTYPE, 'en_US.UTF-8');

  // config
  $domain = 'welchezukunft.org';
  $ml_name = 'newsletter';
  $err_sub_mail_missing = 'Bitte geben Sie eine Email-Adresse an.';
  $ok_sent_data_success = 'Daten erfolgreich übermittelt.' . "\r\n" .
    'Bitte bestätigen Sie die Email, welche Sie in Kürze von uns erhalten';
  $ok_sub_confirmed = 'Eintragung in den Newsletter erfolgreich bestätigt.';
  $ok_unsub_confirmed = 'Austragung aus dem Newsletter erfolgreich bestätigt.';

  // define variables and set to empty values
  //// given via GET
  $subject = $unsub_mail = "";
  //// given via POST
  $sub_mail = $name_first = $name_last = "";
  //// used in form
  $feedback = "";
  //// used only here
  $mail_address = $mail_subject = $mail_msg = $return_value = "";

  // functions

  //// input validation

  function isAscii($data) {
    return mb_check_encoding($data, 'ASCII');
  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  //// storing names along side address
  //// TODO

  //// mail

  function _mail($adr, $subj, $msg) {
    $return_value = mail($adr.'@'.$domain, $subj, $msg);
    return $return_value;
  }

  // sub form logic if POST
  // otherwise GET stuff

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // start doing sub stuff if mail address is provided
    // else throw error
    if (isset($_POST["sub_mail"])) {
      // validate input and put into vars
      $sub_mail = test_input($_POST["sub_mail"]);
      $name_first = test_input($_POST["name_first"]);
      $name_last = test_input($_POST["name_last"]);

      // set vars for mail to send
      //// we must replace '@' of the users address so we can mail ist to ezmlm
      $sub_mail_ezmlm = str_replace('@', '=', "$sub_mail");
      $adr = $ml_name.'-subscribe-'.$sub_mail_ezmlm;
      $subj = "";
      $msg = "";
      //// TEST $return_value = _mail($adr, $subj, $msg);
      // if ($return_value == 0) {
      //   $feedback = $ok_sent_data_success;
      // }
      echo("<p>$adr, $subj, $msg</p>");
    } else {
      $feedback = $err_sub_mail_missing;
    }
  } else {
    // GET stuff
    // get env
    $unsub_mail = $_GET['unsub'];
 
    // confirmations
    if (isset($_GET["subject"])) {
      $adr = $ml_name.'-request';
      $subj = test_input($_GET["subject"]);
      $msg = "";

      //// TEST $return_value = _mail($adr, $subj, $msg);
      // if ($return_value == 0) {
      //   $feedback = $ok_sub_confirmed;
      // }
      echo("<p>$adr, $subj, $msg</p>");
     }
    // unsub
    if (isset($_GET["unsub"])) {
      $unsub_mail = test_input($_GET["unsub"]);
      //// we must replace '@' of the users address so we can mail ist to ezmlm
      $unsub_mail_ezmlm = str_replace('@', '=', "$unsub_mail");
      $adr = $ml_name.'-unsubscribe-'.$unsub_mail_ezmlm;
      $subj = "";
      $msg = "";

      //// TEST $return_value = _mail($adr, $subj, $msg);
      // if ($return_value == 0) {
      //   $feedback = $ok_unsub_confirmed;
      // }
      echo("<p>$adr, $subj, $msg</p>");
     }
  }
?>

