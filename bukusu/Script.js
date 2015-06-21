document.getElementById("foot01").innerHTML =
"<p>&copy;  " + new Date().getFullYear() + " Ben Stockman. All rights reserved.</p>";

document.getElementById("nav01").innerHTML =
 "<ul id='menu'>" +
"<li><a href='https://babbage.cs.missouri.edu/~cs3380s15grp15/Index.html'>Home</a></li>" +
 "<li><a href='https://babbage.cs.missouri.edu/~cs3380s15grp15/search.php'>Data</a></li>" +
 "<li><a href='https://babbage.cs.missouri.edu/~cs3380s15grp15/About.html'>About</a></li>" +
 "</ul>";

/*document.getElementById("nav02").innerHTML =*/

var xmlhttp = new XMLHttpRequest();
var url = "/~cs3380s15grp15/user/user.php";

xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        updateMenu2(xmlhttp.responseText);
    }
}
xmlhttp.open("POST", url, true);
xmlhttp.send();

//http:/ / www.w3schools.com / jsref / met_node_appendchild.asp
//http://www.greywyvern.com/code/javascript/keyboard
function loadKeyboard(parent, name) {

    //console.log('Append Item');
    var foo = document.createElement('input');
    foo.setAttribute("type", "text");
    foo.setAttribute("name", name);
    document.getElementById(parent).appendChild(foo);
    VKI_attach(foo);
}

function updateMenu2(response) {
    //var parsedResponse = escapeJsonString(response);
    var arr = JSON.parse(response);
    var i;
    var out = "<ul id='menu2'>";

    for(i = 0; i < arr.length; i++) {
        out +=
        "<li><a href='" + 
        arr[i].Link +
        "'>" +
        arr[i].Name +
        "</a></li>";
        
    }
    out += "</ul>"
    document.getElementById("nav02").innerHTML = out;
}

/*function escapeJsonString(value) {
    // http://stackoverflow.com/questions/1048487/phps-json-encode-does-not-escape-all-json-control-characters
    // list from www.json.org: (\b backspace, \f formfeed)    
    var escapers = new array("\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
    var replacements = new array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
    var result = str_replace( escapers, replacements, value);
    return result;
}*/




 
