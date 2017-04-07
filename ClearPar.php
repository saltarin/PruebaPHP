<?php

Class ClearPar{

	function build($cadena){

		$incidencias = substr_count($cadena,"()");
		return str_repeat("()",$incidencias);
	}
}


$obj = new ClearPar();

$entrada = "()())()";
$salida = $obj->build($entrada);
echo "entrada: $entrada salida: $salida<br/>";

$entrada = "()(()";
$salida = $obj->build($entrada);
echo "entrada: $entrada salida: $salida<br/>";

$entrada = ")(";
$salida = $obj->build($entrada);
echo "entrada: $entrada salida: $salida<br/>";

$entrada = "((()";
$salida = $obj->build($entrada);
echo "entrada: $entrada salida: $salida<br/>";

