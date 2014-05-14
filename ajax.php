<?php
header ('Content-Type: application/json');
require_once('classe.php');
session_start();

$result=array();
switch($_GET['action']){
	case 'cri':
		$result['cri']=$_SESSION['pokemon'][$_GET['pokemon']]->rugir();
	break;
	case 'combat':
		try{
			if(isset($_GET['pokemon'])){
				if(isset($_SESSION['pokemon'][$_GET['pokemon']])){
					$pkmn=$_SESSION['pokemon'][$_GET['pokemon']];
				} else {
					throw new PokemonNotFoundException (htmlspecialchars($_GET['pokemon']));
				}
			}
		} catch (PokemonNotFoundException $e){
			$result['errors']='Le pokemon '.$e->getMessage().' n\'existe pas.';
		} catch (PokemonException $e){
			$result['errors']='Erreur : '.$e->getMessage();
		}

		try{
			if(isset($_SESSION['pokemon'])){
				if($_SESSION['pokemon'][$_GET['attaquant']]==$_SESSION['pokemon'][$_GET['defenseur']]){
					throw new SelfPokemonException ('Un pokemon ne peut s\'affronter lui même.');			
				}
				if(($_SESSION['pokemon'][$_GET['attaquant']]->hp)==0){
					throw new DeadPokemonException (htmlspecialchars($_GET['attaquant']));
				} elseif (($_SESSION['pokemon'][$_GET['defenseur']]->hp)==0){
					throw new DeadPokemonException (htmlspecialchars($_GET['defenseur']));
				} else {
				$result['combat']=$_SESSION['pokemon'][$_GET['attaquant']]->attaquer($_SESSION['pokemon'][$_GET['defenseur']]);
				$result['attaquant']=$_SESSION['pokemon'][$_GET['attaquant']];
				$result['defenseur']=$_SESSION['pokemon'][$_GET['defenseur']];
				}
			}
		} catch (SelfPokemonException $e){
			$result['errors']=$e->getMessage();
		} catch (DeadPokemonException $e){
			$result['errors']='Le pokemon '.$e->getMessage().' est déjà K.O., choisissez une autre cible.';
		}
	break;
	case 'init':
		$_SESSION = array();
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
	break;
}

echo json_encode($result);