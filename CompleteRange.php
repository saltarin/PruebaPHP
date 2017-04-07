<?php

Class CompleteRange{
	
	function build($arreglo){
	
		for($i = count($arreglo) - 1; $i > 0;$i--){

			if($arreglo[$i] != $arreglo[$i-1] + 1){
				$parte_a = array_slice($arreglo,0,$i);
				$parte_b = array_slice($arreglo,$i);
				$add = range($arreglo[$i-1] + 1 ,$arreglo[$i] -1);
				$arreglo = array_merge($parte_a,$add,$parte_b);
			}
		}

		return $arreglo;
	}
}

$obj = new CompleteRange();

$entrada = [1, 2, 4, 5];
$salida = $obj->build($entrada);

$entrada_r = implode("|",$entrada);
$salida_r = implode("|",$salida);

echo "entrada : $entrada_r salida : $salida_r<br>";

$entrada = [2, 4, 9];
$salida = $obj->build($entrada);

$entrada_r = implode("|",$entrada);
$salida_r = implode("|",$salida);

echo "entrada : $entrada_r salida : $salida_r<br>";

$entrada = [55, 58, 60];
$salida = $obj->build($entrada);

$entrada_r = implode("|",$entrada);
$salida_r = implode("|",$salida);

echo "entrada : $entrada_r salida : $salida_r<br>";