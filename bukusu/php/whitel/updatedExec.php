<?php

session_start();

//database connection, whenever we have the final build ready
//$dbconn = pg_connect(........);
include '../secure/dbConnect.php';


if (isset($_POST['submit'])) {
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
            } else
 
            break;
        case 'lexical':
            if ($_POST['partOfSpeech'] != NULL){
                $_SESSION['pos'] = $_POST['partOfSpeech'];
            }
            //Num of Syllables/count for root
            if ($_POST['quantity'] != NULL){        
                $_SESSION['numOfSyllables'] = $_POST['quantity'];
                //$countForRoot = $_POST['countRoot'];
            }
            //Syl1
            if ($_POST['syllable1'] != NULL){
                $syl1 = $_POST['syllable1'];
                $_SESSION['sigma1'] = $syl1;
            }
            //Syl2
            if ($_POST['syllable2'] != NULL){
                $syl2 = $_POST['syllable2'];
               $_SESSION['sigma2'] = $syl2;
            }
            //Stem-Initial Sound
            if ($_POST['stemInitial'] != NULL){
                $stemInitial = $_POST['stemInitial'];
                $_SESSION['initialSound'] = $stemInitial;
            }
    
            break;
        default:
            break;
    }
    //switch statement for the grammar class
    switch ($_POST['ctrGrammar']) {
        case 'verb':
            if ($toneClass != null) {                            
                $toneClass = $_POST['toneClass'];
                $_SESSION['tonalPattern'] = $toneClass;
            }
            
            //verbOption
            if ($_POST['verbOptions'] != null){
                $verbOption = $_POST['verbOptions'];
               
               

            if ($verbOption[0] == 'reflexive') {
                $reflexive = $verbOption[0];
            }
            if ($verbOption[1] == 'reciprocal') {
                $reciprocal = $verbOption[1];
            }



            if ($_POST['detailsgiven'] != '') {
                $phrase = 'verb';
                //array order if filled: progressive, adverb, passive, perfective
                $verbPhrases = includePhrases($phrase);
            }
            }
            break;
        case 'noun':
            $subpos;
            
            if ($_POST['singular'] != null && $_POST['plural'] != null){
                $subpos[0] = $_POST['singular'];
                $subpos[1] = $_POST['plural'];
            } else if ($_POST['singular'] != null){
                $subpos[0] = $_POST['singular'];
            } else if ($_POST['plural'] != null){
                $subpos[0] = $_POST['plural'];
            }
            
            if ($subpos != null){
                           $_SESSION['subpos'] = $subpos; 
            }
/*
            $singular = $_POST['singular'];
            $txt = 'singular: ' . $singular . "\r\n";
            echo "<br>" . $txt;
            fwrite($myfile, $txt);

            $plural = $_POST['plural'];
            $txt = 'plural: ' . $plural . "\r\n";
            echo "<br>" . $txt;
            fwrite($myfile, $txt);
 * 
 */
            if ($_POST['detailsgiven'] != null) {
                $phrase = 'noun';
                //array order if filled: demonstrative, possessive, interrogatives, adjectives, numerals
                $nounPhrases = includePhrases($phrase);
            }
            break;
        default:
            break;
    }
  

    $getFormData = $_SESSION;
}

function includePhrases($phrase) {

    //$values[]; 

    if ($phrase == 'verb') {
        /*
          Needs to be combined into a tense/aspect variable
          after the form is changed

          $tense = $_POST['Tense'];
          $aspect = $_POST['Aspect'];

         */
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
        $_SESSION['modifier2'] = $modifier2;
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
         */

        $nounPhrases = $_POST['nounPhrase'];
  foreach ($nounPhrases as $key => $value) {
        echo "nounPhrase KEY --> $key  Value --> $value<br>";    
        }
/*
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
 */
        $_SESSION['modifier1'] = $nounPhrases;
        $_SESSION['modifier2'] = $nounPhrases;
    }


    //return $values;
}

include '../php/dynamicQuerySearch-v2.php';
?>