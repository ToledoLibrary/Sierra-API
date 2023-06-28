<?php 
session_start();
unset($_SESSION['email']);

if (isset($_POST['email'])) {

  $email = $_POST['email'];

  // GET USER DATA
  $userData = getData($email);
  
  // SET MESSAGE BASED ON STATUS RETURNED FROM SIERRA API
  $httpStatus = $userData["httpStatus"];
  
  switch ($httpStatus) {
    case 404:   
      $notification =  "The above email address was not found.<br><br>Either this email address does not exist in any account or it may be associated with an account that has multiple email addresses.<br><br>Please contact xxxNAME OR DEPARTMENTxxx at xxxPHONEXXX for assistance."; 
      sendmail($email, $notification);
      break;
  
    case 409:
      $notification = "Multiple patrons found for the specified email address.<br><br>Please contact xxxNAME OR DEPARTMENTxxx at xxxPHONEXXX for assistance.";
      sendmail($email, $notification);
      break;
    
    default:
      $notification = "Barcode: " . $userData["barcodes"][0];
      sendmail($email, $notification);
      break;
  
  }

} 


// GET TOKEN
function getToken() {
  $ch = curl_init();
  // ENTER THE HOST URL FOR THE SIERRA API FOR YOUR CATALOG 
  curl_setopt($ch, CURLOPT_URL,"https://<ENTER HOST URL>/iii/sierra-api/v6/token");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  // ENTER Host URL, Base64-encoded credentials
  $headers = [
    'Host: https://<ENTER HOST URL>',
    'Content-Type: application/x-www-form-urlencoded; charset=utf-8',

    'Authorization: Basic <ENTER Base64-encoded CREDENTIALS>',
    'Content-Type: application/x-www-form-urlencoded'
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $server_output = curl_exec ($ch);
  
  curl_close ($ch);
  
  $response = explode(",",  $server_output);
  $splitresponse = explode(":",  $response[0]);
  $token = str_replace(array("'", "\"", "&quot;"), "", htmlspecialchars($splitresponse[1]));
  
  return $token;
  }
  
  
  // GET DATA
  function getData($email) {
  
  $token = getToken();
  
  $ch = curl_init();
  
  // ENTER SIERRA HOST URL 
  curl_setopt($ch, CURLOPT_URL,"https://<ENTER HOST URL>/iii/sierra-api/v6/patrons/find?varFieldTag=z&varFieldContent=" . $email . "&fields=emails,barcodes");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  // ENTER HOST URL
  $headers = [
    'Host: <ENTER HOST URL>',
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $responseraw = curl_exec($ch);
  curl_close($ch);
  
  $response = json_decode($responseraw, true);
  return $response;
  
  }


  function sendmail($email, $notification) {

      $email = $email;
      $notification = $notification;
      $subject = "Library Card Help Request";
      
      // REPLACE CONTACT INFO >  INSTANCES PRECEDED BY xxx
      // REPLACE or REMOVE LINK TO FORGOT YOUR PIN
    
        $message = "
        <html>
        <head>
        <title>Library Card Help Request</title>
        </head>
        <body>
        <p>Someone requested the Toledo Lucas County Public Library card barcode associated with " . $email.  ".<p>
        <p>No changes have been made to your account.</p>
        <p>" . $notification . "</p>
        <p>If you did not make this request, please ignore this email.</p>";

        $substring = substr($notification, 0, 37); // Extract the first 37 characters from $notification

        if ($substring !== "The above email address was not found") {
          $message .= "<p>If you forgot your PIN, click <a href='https://catalog.toledolibrary.org/pinreset~S1*eng'>here</a> to reset it.<p>";
        }
        
        $message .= "
        <p><br></p>
        <p>Toledo Lucas County Public Library<br>
        Winner of the National Medal for Museum and Library Services<br>
        419.259.5200<br>
        <a href='https://toledolibrary.us12.list-manage.com/track/click?u=dac9884c6ba4158b2a57952ed&id=d4c813c61f&e=ef77cdc9b8'>Web</a> | 
        <a href='https://toledolibrary.us12.list-manage.com/track/click?u=dac9884c6ba4158b2a57952ed&id=49c77f6e5a&e=ef77cdc9b8'>Facebook</a> | 
        <a href='https://toledolibrary.us12.list-manage.com/track/click?u=dac9884c6ba4158b2a57952ed&id=0fa474c945&e=ef77cdc9b8'>Twitter</a> | 
        <a href='https://toledolibrary.us12.list-manage.com/track/click?u=dac9884c6ba4158b2a57952ed&id=f26d1a9989&e=ef77cdc9b8'>Instagram</a> | 
        <a href='https://toledolibrary.us12.list-manage.com/track/click?u=dac9884c6ba4158b2a57952ed&id=0099221c88&e=ef77cdc9b8'>YouTube</a><br>
        </body>
        </html>
        ";
      
      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      
      // ENTER FROM EMAIL ADDRESS
      $headers .= 'From: Your Library<dept@yourlibrary.org>' . "\r\n";

      // TEST MAIL >> SENDS THE TEST EMAILS TO THE ACCOUNT SPECIFIED. TO USE COMMENT OUT LIVE MAIL AND ENTER A TEST EMAIL ADDRESS BELOW
      // THIS WILL SEND THE RESPONSE EMAIL TO THE EMAIL ADDRESS BELOW INSTEAD OF THE RECIPIENT
      //$to = "test@test.com";
      //mail($to,$subject,$message,$headers);

     // LIVE MAIL
     mail($email,$subject,$message,$headers); 

      }


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>  
    $(document).ready(function() {

      $('#correct').hide();
      $('.error').hide();

      $('#submit').click(function(){

        var email = $('#email').val();

        if(email== ''){
          $('#email').next().show();
          return false;
        }
        if(IsEmail(email)==false){
          $('#email_invalid').show();
          return false;
        }
        $.post("", $("#getbarcode").serialize(),  function(response) {
          $('#getbarcode').fadeOut('slow',function(){
          $('#correct').fadeIn('slow');
       });
     });
    return false;
  });
 });

 function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if(!regex.test(email)) {
    return false;
  }else{
    return true;
  }
}
</script>

</head>
    
<body>

<div class="container" style="margin-top:50px;">

<form method=post action="index.php" id="getbarcode">

	<br>
    <fieldset class="search-border">
                <legend class="search-border" style="font-size:1em;margin-left:10px;font-weight:700;"></legend>
                <div class="control-group" style="padding-left:35px;padding-top:10px;">
                
                <div class="form-group row" >             
                <div class="col-sm-24" style="font-size:1em;">

                  <span style="font-size:1.1em;font-weight:700;">Enter your email address to have your barcode sent to you:</span><br><br>
                  
                  <input input name="email" id="email" type="text" style="width:300px;height:31px;border: 1px solid #ccc;padding-left:10px;">
              <input type="submit" data-inline="true" value="Submit"  id="submit" class="btn-sm btn-danger">
              <br>
              <span class="error" id="email_invalid" style="color:maroon;font-weight:700;">Please enter a valid email address.</span>
              <br>
              <span id="pinreset">
                <a href="https://catalog.toledolibrary.org:443/pinreset~S1*eng">Forgot your PIN?</a>
              </span>
              <br><br>
              <span style="font-weight:700;">Please contact xxxNAME OR DEPARTMENTxxx at xxxPHONE NUMBERxxx if:</span>
                    <ul>
                      <li>you have more than one email address listed in your account.
                      <li>your email address is used for multiple cardholders.
                  </ul>
                </div>
                </div>
              </div>

</fieldset>
</form>
<div id="correct">
Information has been sent to the email address provided.<br><br>Please contact xxxNAME OR DEPARTMENTxxx at xxxPHONE NUMBERxxx if further assistance is needed.
</div>

</div>
                 
</body>
</html>
