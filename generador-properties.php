<?php
    $pKey = isset($_POST['pKey']) ? $_POST['pKey'] : null ;
    $pName = 'PRCL' ;
    $pVersion = isset($_POST['pVersion']) ? $_POST['pVersion'] : null ;
    $showCases = true;

    if($argv){
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
			default:
				$pName="PRCL";
				break;
		}
    }

		//Creamos el listado de directorios que cuelgan de la carpeta principal
		function listar_directorios_ruta($ruta){
			global $rutas;
			if(is_dir($ruta)){
		        if($dir = opendir($ruta)){
		            while(($archivo = readdir($dir)) !== false){
		                if($archivo != '.' && $archivo != '..' && $archivo != '.htaccess' && $archivo != 'generador-properties.php' && $archivo != 'sonar-project.properties' && $archivo != 'pom.xml'){
				            array_push($rutas, $ruta.$archivo);
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
    		$pVersion= $properties['pVersion'];

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
";


		      	$archivo = fopen("sonar-project.properties","w+");
				fwrite($archivo, $propiedades);
				fclose($archivo);

				echo "Archivo de propiedades creado correctamente.\nPuede ejecutar Sonar-Runner desde la consola dentro de esta carpeta.";
		}
		function write($properties, $filename)
		{
		   	$content="";

		   	$properties['pVersion']= $properties['pVersion']+0.1;
		   	foreach($properties as $k=>$v){
		   		$content.=$k."=".$v."\n";
		   	}

		    $fileWrite = fopen($filename, 'w');
		    fwrite($fileWrite,$content);
		    fclose($fileWrite);
		}
		listar_directorios_ruta($ruta);
		escribir_archivo($rutas, $properties,$pName);
		write($properties, $pDir);
		system('sonar-runner -e');

?>

