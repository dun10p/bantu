<a href="search.php">Return to search</a><br>
<?php

session_start();

//database connection, whenever we have the final build ready
//$dbconn = pg_connect(........);
include '../secure/dbConnect.php';

$debug = TRUE;

if(debug == TRUE){
    $myfile = fopen("newfile.txt", "w"); //or die($hint = "Unable to open file!"); 

    $txt = "The current time: ". date("Y-m-d h:i:sa")."\r\n";
    echo "<br>".$txt;
    fwrite($myfile, $txt);
    }

// Ben commented this out this was what was breaking my ajax request.
// Please tell me why this is needed?
/// http://www.paulund.co.uk/use-php-to-detect-an-ajax-request
// https://css-tricks.com/snippets/php/detect-ajax-request/
//if (isset($_POST['submit'])) {

//if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){


   
	$sub = $_SESSION;
   
    //switch statement for the first set of data
    //i.e. gloss search or lexical properties
    switch ($_POST['ctrSearch']) {
        case 'glossary':
            if ($_POST['searchEnglish'] != NULL) {
                $glossaryValue = htmlspecialchars($_POST['searchEnglish']);
                $_SESSION['gloss'] = $glossaryValue;
            } elseif ($_POST['searchBukusu'] != NULL) {
                $glossaryValue = htmlspecialchars($_POST['searchBukusu']);
                $_SESSION['Root'] = $glossaryValue;
                $_SESSION['wordform'] = $glossaryValue;
            } else{
            $glossaryValue = "ctrSearch didnt work and else'd";
            }
            
            if($debug == TRUE){
                $txt='POST_ctrSearch:'.$_POST['ctrSearch']."\r\n";
                fwrite($myfile, $txt);
                $txt = "glosssaryValue:". $glossaryValue."\r\n";
                //echo "<br>".$txt;
                fwrite($myfile, $txt);
                $txt = 'searchEnglish:'.$_POST['searchEnglish']."\r\n";
                fwrite($myfile, $txt);
                $txt='searchBukusu:'.$_POST['searchBukusu']."\r\n";
                fwrite($myfile, $txt);
                $txt='session_gloss:'.$_SESSION['gloss']."\r\n";
                fwrite($myfile, $txt);
                $txt='session_Root:'.$_SESSION['Root']."\r\n";
                fwrite($myfile, $txt);
                $txt='session_wordform:'.$_SESSION['wordform']."\r\n";
                fwrite($myfile, $txt);
            }
 
            break;
        case 'lexical':
            if ($_POST['partOfSpeech'] != NULL){
                $_SESSION['pos'] = $_POST['partOfSpeech'];
            }
            //Num of Syllables/count for root
            if ($_POST['quantity'] != NULL){        
                $_SESSION['numOfSyllables'] = $_POST['quantity'];
            }
            //Syl1
            if ($_POST['syllable1'] != NULL){                
                $_SESSION['sigma1'] = $_POST['syllable1'];
            }
            //Syl2
            if ($_POST['syllable2'] != NULL){
               $_SESSION['sigma2'] = $_POST['syllable2'];
            }
            //Stem-Initial Sound
            if ($_POST['stemInitial'] != NULL){
                $_SESSION['initialSound'] = $_POST['stemInitial'];
            }
            
            if ($_POST['countRoot'] != NULL){
                $_SESSION['rootLength'] = $_POST['stemInitial'];
            }
    
            break;
        default:
            break;
    }
    //switch statement for the grammar class
    switch ($_POST['ctrGrammar']) {
        case 'verb':


            //verbOption
            if ($_POST['toneClass'] != null){
                $_SESSION['tonalPattern'] = $_POST['toneClass'];
            }
               
            if ($_POST['verbOptions'] != null){
                foreach ($_POST['verbOptions'] as $key => $value) {
                    echo "<hr><br>Verb Options: key -> $key  value -> $value<br>";
                }
                $_SESSION['morpheme1'] = $_POST['verbOptions'];
                $_SESSION['morpheme2'] = $_POST['verbOptions'];
            }   

            if ($_POST['Tense/Aspect'] != null){
                $_SESSION['subpos'] = $_POST['Tense/Aspect'];                
            }

            
            //This looks like it
            if (isset($_POST['detailsgiven'])) {
                $_SESSION['modifier1'] = $_POST['verbPhrase'];
                $_SESSION['modifier2'] = $_POST['verbPhrase'];
            }
            if($debug == TRUE){
                $txt='detailsGiven checkbox'.$_POST['detailsgiven']."\r\n";
                fwrite($myfile, $txt);
                $txt='verbPhrase1:'.$_POST['verbPhrase']."\r\n";
                fwrite($myfile, $txt);
                $txt='verbPhrase2:'.$_POST['verbPhrase']."\r\n";
                fwrite($myfile, $txt);
                $txt='detailsgiven_modifier1:'.$_SESSION['modifier1']."\r\n";
                fwrite($myfile, $txt);
                $txt='detailsgiven_modifier2:'.$_SESSION['modifier2']."\r\n";
                fwrite($myfile, $txt);
            }
            break;
        case 'noun':
            
            if ($_POST['subPos'] != null){
                $_SESSION['subpos'] = $_POST['subPos']; 
            }
            
            if ($_POST['tonalPattern'] != null) {                            
                $_SESSION['tonalPattern'] = $_POST['tonalPattern'];
            }
           

            if (isset($_POST['detailsgiven'])) {
                $_SESSION['modifier1'] = $_POST['nounPhrase'];
                $_SESSION['modifier2'] = $_POST['nounPhrase'];
            }
                if($debug == TRUE){
                $txt='subpos:'.$_SESSION['subpos']."\r\n";
                fwrite($myfile, $txt);
                $txt='tonalPattern:'.$_SESSION['tonalPattern']."\r\n";
                fwrite($myfile, $txt);
                $txt='modifier1'.$_SESSION['modifier1']."\r\n";
                fwrite($myfile, $txt);
                $txt='modifier2'.$_SESSION['modifier2']."\r\n";
                fwrite($myfile, $txt);
            }
            break;
        default:
            break;
    }
  

    $getFormData = $_SESSION;
//}

function setPos($ctrGrammar){
    
}
/*function includePhrases($phrase) {

    //$values[]; 

    if ($phrase == 'verb') {
        /*
          Needs to be combined into a tense/aspect variable
          after the form is changed

          $tense = $_POST['Tense'];
          $aspect = $_POST['Aspect'];

       
        $verbPhrases = $_POST['verbPhrases'];

        if ($verbPhrases[0] == 'Progressive') {
            $progressive = $verbPhrases[0];
        }
        if ($verbPhrases[1] == 'Adverbs') {
            $adverbs = $verbPhrases[1];
        }
        if ($verbPhrases[2] == 'Passive') {
            $passive = $verbPhrases[2];
        }
        if ($verbPhrases[3] == 'Perfective') {
            $perfective = $verbPhrases[3];
        }
        $modifier1 = array(
            "Progressive" => $progressive,
            "Adverbs" => $adverbs,
            "Passive" => $passive,
            "Perfective" => $perfective,
        );
        $_SESSION['modifier1'] = $modifier1;

        $modifier2 = array(
            "Progressive" => $progressive,
            "Adverbs" => $adverbs,
            "Passive" => $passive,
            "Perfective" => $perfective,
        );
     
        
        $verbPharases = $_POST['verbPhrase'];
         echo "verbPhrase KEY -->  Value --> $value<br>";
        foreach ($verbPhrases as $value) {
           
        }
        $_SESSION['modifier1'] = $verbPhrases;
        $_SESSION['modifier2'] = $verbPhrases;
    } elseif ($phrase == 'noun') {
        /* 			$nounPhrases = array();

          class nounVal{
          public $demonstrative;
          public $possessives;
          public $interrogatives;
          public $adjectives;
          public $numerals;

          }

          $values = new nounVal();

          $nounPhrase = $_POST['nounPhrases'];

          if($nounPhrases[0] != 'FALSE'){
          $values->demonstrative = $nounPhrases[0];
          }

          if($nounPhrases[1] != 'FALSE'){
          $values->possessives = $nounPhrases[1];
          }

          if($nounPhrases[2] != 'FALSE'){
          $values->interrogatives = $nounPhrases[2];
          }

          if($nounPhrases[3] != 'FALSE'){
          $values->adjectives = $nounPhrases[3];
          }

          if($nounPhrases[4] != 'FALSE'){
          $values->numerals = $nounPhrases[4];
          }
         

        $nounPhrases = $_POST['nounPhrase'];
  

        if ($nounPhrases[0] == 'Demonstratives')
            $demonstrative = $nounPhrases[0];

        if ($nounPhrases[1] == 'Possessives')
            $possessive = $nounPhrases[1];

        if ($nounPhrases[2] == 'Interrogatives')
            $interrogative = $nounPhrases[2];

        if ($nounPhrases[3] == 'Adjectives')
            $adjective == $nounPhrases[3];

        if ($nounPhrases[4] == 'Numerals')
            $numeral == $nounPhrases[4];

        $modifier1 = array(
            "Demonstratives" => $demonstrative,
            "Possessives" => $possessive,
            "Interrogatives" => $interrogative,
            "Adjectives" => $adjective,
            "Numerals" => $numeral,
        );
        

        $modifier2 = array(
            "Demonstratives" => $demonstrative,
            "Possessives" => $possessive,
            "Interrogatives" => $interrogative,
            "Adjectives" => $adjective,
            "Numerals" => $numeral,
        );
 * 
 
        $_SESSION['modifier1'] = $nounPhrases;
        $_SESSION['modifier2'] = $nounPhrases;
 * 
 
    }


    //return $values;
}
 * 
 */
 if($debug == TRUE){
    fclose($myfile);
}

include '../php/dynamicQuerySearch-v2.php';
?>