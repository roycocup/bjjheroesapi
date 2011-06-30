<?php
//AQUI ANDRE! BOTAS AS PALAVRAS ENTRE COMAS E ENTRE VIRGULAS.
//here we set the words for the lookup in an array thats going to be parsed inside the string for the mask
$lookupWords = array("Godoi");

$url = "http://www.bjjheroes.com/a-z-bjj-fighters-list";
//get all the strings with the address of the fighters database only
$linkMask = '/\<a[\s]+href\=[\'|\"]([htp\:\w\.\/]+\-fighters[\w\d\/\-]+)/';

$directory = "fighters/";

//endereos is the html page that has the list of all fighters
//if the enderecos file exists then use that to cycle all of them. 
//If not, create one and use the array in memory
if (!file_exists($directory.'enderecos.txt')){
	$string = file_get_contents($url);
	file_put_contents($directory.'enderecos.txt', $string);
} else {
	$string = file_get_contents($directory.'enderecos.txt');
}

//get all the fighters pages
preg_match_all($linkMask, $string, $matches);

$fightersPage = $matches[1];
unset($matches);

$counter = 0;
foreach ($fightersPage as $key => $value){
	++$counter;
	$encodedValue = md5($value);
	
	if (! file_exists($directory.$encodedValue.".txt")){
		$string = file_get_contents($value);
		file_put_contents($directory.$encodedValue.".txt", $string);
	}else {
		$string = file_get_contents($directory.$encodedValue.".txt");
	}

	//glue them together and form the mask
	$words = implode('|', $lookupWords);
	$mask = "/[\.\w\s]{0,30}\s($words)[\s\w\'\-\/\:\,\<\>\=\"\.\\\]+\./";
	
	//Search the $lookup words in this string
	preg_match_all($mask, $string, $matches);
	
	//lets do a string with the results and the name of the page as a heading
	$result = "===".$value."==\n\r\n\r";
	foreach ($matches[0] as $k=>$v){
		$result .= $v."\n\r";
	}
	
	
	if($matches[0]){
		//if the array is not empty, put it on the file
		file_put_contents($directory."results.txt", $result, FILE_APPEND);
	}

	print "$value \n\r";
	ob_flush();
	//if ($counter == 50) break;
}
print $counter."\n\r";



?>
