<!DOCTYPE html>
<html>
<head>
<title>Demo Insert form</title>
    <meta charset="UTF-8">


    <link rel="stylesheet" type="text/css" href="http://babbage.cs.missouri.edu/~cs3380s15grp15/keyboard/keyboard.css">
    
     <style>

         

        /*Search FieldSet*/
        fieldset.f1 { 
            position: absolute;
            width:325px;
            display: block;
            top: 110px;
            height:200px;
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
  



</head>
<body style="background-color:lightgrey" onload="setState()">
    
    <p>Words and phrases can be inserted below. Please note that all words involved in a phrase must already have been inserted
	<br>Word ID's must be known for phrase insertion</p>




    <input type="radio" name="ctrInsert" value="word" onclick="insertClick(this);" checked/>Word Insertion
    <input type="radio" name="ctrInsert" value="phrase" onclick="insertClick(this);" />Phrase Insertion

         
    

    


<script charset="UTF-8">


    function toggleDisabled(_checked) {
        document.getElementById('tb1').disabled = _checked ? false : true;
    }
    function setState() {
        //document.getElementById('tb1').disabled = true;
        setInsert("word");
        //setGrammar("verb");
    }



    //http://stackoverflow.com/questions/8838648/onchange-event-handler-for-radio-button-input-type-radio-doesnt-work-as-one

            

    // Handle radio button selection change
    function insertClick(ctrInsert) {
        setInsert(ctrInsert.value);
    }
    function grammarClick(ctrGrammar) {
        setGrammar(ctrGrammar.value);
    }

    //http:/ / www.w3schools.com / jsref / met_node_appendchild.asp
    //http://www.greywyvern.com/code/javascript/keyboard
    function loadKeyboard() {

        //console.log('Append Item');
        var foo = document.createElement('input');
        document.getElementById('Insert').appendChild(foo);
        VKI_attach(foo);
    }

    // Update displayed data in Search fieldset
    function setInsert(insertSelect) {
        if (insertSelect == "word") {
            document.getElementById('Insert').innerHTML = "<form name='insertRoot' method='POST' action='../php/insertTest.php'>"+
		"<fieldset id='Insert class='f1'>"+
        "<legend>Root Insert</legend>"+
				"Root: <input type='text' name='root'>"+
                "<br><br>Number of syllables: <input type='number' name='rootLength' min='1' max='5'>"+
				"<br><br>Syllable1"+
                "Syllable2<br>"+
                "<select name='sigma1'>"+
                    "<option selected='' value='0'>"+
                    "</option>"+
                    "<option value='2'>Long</option>"+
                    "<option value='1'>Short</option>"+
                "</select>"+
				"<input type='hidden' name='table' value='root'/>"+
                "<select name='sigma2'>"+
                    "<option selected='' value='0'>"+
                    "</option>"+
                    "<option value='2'>Long</option>"+
                    "<option value='1'>Short</option>"+
                "</select><br>"+
                "<br>Stem-Initial sound<br>"+
                "<select name='initialSound'>"+
                    "<option selected='' value=''>"+
                    "</option>"+

				<?php
					$query = 'SELECT * FROM gp.initial_sound';
					include("../secure/dbConnect.php");
					//query to get all the results from initial sound
					$resource = pg_query($dbconn, $query);
					while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[1]."</option>'+";
					}
				?>
				
				"</select>"+
				"<input type='submit' name='submit' value='Submit' />"+
				"</fieldset>"+
				"</form>";
				<!--
				<form method='POST' action='../php/insertTest'>
				<fieldset class='f2'>				
				<legend>Word Insert</legend>
				Word: <input type='text' name='wordForm'>
				<input type='hidden' name='table' value='words'/>
				<br><br>Definition: <input type='text' name='gloss'>
				<br><br>Part of Speech: <select name='pos'>
						<option value='noun'>Noun</option>
						<option value='verb'>Verb</option>
					</select>"+
				<br><br>Sub Part of Speech: <select name='subPos'>
						</select>
				</fieldset>
				<br>
				<input type='submit' value='Submit'/>
				</form> -->
			
		}
        if (insertSelect == "phrase") {
            document.getElementById('Insert').innerHTML =
                "<form method='POST' action='../php/insertTest'>"+
				"<legend>Phrase Insertion</legend>"	+
				"<input type='hidden' name='table' value='phrasal_data'/>"+
				"</form>";
        }
    }
    // Update displayed data in grammar-class fieldset
    function setGrammar(grammarSelect) {
        if (grammarSelect == "verb") {
            document.getElementById('options').innerHTML =
                " <legend>Verb options:</legend>" +
                "<input type='radio' name='tone-class' value='high'>High" +
                "<input type='radio' name='tone-class' value='low'>Low<br>" +
                //changed name to verbOptions[] to be able to send the checkbox data serverside with an array
                "<input type='checkbox' name='verbOptions[]' value='reflexive' />Reflexive<br>" +
                "<input type='checkbox' name='verbOptions[]' value='reciprocal' />Reciprocal<br>";

            document.getElementById('tb1').innerHTML =
                "<legend>Verb Phrase:</legend>" +

            "Tense<br>" +
            "<select name='Tense'>" +
                "<option selected='' value=''>" +
                "</option>" +
                "<option value='bedroom'>Bedroom</option>" +
                "<option value='media-center'>Media-Center</option>" +
            "</select>" +
            "<br>Aspect<br>" +
            "<select name='Aspect'>" +
                "<option selected='' value=''>" +
                "</option>" +
                "<option value='bedroom'>Bedroom</option>" +
                "<option value='media-center'>Media-Center</option>" +
            "</select><br>" +
            //changed name to verbPhrase[] to be able to send the checkbox data
            //server side with an array

            //reference: http://stackoverflow.com/questions/7654155/get-post-data-from-multiple-checkboxes
            "<input type='checkbox' name='verbPhrase[]' value='Progressive' />Progressive<br>" +
            "<input type='checkbox' name='verbPhrase[]' value='Adverbs' />Adverb's<br>" +
            "<input type='checkbox' name='verbPhrase[]' value='Passive' />Passive<br>" +
            "<input type='checkbox' name='verbPhrase[]' value='Perfective' />Perfective<br>";
        }
        if (grammarSelect == "noun") {
            document.getElementById('options').innerHTML =
                " <legend>Noun options:</legend>" +
                "Noun class of Singular<br>" +
                // Weird bug on toggle blank data
                "<select name='singular'>" +
                    "<option selected='' value=''>" +
                    "</option>" +
                    "<option value='bedroom'>Bedroom</option>" +
                    "<option value='media-center'>Media-Center</option>" +
                "</select>" +
                "<br>Noun class of Plural<br>" +
                "<select name='plural'>" +
                    "<option selected='' value=''>" +
                    "</option>" +
                    "<option value='bedroom'>Bedroom</option>" +
                    "<option value='media-center'>Media-Center</option>" +
                "</select><br>";

            document.getElementById('tb1').innerHTML =
                "<legend>Noun Phrase:</legend>" +
                //changed name to nounPhrase[] to be able to send the checkbox data 
                //server side with an array

                //reference: http://stackoverflow.com/questions/7654155/get-post-data-from-multiple-checkboxes
                "<input type='checkbox' name='nounPhrase[]' value='demonstratives' />Demonstratives<br>" +
                "<input type='checkbox' name='nounPhrase[]' value='possessives'/>Possessives<br>" +
                "<input type='checkbox' name='nounPhrase[]' value='interrogatives'/>Interrogatives<br>" +
                "<input type='checkbox' name='nounPhrase[]' value='adjectives'/>Adjectives<br>" +
                "<input type='checkbox' name='nounPhrase[]' value='numerals'/>Numerals<br>";
        }
    }*/
       
</script>

    <script type="text/javascript" src="http://babbage.cs.missouri.edu/~cs3380s15grp15/keyboard/keyboard.js" charset="UTF-8"></script>
</body>
</html> 
