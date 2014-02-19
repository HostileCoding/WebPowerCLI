<?php 

//Read file content and returns it back to AJAX

	if(isset($_POST['variable'])){

		$filename = $_POST['variable'];
		$fp2=fopen('ps/'.$filename, "r");
		$line=array();
		
		while (!feof($fp2)){	
			$line[]=fgets($fp2);
		}

		fclose($fp2);

		$line_imploded = implode(";",$line);

		echo $line_imploded; //Pass arguments
	}else{
		echo 'Something went wrong on PHP page';
	}
?>