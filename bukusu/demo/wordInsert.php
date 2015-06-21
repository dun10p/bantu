<html>
<head>
<title>Demo Word Insert</title>
<meta charset="UTF-8">


    <link rel="stylesheet" type="text/css" href="http://babbage.cs.missouri.edu/~cs3380s15grp15/keyboard/keyboard.css">
    
     <style>

         

        /*Search FieldSet*/
        fieldset.f1 { 
            position: absolute;
            width:325px;
            display: block;
            top: 50px;
            height:370px;
            border: 2px groove (internal value);
        }
        /*Options FieldSet*/
        fieldset.f2 { 
            
            position: absolute;
            width:325px;
            height:150px;
			left: -4px;
            top: 230px;
            display: block;
            border: 2px groove (internal value);
        }
        /*Phrase Data FieldSet*/
        fieldset.f3 { 
            
            position: absolute;
            width:325px;
            height:200px;
            left: 375px;
            top: 40px;
            display: block;
            border: 2px groove (internal value);
        }
        /*Grammar-Class FieldSet*/
         fieldset.f4 { 
            
            position: absolute;
            width:750px;
            height:275px;
            left: 365px;
            top: 100px;
            display: block;
            border: 2px groove (internal value);
        }
         /*Phrase checkbox*/
         input.phrase {
            position: absolute;
            left: 375px;
            top: 20px;
         }
         /*Phrase wording paragraph*/
         p.pos {
            position: absolute;
            left: 400px;
            top: 5px;
         }
        

        
    </style>
    <!--http://www.greywyvern.com/code/javascript/keyboard-->



</head>
<body style="background-color:lightgrey" onload="setState()">
<p>Insert a word using the form below.</p>
<form method='POST' action='../php/insertTest'>
				<fieldset class='f1'>
				<legend>Word Insert</legend>
				Word: <input type='text' name='wordForm'>
				<input type='hidden' name='table' value='words'/>
				<br><br>Definition: <input type='text' name='gloss'>
				<br><br>Root: <select name='rootID'>
				<?php
					//root to tie to word
					$rootQuery = "SELECT rootID, root FROM gp.root ORDER BY root ASC;";
					outputSelectOptions($rootQuery);
					function outputSelectOptions($query){
						include("../secure/dbConnect.php");
						$resource = pg_query($dbconn, $query);
						echo "'<option value=0></option>'+";
						while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[1]."</option>'+";
						}
					}
				?>
				</select>
				<br><br>Part of Speech: <select name='pos'>
				<?php
					$posQuery = "SELECT pos FROM gp.words GROUP BY pos;";
					outputSelect1Col($posQuery);
					function outputSelect1Col($query){
						include("../secure/dbConnect.php");
						$resource = pg_query($dbconn, $query);
						echo "'<option value=''></option>'+";
						while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[0]."</option>'+";
						}
					}			
					?>
				</select>
				<br><br>Sub Part of Speech: <select name='subPos'>
				<?php
					$partOfSpeech = $_POST['pos'];
					$posQuery = "SELECT subPos FROM gp.words GROUP BY subPos;";
					outputSelect1Col($posQuery);
				?>
				</select>
				<br><br>Number of Syllables  
                <input type='number' name='numOfSyllables' min='1' max='10'>
                <br><br>Syllable1
                Syllable2<br>
                <select name='sigma1'>
                    <option selected='' value=''>
                    </option>
                    <option value='2'>Long</option>
                    <option value='1'>Short</option>
                </select>

                <select name='sigma2'>
                    <option selected='' value=''>
                    </option>
                    <option value='2'>Long</option>
                    <option value='1'>Short</option>
                </select><br>
                <br>Stem-Initial sound<br>
                <select name='initialsound'>
                    <option selected='' value=''>
                    </option>
				<?php
					$query = 'SELECT * FROM gp.initial_sound';
					include("../secure/dbConnect.php");
					//query to get all the results from initial sound
					$resource = pg_query($dbconn, $query);
					while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "<option value=".$record[0].">".$record[1]."</option>";
					}
				?>
                </select>
				<br><br><input type='submit' value='Submit'/>
				</fieldset>
				</form>
			
</body>