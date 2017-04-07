<?php


class ChangeString {

	function build($cadena) {
		$salida = array();

		for($i = 0; $i < strlen($cadena); $i++){

			$regresar = false;
			$saltar = false;
			$caracter = $cadena[$i];

			if(!ctype_alnum($caracter))
				$saltar = true;

			if(is_numeric($caracter))
				$saltar = true;


			if(!$saltar)
			{
				if(ctype_upper($caracter)){
					$regresar = true;
					$caracter = strtolower($caracter);
				}
				
				if($caracter == "n")
					$caracter = "ñ";
				elseif(ord($caracter) == ord("ñ")) #ñ
					$caracter = "o"; #o
				else{

					if(ord($caracter) + 1 > 122) #z
						$caracter = chr(97);
					else
						$caracter = chr(ord($caracter) + 1);	
				}


				if($regresar)
					$caracter = strtoupper($caracter);
			}


			array_push($salida,$caracter);

		}		
		return implode($salida);
  }
}

$obj = new ChangeString();

$entrada = "123 abcd*3";
$salida = $obj->build($entrada);
echo "entrada : $entrada salida : $salida<br>";

$entrada = "**Casa 52";
$salida = $obj->build($entrada);
echo "entrada : $entrada salida : $salida<br>";

$entrada = "**Casa 52Z";
$salida = $obj->build($entrada);
echo "entrada : $entrada salida : $salida<br>";