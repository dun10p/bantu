<?php 
$selection = $_POST['pc'];
switch ($selection){    
    case "bedroom":
		$pc = "";
		break;
    case "media-center":
        $pc = "";
        break;
  
}
shell_exec($pc);
echo "The computer '".$selection."' was sent a WOL packet at: ". date("Y-m-d h:i:sa");
?>
