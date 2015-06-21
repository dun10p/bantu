<?
	session_start();

	//database connection, whenever we have the final build ready
	//$dbconn = pg_connect(........);

    $myfile = fopen("newfile.txt", "w"); //or die($hint = "Unable to open file!"); 

    $txt = "The current time: ". date("Y-m-d h:i:sa")."\r\n";
    fwrite($myfile, $txt);
    
	if (isset($_POST['submit'])){
		//switch statement for the first set of data
		//i.e. gloss search or lexical properties
		switch ($_POST['ctrSearch']){
			case 'glossary':
				if ($_POST['searchEnglish'] != NULL){
					$glossaryValue = htmlspecialchars($_POST['searchEnglish']);
					$_SESSION['Gloss'] = $glossaryValue;
                    $txt = "glosssaryValue-English: ". $glossaryValue."\r\n";
                    echo "<br>".$txt;
                    fwrite($myfile, $txt);
                }
				elseif ($_POST['searchBukusu'] != NULL){
					$glossaryValue = htmlspecialchars($_POST['searchBukusu']);
					$_SESSION['Root'] = $glossaryValue;
					$_SESSION['wordform'] = $glossaryValue;
                    $txt =' glosssaryValue-Bukusu: '.$glossaryValue."\r\n";
                    echo "<br>".$txt;
                    fwrite($myfile, $txt);
                }
				else 
					$txt = "Error: no glossary values were specified"."\r\n";
                    echo "<br>".$txt;
                    fwrite($myfile, $txt);
				break;
			case 'lexical':
				//Num of Syllables/count for root
				$numOfSyllables = $_POST['quantity'];
				$_SESSION['numOfSyllables'] = $numOfSyllables;
				$countForRoot = $_POST['countRoot'];
				//Syl1
				$syl1 = $_POST['syllable1'];
				$_SESSION['sigma1'] = $syl1;
				//Syl2
				$syl2 = $_POST['syllable2'];
				$_SESSION['sigma2'] = $syl2;
				//Stem-Initial Sound
				$stemInitial = $_POST['stemInitial'];
				$_SESSION['initialSound'] = $stemInitial;

                $txt =' pos: '.$pos."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
                $txt=' numOfSyllables: '.$numOfSyllables."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
                $txt=' countForRoot: '.$countForRoot."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
                $txt=' syl1: '.$syl1."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
                echo '<br> syl2: '.$syl2."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
                echo '<br> stemInital: '.$stemInitial."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
				break;
			default:
				break;
		}
		//switch statement for the grammar class
		switch ($_POST['ctrGrammar']){
			case 'verb':
				$toneClass = $_POST['toneClass'];
				$_SESSION['tonalPattern'] = $toneClass;
                $txt='toneClass: '.$toneClass."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
				$verbOption = $_POST['verbOptions'];
                foreach($_POST['verbOptions'] as $verbtxt){
                    $txt='For verbOptions: '.$verbtxt."\r\n";
                    echo "<br>".$txt;
                    fwrite($myfile, $txt);
                }
                
				if($verbOption[0] == 'reflexive'){
					$reflexive = $verbOption[0];
                    $txt='reflexive: '.$reflexive."\r\n";
                    echo "<br>".$txt;
                    fwrite($myfile, $txt);
                }
				if($verbOption[1] == 'reciprocal'){
					$reciprocal = $verbOption[1];
                    $txt='reciprocal: '.$reciprocal."\r\n";
                    echo "<br>".$txt;
                    fwrite($myfile, $txt);
                }
                
                $txt='detailsgiven: '.$_POST['detailsgiven']."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
                
                //Issues 
				if($_POST['detailsgiven'] != ''){
					$phrase = 'verb';
					//array order if filled: progressive, adverb, passive, perfective
					$verbPhrases = includePhrases($phrase);
                    $txt='verbPhrase: '.$verbPhrases."\r\n";
                    echo "<br>".$txt;
                    fwrite($myfile, $txt);
				}
				break;
			case 'noun':

				$subpos = array (
					"Singular" => $singular,
					"Plural" => $plural,
					);

				$singular = $_POST['singular'];
                $txt='singular: '.$singular."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);

				$plural = $_POST['plural'];
                $txt='plural: '.$plural."\r\n";
                echo "<br>".$txt;
                fwrite($myfile, $txt);
				if($_POST['detailsgiven'] != 'FALSE'){
					$phrase = 'noun';
					//array order if filled: demonstrative, possessive, interrogatives, adjectives, numerals
					$nounPhrases = includePhrases($phrase);
                    $txt='nounPhrase: '.$nounPhrases."\r\n";
                    echo "<br>".$txt;
                    fwrite($myfile, $txt);
                }
                $_SESSION['subPos'] = $subpos;
				break;
			default:
				break;
		}
        fclose($myfile);
	}

function includePhrases($phrase){

        //$values[]; 

		if($phrase == 'verb'){
/*
			Needs to be combined into a tense/aspect variable  
			after the form is changed

			$tense = $_POST['Tense'];
			$aspect = $_POST['Aspect'];

*/
			$verbPhrases = $_POST['verbPhrases'];

			if($verbPhrases[0] == 'Progressive'){
				$progressive = $verbPhrases[0];
            }
			if($verbPhrases[1] == 'Adverbs'){
				$adverbs = $verbPhrases[1];
            }
			if($verbPhrases[2] == 'Passive'){
				$passive = $verbPhrases[2];
            }
			if($verbPhrases[3] == 'Perfective'){
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
		}
		elseif($phrase == 'noun'){
/*			$nounPhrases = array();
			
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

			if($nounPhrases[0] == 'Demonstratives')
				$demonstrative = $nounPhrases[0];

			if($nounPhrases[1] == 'Possessives')
				$possessive = $nounPhrases[1];

			if($nounPhrases[2] == 'Interrogatives')
				$interrogative = $nounPhrases[2];

			if($nounPhrases[3] == 'Adjectives')
				$adjective == $nounPhrases[3];

			if($nounPhrases[4] == 'Numerals')
				$numeral == $nounPhrases[4];

			$modifier1 = array(
					"Demonstratives" => $demonstrative,
					"Possessives" => $possessive,
					"Interrogatives" => $interrogative,
					"Adjectives" => $adjective,
					"Numerals" => $numeral,
				);
			$_SESSION['modifier1'] = $modifier1;

			$modifier2 = array(
					"Demonstratives" => $demonstrative,
					"Possessives" => $possessive,
					"Interrogatives" => $interrogative,
					"Adjectives" => $adjective,
					"Numerals" => $numeral,
				);
			$_SESSION['modifier2'] = $modifier2;
		}
                

		//return $values;
}

