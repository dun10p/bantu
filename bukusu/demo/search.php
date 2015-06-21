<!DOCTYPE html>
<html>
<head>
<title>Demo search form</title>
    <meta charset="UTF-8">


    <link rel="stylesheet" type="text/css" href="http://babbage.cs.missouri.edu/~cs3380s15grp15/keyboard/keyboard.css">
    
     <style>

         
         /*this is how to comment out section of code
        body{
            width:500px;
        }*/
        
        /*Search FieldSet*/
        fieldset.f1 { 
            position: absolute;
            width:325px;
            display: block;
            top: 100px;
            height:275px;
            border: 2px groove (internal value);
        }
        /*Options FieldSet*/
        fieldset.f2 { 
            
            position: absolute;
            width:325px;
            height:200px;
            left: 15px;
            top: 40px;
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
        
        /*$DarkBrown: #292321;

        $Orange: #CC3300;

        div {
            margin:0 0 0.75em 0;
        }

        input[type="radio"] {
            display:none;
        }
        input[type="radio"] + label {
            color: #292321;
            font-family:Arial, sans-serif;
            font-size:14px;
        }
        input[type="radio"] + label span {
            display:inline-block;
            width:19px;
            height:19px;
            margin:-1px 4px 0 0;
            vertical-align:middle;
            cursor:pointer;
            -moz-border-radius:  50%;
            border-radius:  50%;
        }

        input[type="radio"] + label span {
             background-color:#292321;
        }

        input[type="radio"]:checked + label span{
             background-color:#CC3300;
        }

        input[type="radio"] + label span,
        input[type="radio"]:checked + label span {
          -webkit-transition:background-color 0.4s linear;
          -o-transition:background-color 0.4s linear;
          -moz-transition:background-color 0.4s linear;
          transition:background-color 0.4s linear;
        }
        */
        /*ul#menu {
            padding: 0;
            margin-bottom: 11px;
        }

        ul#menu li {
            display: inline;
            margin-right: 3px;
        }

        ul#menu li a {
            background-color: #ffffff;
            padding: 5px 10px;
            text-decoration: none;
            color: #696969;
            border-radius: 4px 4px 0 0;
        }*/
        
    </style>
    <!--http://www.greywyvern.com/code/javascript/keyboard-->



</head>
<?php
unset($_POST);
?>
<body style="background-color:lightgrey" onload="setState()">
    
    <p>You can ether run a Glossary Search or a Lexical Properties Search; only the selected tab Search will execute.</p>
    <!--nav id="nav01"></nav-->

    <!--div>
        <input type="radio" id="radio01" name="radio" />
        <label for="radio01"><span></span>Lexical Properties</label>
    </div>

    <div>
        <input type="radio" id="radio02" name="radio" />
        <label for="radio02"><span></span>Glossary Search</label>
    </div-->



<form name="searchData" method="post" action="updatedExec1.php">
    <input type="submit" name="submit" value="Submit" /><br />
    <input type="radio" name="ctrSearch" value="glossary" onclick="searchClick(this);" checked/>Glossary Search
    <input type="radio" name="ctrSearch" value="lexical" onclick="searchClick(this);" />Lexical Properties
    <fieldset id="Search" class="f1">
        
         
    </fieldset>
    <fieldset id ="grammar-class" class ="f4">
        <legend>Grammar Class:</legend>
        <input type="radio" name="ctrGrammar" value="verb" onclick="grammarClick(this);" checked/>Verb
        <input type="radio" name="ctrGrammar" value="noun" onclick="grammarClick(this);"/>Noun
        <input type="checkbox" class ="phrase" name="detailsgiven" onchange="toggleDisabled(this.checked)"/>
        <p class ="pos">Include Phrases</p>

        <fieldset id ="options" class ="f2">
           
        </fieldset>

        <fieldset id ="tb1" class ="f3">
            
        </fieldset>
    </fieldset>
    
</form>

<script charset="UTF-8">
    /*document.getElementById('nav01').innerHTML =
        "<ul id='menu'>" +
        "<li><<a href='Index.html'>Glossary Search<></li>" +
        "<li><a href='Customers.html'>Lexical Properties</a></li>" +
        "</ul>";
    */

    function toggleDisabled(_checked) {
        document.getElementById('tb1').disabled = _checked ? false : true;
    }
    function setState() {
        document.getElementById('tb1').disabled = true;
        setSearch("glossary");
        setGrammar("verb");
    }



    //http://stackoverflow.com/questions/8838648/onchange-event-handler-for-radio-button-input-type-radio-doesnt-work-as-one

            

    // Handle radio button selection change
    function searchClick(ctrSearch) {
        setSearch(ctrSearch.value);
    }
    function grammarClick(ctrGrammar) {
        setGrammar(ctrGrammar.value);
    }

    //http:/ / www.w3schools.com / jsref / met_node_appendchild.asp
    //http://www.greywyvern.com/code/javascript/keyboard
    function loadKeyboard() {

        //console.log('Append Item');
        var foo = document.createElement('input');
        document.getElementById('Search').appendChild(foo);
        VKI_attach(foo);
    }

    // Update displayed data in Search fieldset
    function setSearch(searchSelect) {
        if (searchSelect == "glossary") {
            document.getElementById('Search').innerHTML =
                "<legend>Glossary Search</legend>" +
                "English: <br><input type='text' name='searchEnglish' value=''><br>" +
                "Bukusu:<br>";
            loadKeyboard();
        }
        if (searchSelect == "lexical") {
            document.getElementById('Search').innerHTML =
                "<legend>Lexical Properties</legend>" +
                /*"Part of Speech<br>" +
                "<select name='partOfSpeach'>" +
                    "<option selected='' value=''>" +
                    "</option>" +
                    "<option value='bedroom'>Bedroom</option>" +
                    "<option value='media-center'>Media-Center</option>" +
                "</select><br>" +*/
                "Number of Syllables<br>" +
                "<input type='number' name='quantity' min='1' max='10'>" +
                "<input type='checkbox' name='countRoot'/>Count for root?<br>" +
                "<br>Syllable1" +
                "Syllable2<br>" +
                "<select name='syllable1'>" +
                    "<option selected='' value=''>" +
                    "</option>" +
                    "<option value='2'>Long</option>" +
                    "<option value='1'>Short</option>" +
                "</select>" +

                "<select name='syllable2'>" +
                    "<option selected='' value=''>" +
                    "</option>" +
                    "<option value='2'>Long</option>" +
                    "<option value='1'>Short</option>" +
                "</select><br>" +
                "<br>Stem-Initial sound<br>" +
                "<select name='stemInitial'>" +
                    "<option selected='' value=''>" +
                    "</option>" +
				<?php
					$query = 'SELECT * FROM gp.initial_sound';
					include("../secure/dbConnect.php");
					//query to get all the results from initial sound
					$resource = pg_query($dbconn, $query);
					while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[1]."</option>'+";
					}
				?>
                "</select>";
        }
    }
    // Update displayed data in grammar-class fieldset
    function setGrammar(grammarSelect) {
        if (grammarSelect == "verb") {
            document.getElementById('options').innerHTML =
                " <legend>Verb options:</legend>" +
                "<input type='radio' name='toneClass' value='High'>High" +
                "<input type='radio' name='toneClass' value='Low'>Low<br>" +
                //changed name to verbOptions[] to be able to send the checkbox data serverside with an array
                "<input type='checkbox' name='verbOptions[]' value='18' />Reflexive<br>" +
                "<input type='checkbox' name='verbOptions[]' value='19' />Reciprocal<br>";

            document.getElementById('tb1').innerHTML =
                "<legend>Verb Phrase:</legend>" +

            "Tense<br>" +
            "<select name='Tense/Aspect'>" +
			<?php
				$classQuery = "SELECT subPos FROM gp.words WHERE pos ='Verb' GROUP BY subPos;";
				outputSelect1Col($classQuery);
			?>      
            "</select>" +
            /*"<br>Aspect<br>" +
            "<select name='Aspect'>" +
                "<option selected='' value=''>" +
                "</option>" +
                "<option value='bedroom'>Bedroom</option>" +
                "<option value='media-center'>Media-Center</option>" +
            "</select>"+*/
            "<br>" +
            //changed name to verbPhrase[] to be able to send the checkbox data
            //server side with an array

            //reference: http://stackoverflow.com/questions/7654155/get-post-data-from-multiple-checkboxes
            //http://www.html-form-guide.com/php-form/php-form-checkbox.html
            "<input type='checkbox' name='verbPhrase[]' value='Progressive' />Progressive<br>" +
            "<input type='checkbox' name='verbPhrase[]' value='Adverbs' />Adverb's<br>" +
            "<input type='checkbox' name='verbPhrase[]' value='Passive' />Passive<br>" +
            "<input type='checkbox' name='verbPhrase[]' value='Perfective' />Perfective<br>";
        }
        if (grammarSelect == "noun") {
            document.getElementById('options').innerHTML =
                " <legend>Noun options:</legend>" +
                "Noun class<br>" +
                // Weird bug on toggle blank data
                "<select name='subPos'>" +
				<?php
					$classQuery = "SELECT subPos FROM gp.words WHERE pos ='Noun' GROUP BY subPos ORDER BY subPos ASC;";
					outputSelect1Col($classQuery);
				?>                    
					"</select>" +
                "<!--<br>Noun class of Plural<br>" +
                "<select name='plural'>" +
                    "<option selected='' value=''>" +
                    "</option>" +
                    "<option value='bedroom'>Bedroom</option>" +
                    "<option value='media-center'>Media-Center</option>" +
                "</select><br>-->"+
				"<br><br>Tonal Pattern<br><select name='tonalPattern'>"+
				<?php
					$tonalQuery = "SELECT tonalPattern FROM gp.words WHERE pos ='Noun' GROUP BY tonalPattern;";
					outputSelect1Col($tonalQuery);
					function outputSelect1Col($query){
						include("../secure/dbConnect.php");
						$resource = pg_query($dbconn, $query);
						echo "\"<option value=''></option>\"+";
						while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[0]."</option>'+";
						}
					}
				?>
				"</select>"
				;

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
    }
       
</script>

    <script type="text/javascript" src="http://babbage.cs.missouri.edu/~cs3380s15grp15/keyboard/keyboard.js" charset="UTF-8"></script>
</body>
</html> 
