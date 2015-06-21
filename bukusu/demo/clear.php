<a href="search.php">Return to search</a><br>
<?php
    $txt = "The current time: ". date("Y-m-d h:i:sa")."\r\n";
    echo "<br>".$txt;
    session_start();

    $reset = "";

    /*$_SESSION['gloss'] = $reset;
    $_SESSION['Root'] = $reset;
    $_SESSION['wordform'] = $reset;
    $_SESSION['pos'] = $reset;
    $_SESSION['numOfSyllables'] = $reset;
    $_SESSION['sigma1'] = $reset;
    $_SESSION['sigma2'] = $reset;
    $_SESSION['initialSound'] = $reset;
    $_SESSION['rootLength'] = $reset;
    $_SESSION['tonalPattern'] = $reset;
    $_SESSION['morpheme1'] = $reset;
    $_SESSION['morpheme2'] = $reset;*/
    		unset($_SESSION['gloss']);
			unset($_SESSION['Root']);
			unset($_SESSION['wordform']);
			unset($_SESSION['pos']);
			unset($_SESSION['numOfSyllables']);
			unset($_SESSION['sigma1']);
			unset($_SESSION['sigma2']);
			unset($_SESSION['initialSound']);
			unset($_SESSION['rootLength']);
			unset($_SESSION['tonalPattern']);
			unset($_SESSION['morpheme1']);
			unset($_SESSION['morpheme2']);
			unset($_SESSION['subpos']);
			unset($_SESSION['modifier1']);
			unset($_SESSION['modifier2']);
			
	
    /*$_SESSION['subpos'] = $reset;                
    $_SESSION['modifier1'] = $reset;
    $_SESSION['modifier2'] = $reset;
    
    $_SESSION['subpos'] = $reset; 
    $_SESSION['tonalPattern'] = $reset;
    
    $_SESSION['modifier1'] = $reset;
    $_SESSION['modifier2'] = $reset;*/

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

?>