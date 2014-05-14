<?php
require_once('classe.php');
session_start();
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Pokemon Jdr</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="jquery-2.1.0.js"></script>
	<script type="text/javascript" src="script.js"></script>
</head>
<body>

<?php
	require_once('function.inc.php');

	if(!isset($_SESSION['pokemon'])){
		$pkmn=array();
		$pkmn['1']=new bulbizarre();
		$pkmn['2']=new salameche();
		$pkmn['3']=new carapuce();
		$pkmn['4']=new pikachu();
		$pkmn['5']=new abra();
		$pkmn['6']=new mew();
		$pkmn['7']=new ferossinge();
		$pkmn['8']=new fantominus();
		$_SESSION['pokemon']=$pkmn;
	} else {
		foreach($_SESSION['pokemon'] as $poke){
			echo affiche($poke);
		}
	}
	
?>
<div class="text"></div>
<a href="index.php"><button class="init">Réinitialiser</button></a>

</body>
</html>