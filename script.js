$(function(){
	//modification des informations attaquant ou défenseur grâce à ajax
	function affichage(pokemon_cible){
		$('#'+pokemon_cible['id']+' .level').text(pokemon_cible['level']);
		/*$('#'+pokemon_cible['id']+' .pok_img').attr('src', 'image/'+pokemon_cible['img']);*/
		$('#'+pokemon_cible['id']+' .hp').text(pokemon_cible['hp']);
		
		$('#'+pokemon_cible['id']+' .full').css("width", ((pokemon_cible['hp']/pokemon_cible['hpmax'])*100)+'%');
		$('#'+pokemon_cible['id']+' .medium').css("width", ((pokemon_cible['hp']/pokemon_cible['hpmax'])*100)+'%');
		$('#'+pokemon_cible['id']+' .low').css("width", ((pokemon_cible['hp']/pokemon_cible['hpmax'])*100)+'%');

		if((pokemon_cible['hp'])<=(pokemon_cible['hpmax'])*15/100){
			$('#'+pokemon_cible['id']+' .hpbarre>span').removeClass(['medium','full']).addClass('low');
		} else if((pokemon_cible['hp'])<=(pokemon_cible['hpmax']/2)){
			$('#'+pokemon_cible['id']+' .hpbarre>span').removeClass(['low','full']).addClass('medium');
		} else {
			$('#'+pokemon_cible['id']+' .hpbarre>span').removeClass(['medium','low']).addClass('full');
		}
		if(pokemon_cible['hp']==0){
			$('#'+pokemon_cible['id']+' img.pok_img').attr('src', 'image/Poke_Ball_Sprite.png');
		}
		//if pokemon évolue changement image, changement nom et changement stats si besoin?
		$('#'+pokemon_cible['id']+' .hpmax').text(pokemon_cible['hpmax']);
		$('#'+pokemon_cible['id']+' .atk').text(pokemon_cible['atk']);
		$('#'+pokemon_cible['id']+' .def').text(pokemon_cible['def']);
	}


	var attaquant=null;
	
	$('.pokemon').click(function(){
		//Cri du pokemon au click
		var pokclick=$('h2', this).text();
		var pokclick2=this.id;
		console.log(pokclick2);
		
		$.get('ajax.php', {action: 'cri', pokemon: pokclick},function(data){
			var textcri=$('<p/>').appendTo('.text');
			textcri.text(data['cri']);
		});

		//mise dans variable de défenseur si attaquant n'existe pas
		var id=pokclick2;
		if(attaquant===null){
			attaquant=id;
			$('button.bt_atk').removeClass('bt_atk').addClass('bt_def');
			$('button.bt_atk').text('défendre');
		} else {
			var defenseur=id;
			//exception pour attaquant=defenseur
			if (attaquant==defenseur){
				$.get('ajax.php', {action: 'combat', attaquant: attaquant, defenseur: defenseur}, function(data){
					var textcbt=$('<p/>', {class: 'logfight'}).appendTo('.text');
					textcbt.text(data['errors']);
				});
			} else {
				$.get('ajax.php', {action: 'combat', attaquant: attaquant, defenseur: defenseur}, function(data){

					//vérification de la mort d'un des 2 pokemon avec exception
					if(data['errors']){
							var textcbt=$('<p/>', {class: 'logfight'}).appendTo('.text');
							textcbt.text(data['errors']);
					} else {
						
					//affichage des informations de combat si 0 exceptions
						var textcbt=$('<p/>', {class: 'logfight'}).appendTo('.text');
						textcbt.text(data['combat']);

						var pok_attaquant=affichage(data['attaquant']);
						var pok_defenseur=affichage(data['defenseur']);

						attaquant=null;
						defenseur=null;

						$('button.bt_def').removeClass('bt_def').addClass('bt_atk');
						$('button.bt_def').text('attaquer');
					}
				});
			}
		}
	});//TODO: choix new pok quand pok mort, if lvl=3 evolve (changement img), log de cbt maj, modifier id pour les evolutions
	$('.init').click(function(){
		$.get('ajax.php', {action: 'init'}, function(data){

		});
	});
});