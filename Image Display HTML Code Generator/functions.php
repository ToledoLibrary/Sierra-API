<?php

function isbn($isbn, $token) {
  
    $isbn = $isbn;
    $token = $token;

    $curl = curl_init();
  
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://XXXX/iii/sierra-api/v6/bibs/search?limit=5&text=' . $isbn . '&fields=title,author,marc',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'authorization: Bearer ' . $token,
        'index: isbn',
        'suppresssed: false',
        'deleted: false',
        'Cookie: jcontainerId=.jcontainer-public1'
      ),
    ));
                
    $responseraw = curl_exec($curl);
    //echo $responseraw;

    $response = json_decode($responseraw, true);
  
    return $response;
    //print_r($response);
    //echo "<br>";


  }

  function enclink($bibid, $token) {

    $bibid = $bibid;
    $token = $token;

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://XXXX/iii/sierra-api/v6/bibs/' . $bibid . '?fields=author,title,marc',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'authorization: Bearer ' . $token,
      'index: isbn',
      'suppresssed: false',
      'deleted: false',
      'Cookie: jcontainerId=.jcontainer-public1'
    ),
  ));
              
  $responseraw = curl_exec($curl);
  //echo $responseraw . "<br><br><br>";
  
  $response = json_decode($responseraw, true);
  //print_r($response);

  return $response;

}


// FORMAT TITLE USING TITLECASE
function titlecase($title) {
  // Our array of 'small words' which shouldn't be capitalised if they aren't the first word. Add your own words to taste.
  $smallwordsarray = array('of','a','the','and','an','or','nor','but','is','if','then','else','when','at','from','by','on','off','for','in','out','over','to','into','with');
  $title = str_replace(" [KIT]", "", $title);
  $title = str_replace(" [electronic resource].", "", $title);
  $words = explode(' ', $title);
  
  foreach ($words as $key => $word) {
    if ($key == 0 or !in_array($word, $smallwordsarray))
    $words[$key] = ucwords($word);
  }
  
  $newtitle = implode(' ', $words);
  
  return $newtitle;

}

// FORMAT AUTHOR
function authorformat($author) {

  $author = trim($author);
        if (substr($author, -1) == ".") {
            $author = substr($author, 0, -1);
        }

        // AUTHOR FORMAT > SWAP FIRST NAME AND LAST NAME
        $authorname = explode(",",$author);
        $firstname = $authorname[1];
        $lastname = $authorname[0];

        $author = $firstname . " " . $lastname;

        return $author;
}
  
// GET TOKEN
function getToken() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://XXXX:443/iii/sierra-api/v6/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    #curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $headers = [
      'Host: catalog.toledolibrary.org',
      'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
      'Authorization: Basic XXXX',
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

?>