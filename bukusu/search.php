<!DOCTYPE html>
<html>
<head>
<title>PHP Search</title>
    <meta charset="UTF-8">
<link href="Site.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="keyboard/keyboard.css">
<!--http://www.greywyvern.com/code/javascript/keyboard-->

</head>
<?php
unset($_POST);
?>
<body  onload="setState()">
<nav id="nav01"></nav>

<nav id="nav02"></nav>
<div id="main">
    <h1>Bantu Language</h1><br/>
    <p>You can either run a Glossary Search or a Lexical Properties Search; only the selected tab Search will execute.</p>
    <br/>
    <input type="submit" name="submit" value="Submit" onclick="setQuery()" /><br/>
    
    <form name="searchData" id="searchData">
        <!--input type="submit" name="submit" value="Submit" /--><br />
        <input type="radio" name="ctrSearch" value="glossary" onclick="searchClick(this);" checked/>Glossary Search
        <input type="radio" name="ctrSearch" value="lexical" onclick="searchClick(this);" />Lexical Properties
        <fieldset id="Search" class="f1">
        
         
        </fieldset>
        <fieldset id ="grammar-class" class ="f5">
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
    <button type="reset" value="Reset" onclick="clearSession();" >Reset</button>
    <hr id="split"></hr>
    
<div id="id01" class="tbl1"></div>
<footer id="foot01"></footer>
</div>
<script charset="UTF-8">

    //http://www.w3schools.com/ajax/tryit.asp?filename=tryajax_post2
    //https://developer.mozilla.org/en-US/docs/Web/Guide/Using_FormData_Objects
    //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest#send()
    //http://www.w3schools.com/jsref/prop_form_method.asp
    //http://stackoverflow.com/questions/18592679/xmlhttprequest-to-post-html-form
    
    function setQuery() {
        document.getElementById("searchData").method = "post";
        var formData = new FormData(document.getElementById("searchData"));        
    
        var xmlhttp1 = new XMLHttpRequest();
        //var url1 = "Customers_HTML.php";
        var url1 = "demo/updatedExec1.php";

        xmlhttp1.onreadystatechange = function () {
            if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
                document.getElementById("id01").innerHTML =
               xmlhttp1.responseText;
            }
        }
        xmlhttp1.open("POST", url1, true);
        xmlhttp1.send(formData);
    }
    
    function clearSession(){
        document.getElementById("searchData").reset();
        document.getElementById("searchData").method = "post";

        var formData = new FormData(document.getElementById("searchData"));        
    
        var xmlhttp2 = new XMLHttpRequest();
        var url2 = "demo/clear.php";

        xmlhttp2.onreadystatechange = function () {
            if (xmlhttp2.readyState == 4 && xmlhttp2.status == 200) {
                document.getElementById("id01").innerHTML =
               xmlhttp2.responseText;
            }
        }
        xmlhttp2.open("POST", url2, true);
        xmlhttp2.send(formData);

        //resets the grammar form to default verb
        setState();
    }
    
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



    // Update displayed data in Search fieldset
    function setSearch(searchSelect) {
        if (searchSelect == "glossary") {
            document.getElementById('Search').innerHTML =
                "<legend>Glossary Search</legend>" +
                "English: <br>" +
                "<input type='text' name='searchEnglish' value=''><br>" +
                "Bukusu:<br>";
                loadKeyboard("Search", "searchBukusu");
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
					include("secure/dbConnect.php");
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
                
				"<br><br>Tonal Pattern<br><select name='tonalPattern'>"+
				<?php
					$tonalQuery = "SELECT tonalPattern FROM gp.words WHERE pos ='Noun' GROUP BY tonalPattern;";
					outputSelect1Col($tonalQuery);
					function outputSelect1Col($query){
						include("secure/dbConnect.php");
						$resource = pg_query($dbconn, $query);
						echo "\"<option value=''></option>\"+";
						while($record = pg_fetch_array($resource, null, PGSQL_NUM)){
						echo "'<option value=".$record[0].">".$record[0]."</option>'+";
						}
					}
				?>
				"</select>";
				

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
    <script src="Script.js"></script>
    <script type="text/javascript" src="keyboard/keyboard.js" charset="UTF-8"></script>
</body>
</html> 
