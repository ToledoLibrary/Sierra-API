<!--
Use at your own risk. Not supported by the Toledo Lucas County Public Library.

Replace all XXXX with library-specific information.
-->

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Tools</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="imagestyles.css">


<style>
.post-container {
    margin: 20px 20px 0 0;  
    overflow: auto
}
.post-thumb {
    float: left;
}
.post-thumb img {
    display: block;
}
.post-content {
    margin-left: 100px;
	line-height:150%;
}
.post-title {
    font-weight: bold;
    font-size: 200%;
}
</style>

</head>
  <body>

<div class="container my-5" style="font-family: Arial !important;">
<?php

booklist_function();


function booklist_function() {

        $token = getSierraToken();
        #echo $token;

        $xmlData = file_get_contents('http://bookdb.nextgoodbook.com/v3/api/custom/lid/XXXX/nlid/XXXX/format/xml');
        #echo $xmlData . "<br><br>";

        $xml = simplexml_load_string($xmlData);

        $listname = array();
                
        foreach ($xml->xpath('//item') as $record) {
                $listname[] = array(
                        'name' => (string) $record->name
                );
        }	

        $result = array();


        foreach ($xml->xpath('//book') as $record) {
        $result[] = array(
                'id' => (string) $record->id,
                'isbn' => (string) $record->isbn,
                'title' => (string) $record->title,
                'author' => (string) $record->author,
                'publisher' => (string) $record->publisher,
                'publicationDate' => (string) $record->publicationDate,
                'binding' => (string) $record->binding,
                'synopsis' => (string) $record->synopsis,
                'image_link' => (string) $record->image_link
        
        );
        }

        $resultCount = count($result) - 1;

        for ($i=0;$i<=$resultCount;$i++) {

				echo "<div class='post-container'>";

                $isbn = $result[$i]["isbn"];
                				
				echo "<div class='post-thumb'><img src='https://contentcafe2.btol.com/ContentCafe/Jacket.aspx?UserID=XXXX&Password=XXXX&Return=1&Type=S&Value=" . $isbn . "&erroroverride=1'></div><div class='post-content'>";

                $title = $result[$i]["title"];
                echo "<span style='font-weight:900;'>" . $title . "</span><br>";
				echo "ISBN: " . $isbn . "<br>"; 

                getBibNum($token,$isbn);

        }

}


function getSierraToken(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://XXXX/iii/sierra-api/v6/token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'authorization: Basic XXXX',
            'Cookie: jcontainerId=.jcontainer-public1'
          ),
        ));
        
        $response = curl_exec($curl);
        #echo $response;

        $response = json_decode($response, true);
        $token = $response['access_token'];

        curl_close($curl);
        return $token;

}


function getBibNum($token,$isbn){

        $token = $token;
        $isbn = $isbn;

        $curl = curl_init();

        curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://XXXX/iii/sierra-api/v6/bibs/search?limit=5&text='. $isbn,
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
              #echo $responseraw . "<br>";
   
              $response = json_decode($responseraw, true);

              $count = $response['count'];
              $total = $response['total'];
              $start = $response['start'];
              $entries = $response["entries"];
              #print_r($entries);
              #echo "<br><br>";

			  $bibid = $entries[0]["bib"]["id"];
              echo "Bib Num: " . $bibid . "<br>";
			  echo "<a target=_blank href='https://cXXXX/record=b" . $bibid . "~S1'>https://XXXX/record=b" . $bibid . "~S1</a><br>";
			  echo "<a target=_blank href='https://XXXX/iii/encore/record/C__Rb" . $bibid . "?lang=eng'>https://XXXX/iii/encore/record/C__Rb" . $bibid . "?lang=eng</a></div></div>";

              curl_close($curl);

}


?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
