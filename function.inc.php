<?php
	function img_pok($pokemon){
		switch ($pokemon->id){
				case '1':
					echo '<img src="image/187.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '2':
					echo '<img src="image/121.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '3':
					echo '<img src="image/048.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '4':
					echo '<img src="image/145.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '5':
					echo '<img src="image/040.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '6':
					echo '<img src="image/080.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '7':
					echo '<img src="image/050.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '8':
					echo '<img src="image/106.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '9':
					echo '<img src="image/015.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '10':
					echo '<img src="image/113.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '11':
					echo '<img src="image/032.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
				case '12':
					echo '<img src="image/103.gif"/><img src="image/'.$pokemon->img.'" class="pok_img"/>';
				break;
			}
	}

	function affiche($pokemon){
		echo '<div class="pokemon" id="'.$pokemon->id.'"><div class="pokemonnom">';
		$img=img_pok($pokemon);
		echo '<h2>'.$pokemon->nom.'</h2></div>';
		echo '<div class="stats"><p>Level : <span class="level">'.$pokemon->level.'</span></p>';
		if(($pokemon->hp)>(($pokemon->hpmax)/2)){
			echo '<p>hp : <span class="hpbarre"><span class="full"></span></span></p>';
		} elseif(($pokemon->hp)>(($pokemon->hpmax)*15/100)){
			echo '<p>hp : <span class="hpbarre"><span class="medium"></span></span></p>';
		} else {
			echo '<p>hp : <span class="hpbarre"><span class="low"></span></span></p>';
		}
		echo '<p><span class="hp">'.$pokemon->hp.'</span>/<span class="hpmax">'.$pokemon->hpmax.'</span></p>';
		echo '<p>type : <span class="'.$pokemon->type.'">'.$pokemon->type.'</span></p>';
		echo '<p>attaque : <span class="atk">'.$pokemon->atk.'</span></p>';
		echo '<p>defense : <span class="def">'.$pokemon->def.'</span></p><button class="bt_atk">attaquer</button></div></div>';
	}