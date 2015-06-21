<?php
if(!isset($_SESSION['username'])){
	header('Location:https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html');
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Insert form</title>
    <meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="keyboard/keyboard.css">
<link href="Site.css" rel="stylesheet">
    
<!--http://www.greywyvern.com/code/javascript/keyboard-->

</head>
<body onload="setState()">
 
<nav id="nav01"></nav>

<nav id="nav02"></nav>

<div id="main">

    <p>Roots, words and phrases can be inserted below. Please note that roots must be inserted before words and all words involved in a phrase must already have been inserted.</p>

    <input type="radio" name="ctrInsert" value="root" onclick="insertClick(this);" checked/>Root Insertion
	<input type="radio" name="ctrInsert" value="word" onclick="insertClick(this);"/>Word Insertion
    <input type="radio" name="ctrInsert" value="phrase" onclick="insertClick(this);" />Phrase Insertion
    <form name="insertRoot" method="POST" action="insertTest.php">
    <fieldset id="Insert" class="fInsert">
        

    
<footer id="foot01"></footer>
</div>

<script charset="UTF-8">


    
    function setState() {
        //document.getElementById('tb1').disabled = true;
        setInsert("root");
        
    }

    //http://stackoverflow.com/questions/8838648/onchange-event-handler-for-radio-button-input-type-radio-doesnt-work-as-one

    // Handle radio button selection change
    function insertClick(ctrInsert) {
        setInsert(ctrInsert.value);
    }
    function grammarClick(ctrGrammar) {
        setGrammar(ctrGrammar.value);
    }



    // Update displayed data in Search fieldset
    function setInsert(insertSelect) {

        if (insertSelect == "root") {
            document.getElementById('Insert').innerHTML =
                "<legend>Root Insert</legend>" +
				"Root:";
                //<input type='text' name='root'>" +
            loadKeyboard("Insert", "root");
            
            //http://stackoverflow.com/questions/18539992/innerhtml-append-instead-of-replacing
            //I dont like this but it works
            var newElement = document.createElement('div');
            newElement.innerHTML = 
                "<br><br>Number of syllables: <input type='number' name='rootLength' min='1' max='5'>"+
				"<br><br>Syllable1  " +
                "Syllable2<br>" +
                "<select name='sigma1'>" +
                    "<option selected='' value='0'>" +
                    "</option>" +
                    "<option value='2'>Long</option>" +
                    "<option value='1'>Short</option>" +
                "</select>  " +
				"<input type='hidden' name='table' value='root'/>"+
                "<select name='sigma2'>" +
                    "<option selected='' value='0'>" +
                    "</option>" +
                    "<option value='2'>Long</option>" +
                    "<option value='1'>Short</option>" +
                "</select><br>" +
                "<br>Stem-Initial sound<br>" +
                "<select name='initialSound'>" +
                    "<option selected='' value=''>" +
                    "</option>" +

				<?php
					$query = 'SELECT * FROM gp.initial_sound';
					include("secure/dbConnect.php");
					//query to get all the results from initial sound
					$resource = pg_query($dbconn, $query);
					while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[1]."</option>'+";
					}
				?>
				"</select>"+
				"<br><br><input type='submit' name='submit' value='Submit' />"+
				"</fieldset>"+
				"</form>";
            document.getElementById('Insert').appendChild(newElement);
                
	
                
		}
		if(insertSelect == "word"){
			document.getElementById('Insert').innerHTML =
				"<p>Insert a word using the form below.</p>"+
				"<legend>Word Insert</legend>"+
				"Word: ";//<input type='text' name='wordForm'>";
				loadKeyboard("Insert", "wordForm");
				var newElement = document.createElement('div');
				newElement.innerHTML = 
				"<input type='hidden' name='table' value='words'/>"+
				"<br><br>Definition: <input type='text' name='gloss'>"+
				"<br><br>Root: <select name='rootID'>"+
				<?php
					//root to tie to word
					$rootQuery = "SELECT rootID, root FROM gp.root ORDER BY root ASC;";
					outputSelectOptions($rootQuery);
				?>
				"</select>"+
				"<br><br>Morpheme 1 :<select name='morpheme1'>"+
				<?php
					//possible morphemes
					$morphemeQuery = "SELECT morphemeId, form, description FROM gp.allomorphs ORDER BY form ASC;";
					outputMorphemeOptions($morphemeQuery);
					function outputMorphemeOptions($query){
						include("secure/dbConnect.php");
						$resource = pg_query($dbconn, $query);
						echo "'<option value=0></option>'+";
						while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[1]."  :  ".$record[2]."</option>'+";
						}
					}
				?>
				"</select>"+
				"<br><br>Morpheme 2 :<select name='morpheme2'>"+
				<?php
					//possible morphemes
					
					outputMorphemeOptions($morphemeQuery);

				?>
				"</select>"+
				"<br><br>Part of Speech: <select name='pos'>"+
				<?php
					$posQuery = "SELECT pos FROM gp.words GROUP BY pos;";
					outputSelect1Col($posQuery);
	
					?>
				"</select>"+
				"<br><br>Sub Part of Speech: <select name='subPos'>"+
				<?php
					$partOfSpeech = $_POST['pos'];
					$posQuery = "SELECT subPos FROM gp.words GROUP BY subPos ORDER BY subPos ASC;";
					outputSelect1Col($posQuery);
				?>
				"</select>"+
				"<br><br>Number of Syllables: "+  
                "<input type='number' name='numOfSyllables' min='1' max='10'>"+
                "<br><br>Syllable1"+
                "Syllable2<br>"+
                "<select name='sigma1'>"+
                "    <option selected='' value=''>"+
                "    </option>"+
                "    <option value='2'>Long</option>"+
                "    <option value='1'>Short</option>"+
                "</select>  "+

                "<select name='sigma2'>"+
                 "   <option selected='' value=''>"+
                 "   </option>"+
                 "   <option value='2'>Long</option>"+
                    "<option value='1'>Short</option>"+
                "</select><br>"+
                "<br>Stem-Initial sound<br>"+
                "<select name='initialsound'>"+
                    "<option selected='' value=''>"+
                    "</option>"+
				<?php
					$query = 'SELECT * FROM gp.initial_sound';
					include("secure/dbConnect.php");
					//query to get all the results from initial sound
					$resource = pg_query($dbconn, $query);
					while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[1]."</option>'+";
					}
				?>
                "</select>"+
				"<br><br>Tone class: "+
				"<select name = 'tonalpattern'>"+
				<?php
					$toneClassQuery = "SELECT tonalpattern FROM gp.words GROUP BY tonalpattern;";
					outputSelect1Col($toneClassQuery);
				?>
				"</select>"+
				"<br><br><input type='submit' value='Submit'/>"+
				"</fieldset>"+
				"</form>";
			document.getElementById('Insert').appendChild(newElement);
		}
        if (insertSelect == "phrase") {
            document.getElementById('Insert').innerHTML =
                "<form method='POST' action='insertTest.php'>"+
				"<legend>Phrase Insertion</legend>"	+
				"<input type='hidden' name='table' value='phrasal_data'/>" +
				"Bukusu phrase:";
            //    <input type='text' name='phraseForm'>";
                
            loadKeyboard('Insert', 'phraseForm');
            
            var newElement = document.createElement('div');
            newElement.innerHTML = 
            
				"<br>Definition: <input type='text' name='definition'>"+
				"<br><br><table>"+
				
				<?php
					echo "\"<br><tr><td>Noun 1</td><td>Noun 2</td><td>Noun 3</td><td>Verb 1</td><td>Verb 2</td><td>Verb 3</td></tr>\"+";
					$verbQuery = "SELECT wordID, wordForm FROM gp.words WHERE pos = 'Verb' ORDER BY wordForm DESC;";
					$nounQuery = "SELECT wordID, wordForm FROM gp.words WHERE pos = 'Noun' ORDER BY wordForm DESC;";
					
					echo "\"<tr><td><select name='noun1'>\" +";
					outputSelectOptions($nounQuery);
					echo "'</select></td>' +";
					
					echo "\"<td><select name='noun2'>\" +";
                    outputSelectOptions($nounQuery);
					echo "'</select></td>' +";
					
					echo "\"<td><select name='noun3'>\" +";
					outputSelectOptions($nounQuery);
					echo "'</select></td>' +";

					echo "\"<td><select name='verb1'>\" +";
					outputSelectOptions($verbQuery);
					echo "'</select></td>' +";

					echo "\"<td><select name='verb2'>\" +";
					outputSelectOptions($verbQuery);
					echo "'</select></td>' +";	

					echo "\"<td><select name='verb3'>\" +";
					outputSelectOptions($verbQuery);
					echo "'</select></td></tr>' +";	
					
					function outputSelectOptions($query){
						include("secure/dbConnect.php");
						$resource = pg_query($dbconn, $query);
						echo "'<option value=0></option>'+";
						while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[1]."</option>'+";
						}
					}
				?>
				"</table>"+
				"<br>Number of modifiers: <input type='number' name ='numModifiers' min='0' max='2'/>"+
				"      <select name='modifier1'>"+
				<?php
					$modifierQuery='SELECT * FROM gp.modifiers;';
					outputSelect1Col($modifierQuery);
					function outputSelect1Col($query){
						include("secure/dbConnect.php");
						$resource = pg_query($dbconn, $query);
						echo "'<option value=0></option>'+";
						while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[0]."</option>'+";
						}
					}
				?>
				"</select>"+
				"  <select name='modifier2'>"+
				<?php
					$modifierQuery='SELECT * FROM gp.modifiers;';
					outputSelect1Col($modifierQuery);
				?>
				"</select>"+
				"<br><br><input type='submit' value='Insert Phrase'/>"+
				
				"</form>";
            document.getElementById('Insert').appendChild(newElement);
        }
    }
    
    
       
</script>
    <script src="Script.js"></script>
    <script type="text/javascript" src="keyboard/keyboard.js" charset="UTF-8"></script>
</body>
</html> 
