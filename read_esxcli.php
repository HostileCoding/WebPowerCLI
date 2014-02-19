<?php 

//Read file content and returns it back to AJAX

					if(isset($_POST['variable'])){

							$esxclimajor=array();
							$type=$_POST['variable'];
							
							$fp=fopen("files/esxcli.txt", "r");
							while (!feof($fp))
							{
								$line=fgets($fp);

								$line=explode('\n', $line);

								$esxclimajor[]=$line[0];

							}
							fclose($fp);
							
							$i=0;
						
							print '<script>var value = ' . json_encode($esxclimajor) . ';</script>';
							
							foreach ($esxclimajor as $esxclicmd) {

								echo '<li><a onclick="addText(\'$esxcli.\'+value['.$i.'] +\'\n\')">' . $esxclicmd . '</a></li>';
							
								$i++;
							
							}
							

					}else{
						echo 'Something went wrong on PHP page';
					}
?>