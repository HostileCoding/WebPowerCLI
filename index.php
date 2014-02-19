<!--

WebPowerCLI v1.0

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
and/or sell copies of the Software.

THIS SOFTWARE IS PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND. AUTHOR IS NOT RESPONSIBLE FOR ANY DAMAGE RESULTING IN USING THIS SOFTWARE.


Version Notes:

v1.0 -------------

-Tested with Firefox 26.0, Chrome 32.0, IE 10.0
-"Save to File" feature only works with Chrome browser
-Comments "#" do not work
-Sometimes more than a single click is required in order to add cmdlet to the Input text-area

Credits:

CSS3 Accordion inspired by - http://tympanus.net/Tutorials/CSS3Accordion/ 
Modal Window inspired by - http://2.0webtutorials.com/css3-html5/create-modal-window-pure-html5-css3/
jQueryAsuggest - http://imankulov.github.io/asuggest/
jQueryBalloons - http://www.jqueryrain.com
-->

<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title>WebPowerCLI</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://imankulov.github.io/js/jquery.asuggest.js"></script>
<script src="http://imankulov.github.io/js/jquery.a-tools-1.4.1.js"></script>
<script src="http://file.urin.take-uma.net/jquery.balloon.min.js"></script>
<script language="javascript" type="text/javascript">
function addText(text){
	document.testForm.cmd.value += text;
}
function emptyTextBox(){ //Empty textbox
	document.testForm.cmd.value = "";
}
function getText(content, filename) { //Download your script (as for now works only in Chrome)
    var file = document.createElement('a');
    file.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
    file.setAttribute('download', filename);
    file.click();
}
function loadScriptContent(filename){ //Need to pass filename to PHP in order to read file content

    $.ajax({  
      url: 'read_script.php', 
      type: "POST", 
	  data: {'variable': filename},
      dataType: "html",
      success: function(msg) {  
        $("textarea#cmd").html(msg);  
      },
      error: function(){
        alert("Something went wrong on AJAX");
      } 
    }); 

}
function openModal(text){

	$.ajax({
    url: 'read_esxcli.php', 
    type: "POST", 
	data: {'variable': text},
    dataType: "html",
    success: function(data){
		location.href = "#openModal";
        $("#content").html(data);
    },
	error: function(){
        alert("Something went wrong on AJAX");
      } 	
	});
	
}
</script>
</head>
<body>

<?php 
	if(isset($_POST['cmd'])) { 
         $content = htmlentities ($_POST['cmd']); }
?>

<div id="title">

</div>
<div id="main">
	<div id="box1">
	<div class="container">
			<section class="ac-container">
				<div>
					<input id="ac-1" name="accordion-1" type="checkbox" />
					<label for="ac-1">PowerCLI Cmdlets</label>
					<article class="ac-fixed">
						<?php

						$cmdlet=array();
						$argument=array();

						$fp=fopen("files/cmd.txt", "r");
						while (!feof($fp))
						{
							$line=fgets($fp);

							$line=explode(' ', $line, 2);

							$cmdlet[]=$line[0];
							$argument[]=$line[1];

						}
						fclose($fp);

						$i=0;

						print '<script>var values = ' . json_encode($argument) . ';</script>'; //Pass arguments

						print '<script>var suggests = ' . json_encode($cmdlet) . ';</script>'; //Pass cmdlets to autocomplete

						foreach ($cmdlet as $command) {

							echo '<li><a onclick="addText(\''.$command.'\'+\'\'+values['.$i.']+\';\')">' . $command . '</a> <a class="doc" href="https://www.vmware.com/support/developer/PowerCLI/PowerCLI55R1/html/'. $command .'.html" target="_blank"><img src="img/doc.png"></a></li>';

							$i++;

						}

						?>
					</article>
				</div>
				<div>
					<input id="ac-2" name="accordion-1" type="checkbox" />
					<label for="ac-2">PowerCLI Scripts</label>
					<article class="ac-fixed">
						<ul>
							<?php
								$dir = 'ps';
								if ($handle = opendir($dir)) {
									while (false !== ($entry = readdir($handle))) {
										if ($entry != "." && $entry != ".." && strtolower(substr($entry, strrpos($entry, '.') + 1)) == 'ps1') { //Select only .ps1 files
											echo '<li><a onclick="loadScriptContent(\''.$entry.'\')">'.$entry.'</a></li>';
										}
									}
									closedir($handle);
							   }
							?>
						</ul>
					</article>
				</div>
				<div>
					<input id="ac-3" name="accordion-1" type="checkbox" />
					<label for="ac-3">EsxCLI</label>
					<article class="ac-fixed">
						<ul>
							<?php

							$esxclimajor=array();
							$esxcliminor=array();
							$esxclidistinct=array();

							$fp=fopen("files/esxcli.txt", "r");
							while (!feof($fp))
							{
								$line=fgets($fp);

								$line=explode('.', $line, 2);

								$esxclimajor[]=$line[0];
								$esxcliminor[]=$line[1];

							}
							fclose($fp);

							$i=0;

							$esxclidistinct = array_unique($esxclimajor, SORT_REGULAR); //Display distinct ESXCLI Name spaces
							
							foreach ($esxclidistinct as $command) {

								echo '<li><a onclick="openModal(\'' . $command . '\')">' . $command . '</a></li>';

								$i++;

							}

							?>
						</ul>
					</article>
				</div>
			</section>
    </div>
	</div>
	<div id="box2">
		<div id="boxheader">
			Input
		</div>
		<div id="boxcontent">		
			<form name="testForm" id="testForm" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
				<a id="tip">Toggle Tips</a><br \>
				<textarea spellcheck="false" name="cmd" id="cmd" cols="112" rows="20"><?php echo $content; ?></textarea><br \>
				<a onclick="getText(document.testForm.cmd.value, 'MyScript.ps1')">Save to File</a><br \>
				<input type="submit" name="submit" id="submit" value="Run PowerCLI Command" /> <button onclick="emptyTextBox()">Cancel</button>
			</form>			
			<?php 
				if((isset($_POST["submit"])) )
				{
					$command_untrimmed2 = $_POST["cmd"];
					$command_untrimmed = preg_replace("/\r\n|\r|\n/",';',$command_untrimmed2);
					$command = str_replace("\"","'",$command_untrimmed);
					$powercli = "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe -psc \"C:\\Program Files (x86)\\VMware\\Infrastructure\\vSphere PowerCLI\\vim.psc1\" -Command \"& {$command}\"";    
					$query = shell_exec("$powercli");
				}
			?>			
		</div>
		<div id="boxheader">
			Output
		</div>
		<div id="boxcontent">
			<form name="outForm" id="outForm">
			<textarea disabled name="output" spellcheck="false" cols="112" rows="20"><?php echo $query; ?></textarea><br \>
			<a onclick="getText(document.outForm.output.value, 'MyOutput.txt')">Save to File</a><br \>
			</form>	
		</div>
	</div>
</div>

	<div id="openModal" class="modalDialog">
    <div>
            <h2>EsxCLI - Commands</h2>
            <p>Before using EsxCLI remember to use "Get-EsxCli" cmdlet. e.g. $esxcli = Get-EsxCli -VMHost (...)</p>
			<ul id="content">
			<!-- Content returned by AJAX will be dinamycally placed here -->
			</ul>
            <a href="#ok" title="Close" class="close">X</a>
    </div>

	<?php
		$lines = file('files/tips.txt', FILE_IGNORE_NEW_LINES); //Read tips to show in balloon
	?>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$(".suggest").each(function(){
				eval($(this).text());
			});
		});
		$("#cmd").asuggest(suggests);
		
		$(function() {
			$('#tip').showBalloon({ contents: "<?php echo $lines[array_rand($lines)]; ?>", position: "right" }).toggle( //Show a random tip
				function(){ $(this).hideBalloon(); },
				function(){ $(this).showBalloon(); }
			);
		}); 
	</script>
</div>
</body>
</html>