<?php include 'functions.php';?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Tools</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="XXXX" crossorigin="anonymous">
    <link rel="stylesheet" href="imagestyles.css">
  </head>
  <body>

<?php

if(isset($_POST['Generate'])) { //check if form was submitted

    $textdata = $_POST['textdata'];
    $imagesize = $_POST['imagesize'];
    $displayimages = $_POST['displayimages'];
    $displayimages = number_format($displayimages, 0);
    $summary = $_POST['summary'];

    $array = array_values(array_filter(explode(PHP_EOL, $textdata)));

    $arraysize = sizeof($array);
    $token = getToken();

    $final_display_text = "";
    $intCount = 0;
    $displaytext = "";
    $isbnerror = "";


    foreach ($array as $value) {

        $testvalue =  substr(trim($value), 0, 4);

        if( $testvalue == "http") {

            $encorelink = trim($value);

            $splitlink = explode("C__Rb", $encorelink);
            $bibid = $splitlink[1];

            $response = enclink($bibid, $token);

            $response = enclink($bibid, $token);
            $title = $response["title"];
            $author = $response["author"];
            $arrfields = $response["marc"]["fields"];
            $key020 = array_column($arrfields, '020');
            $isbn = $key020[0]['subfields'][0]['a'];
            $key520 = array_column($arrfields, '520');
            $summarytext = $key520[0]['subfields'][0]['a'];

            if ($isbn === null || empty($isbn)) {
                
                $isbnerror .= "<br><a target='_blank' href='" . $encorelink . "'>" . $bibid . "</a>";
                continue;
            }

        } else {

            $isbn = trim($value);
            $response = isbn($isbn, $token);

            $entries = $response["entries"];

            if ($entries === null || empty($entries)) {
                
                $isbnerror .= "<br>" . $isbn;
                continue;

            } else {

            $bibid = $entries[0]["bib"]["id"];
            $title = $entries[0]["bib"]["title"];
            $author = $entries[0]["bib"]["author"];
            $arrfields = $entries[0]["bib"]["marc"]["fields"];
            $key520 = array_column($arrfields, '520');
            $keysubf = array_column($key520, 'subfields');
            $summarytext = $keysubf[0][0]["a"];

            // LINK TO ENCORE
            $encorelink = "https:/XXXX/iii/encore/record/C__Rb" . $bibid;

            }
        }

        // TITLE FORMAT
        $title = titlecase($title);
        
        // AUTHOR FORMAT
        $author = authorformat($author);

        $titleauthorlink = "<a href='" . $encorelink . "'><b><i>" . $title . "</i></b></a> by " . $author;

        $breakpoint = fmod($intCount, $displayimages);

        switch ($displayimages) {
            case 1:

                // FIRST ROW OR NEW ROW
                
                $displaytext .= "<div class='special-display-1" .$imagesize . "-image-grid'>";
                $displaytext .= "<div><img class='specialdisplayimage1' src='https://contentcafe2.btol.com/ContentCafe/Jacket.aspx?UserID=XXXX&Password=XXXX&Return=1&Type=" . $imagesize. "&Value=" . $isbn . "&erroroverride=1'></div>";
                $displaytext .= "<div>" . $titleauthorlink;

                if ($summary == "Y") {
                    $displaytext .= "<br><br>" . $summarytext;
                }

                $displaytext .= "</div>";
                $displaytext .= "</div>";
                break;

            default;

                // FOR IMAGE DISPLAY 3 OR 5

                    if ( ($intCount == 0) || ($breakpoint == 0) ) {
                        // FIRST ROW OR NEW ROW
                        
                        if  (($intCount != 0 && $breakpoint == 0))  {
                        // NEW ROW
                        $displaytext .= "</div>";
                        } 

                        $displaytext .= "<div class='special-display-" . $displayimages . "-image-grid'>";

                    } 
                    
                    switch($imagesize) {
                        case 'S':
                            $maxwidth = '100px';
                            break;
                        case 'M':
                            $maxwidth = '200px';
                            break;
                        
                        case 'L':
                            $maxwidth = '300px';
                            break;
                        
                        default;
                            $maxwidth = '200px';
                    }

                    $displaytext .= "<div>";
                    $displaytext .= "<img style='max-width: " . $maxwidth . ";' src='https://contentcafe2.btol.com/ContentCafe/Jacket.aspx?UserID=XXXX&Password=XXXX&Return=1&Type=" . $imagesize. "&Value=" . $isbn . "&erroroverride=1'>";
                    $displaytext .= "<br><br>";
                    $displaytext .= $titleauthorlink;

                    if ($summary == "Y") {
                        $displaytext .= "<br><br>" . $summarytext;
                    }

                    $displaytext .= "</div>";
                    
                    break;
                }
                // END IMAGE DISPLAY 3 OR 5

                $intCount++;
            }
        
        $displaytext .= "</div>";

        $testvalue = "";
        $isbn = "";
        $entries = "";
        $bibid = "";
        $title = "";
        $author = "";
        $arrfields = "";
        $key520 = "";
        $keysubf = "";
        $summary = "";
        $breakpoint = "";
        $encorelink = "";
        $imagetext = "";
        $titleauthorlink = "";
        $maxwidth = "";


}

$displaytext .= "</div>";

echo "<div style='margin-top:20px;margin-left:20px;'><input class='btn btn-secondary' type='button' value='Copy Code to Clipboard' onclick='copyDivToClipboard()' /></div>";

if (!empty($isbnerror)) {
    // Code to be executed if $isbnerror is empty
    echo "<br><div style='margin-left:30px;padding:20px;background-color:yellow;width:325px;'><b>The following ISBNs were not found.</b>" . $isbnerror . "</div><br><br>";
}

echo $displaytext;

$displaytext = htmlspecialchars($displaytext, ENT_QUOTES);
echo "<div id='a' style='color:white;'>" . $displaytext . "</div>";

?>

<script>
                function copyDivToClipboard() {
                    var range = document.createRange();
                    range.selectNode(document.getElementById("a"));
                    window.getSelection().removeAllRanges(); // clear current selection
                    window.getSelection().addRange(range); // to select text
                    document.execCommand("copy");
                    window.getSelection().removeAllRanges();// to deselect
                    alert("Code copied!");
                }
 </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
