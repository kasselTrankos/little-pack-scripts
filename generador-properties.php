<?php
    $pKey = isset($_POST['pKey']) ? $_POST['pKey'] : null ;
    $pName = 'PRCL' ;
    $pVersion = isset($_POST['pVersion']) ? $_POST['pVersion'] : null ;
    $showCases = true;
    if(count($argv)>1){
    	for($i=0;$i<count($argv);$i++){
	    	if($argv[$i]==='PRCL'){
	    		$pName="PRCL";
	    		break;
	    	}
	    	if($argv[$i]==='GCLI'){
	    		$pName="GCLI";
	    		break;
	    	}
	    	if($argv[$i]==='PRSR'){
	    		$pName="PRSR";
	    		break;
	    	}
	    	if($argv[$i]==='GEST'){
	    		$pName="GEST";
	    		break;
	    	}
	    	if($argv[$i]==='PRCL-CNT'){
	    		$pName="PRCL-CNT";
	    		break;
	    	}
	    	if($argv[$i]==='GCLI-CNT'){
	    		$pName="GCLI-CNT";
	    		break;
	    	}
	    	if($argv[$i]==='PRSR-CNT'){
	    		$pName="PRSR-CNT";
	    		break;
	    	}
	    	if($argv[$i]==='GEST-CNT'){
	    		$pName="GEST-CNT";
	    		break;
	    	}
	    }
	    $showCases = false;
    }

    $ruta = "./";
    $rutas = array();
    $pDir = __DIR__.'/.properties';
    $properties= parse_ini_file($pDir);
    echo "Bienvenido al generador de .properties para Sonar-runner.\n";
    if($showCases){
    	echo "Selecciona que proceso deseas producir:\n";
	    echo "\t1: PRCL\n";
	    echo "\t2: GCLI\n";
	    echo "\t3: PRSR\n";
	    echo "\t4: GEST\n";
	    echo "\t5: PRCL-CNT\n";
	    echo "\t6: GCLI-CNT\n";
	    echo "\t7: PRSR-CNT\n";
	    echo "\t8: GEST-CNT\n";
		$stdin = fopen('php://stdin', 'r');
		$str = fgets($stdin);
		switch ((int)$str) {
			case 1:
				$pName="PRCL";
				break;
			case 2:
				$pName="GCLI";
				break;
			case 3:
				$pName="PRSR";
				break;
			case 4:
				$pName="GEST";
				break;
			case 5:
				$pName="PRCL-CNT";
				break;
			case 6:
				$pName="GCLI-CNT";
				break;
			case 7:
				$pName="PRSR-CNT";
				break;
			case 8:
				$pName="GEST-CNT";
				break;
			default:
				$pName="PRCL";
				break;

		}
    }
    	function addFiles(){

    	}

		//Creamos el listado de directorios que cuelgan de la carpeta principal
		function listar_directorios_ruta($ruta){
			global $rutas;
			if(is_dir($ruta)){
		        if($dir = opendir($ruta)){
		            while(($archivo = readdir($dir)) !== false){
		                if($archivo != '.' && $archivo != '..' && $archivo != '.htaccess' && $archivo != 'generador-properties.php' && $archivo != 'sonar-project.properties' && $archivo != 'pom.xml'){
		                	$trunk = $archivo.'/trunk';
		                	if(is_dir($trunk)){
		                		$cnts = scandir($ruta.$trunk);
		                		 for($i=0;$i< count($cnts);$i++){
        							if(preg_match("/.*-webapp/", $cnts[$i], $output_array))
        							{
        								$trunk.='/'.$cnts[$i];
        								if(is_dir($trunk)){
        									$trunk.='/src';
        									if(is_dir($trunk)){
        										$trunk.='/main';
	        									if(is_dir($trunk)){
	        										$trunk.='/webapp';
		        									if(is_dir($trunk)){
		        										$files = scandir($ruta.$trunk);
		        										$trunk.='/';
		        										for($i=0;$i< count($files);$i++){
				                							if(preg_match("/.*_controller|.*directive|.*_model|.*_service|app|cnt|main/", $files[$i], $output_array)){
				                								array_push($rutas, $ruta.$trunk.$files[$i]);
				                							}
				                						}
		        									}
	        									}
        									}
        								}
        								//array_push($rutas, $ruta.$trunk.$cnts[$i]);
        							}
        						}
		                		$trunk.='/src';
		                		if(is_dir($trunk)){
		                			$trunk.='/main';
		                			if(is_dir($trunk)){
		                				$trunk.='/webapp';
		                				if(is_dir($trunk)){
		                					$trunk.='/js';
		                					if(is_dir($trunk)){
		                						$files = scandir($ruta.$trunk);
		                						$trunk.='/';
		                						for($i=0;$i< count($files);$i++){
		                							if(preg_match("/.*_controller|.*directive|.*_model|.*_service|app|cnt|main/", $files[$i], $output_array)){
		                								array_push($rutas, $ruta.$trunk.$files[$i]);
		                							}
		                						}

		                					}

		                				}

		                			}

		                		}
		                	}
		                }
		            }
		            closedir($dir);
		        }
			}else{
			}

		}

		//Escritura del archivo de propiedades para Sonar-Runner
		function escribir_archivo($rutas, $properties, $pName){
			$pKey = $properties['pKey'];
    		$pVersion= $properties['pVersion-'.$pName];

			$rutas[0] = str_replace("./" , "", $rutas[0]);
			$rutasFinal= $rutas[0].",";

			for($i = 1; $i < count($rutas); ++$i) {
				$rutas[$i] = str_replace("./" , "", $rutas[$i]);
				$rutas[$i] = $rutas[$i].",";
				$rutasFinal = $rutasFinal.$rutas[$i];
			}

			$rutasFinal = substr($rutasFinal, 0, strlen($rutasFinal) - 1);


//No tabular estas líneas

$propiedades ="
sonar.projectKey= ".$pKey."
sonar.projectName= ".$pName."
sonar.projectVersion= ".$pVersion."
sonar.sources= ".$rutasFinal."
sonar.language=js
sonar.javascript.jstestdriver.reportsfolder=jstestdriver
sonar.dynamicAnalysis=reuseReports
sonar.sourceEncoding=UTF-8
exclusions=**/test/**,**/tags/**
";


		      	$archivo = fopen("sonar-project.properties","w+");
				fwrite($archivo, $propiedades);
				fclose($archivo);

				echo "Archivo de propiedades creado correctamente.\nPuede ejecutar Sonar-Runner desde la consola dentro de esta carpeta.";
		}
		function write($properties, $filename, $pName)
		{
		   	$content="";

		   	$properties['pVersion-'.$pName]= $properties['pVersion-'.$pName]+0.1;
		   	foreach($properties as $k=>$v){
		   		$content.=$k."=".$v."\n";
		   	}

		    $fileWrite = fopen($filename, 'w');
		    fwrite($fileWrite,$content);
		    fclose($fileWrite);
		}
		listar_directorios_ruta($ruta);
		escribir_archivo($rutas, $properties, $pName);
		write($properties, $pDir, $pName);
		system('sonar-runner -e');

?>

