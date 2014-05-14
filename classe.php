<?php
	/* CLASSES EXCEPTIONS */
	class PokemonException extends Exception {}
	class PokemonNotFoundException extends PokemonException {}
	class SelfPokemonException extends PokemonException {}
	class DeadPokemonException extends PokemonException {}


	/* CLASSES POKEMON */
	//class pokemon contenant toutes les informations nécessaire à leur création
	class Pokemon{
		public $nom;
		public $id;
		public $level=1;
		public $type;
		public $types= array(
			'eau'=>array('eau'=>0.5, 'feu'=>2, 'plante'=>0.5, 'foudre'=>1, 'psy'=>1, 'combat'=>1, 'spectre'=>1),
			'feu'=>array('eau'=>0.5, 'feu'=>0.5, 'plante'=>2, 'foudre'=>1, 'psy'=>1, 'combat'=>1, 'spectre'=>1),
			'plante'=>array('eau'=>2, 'feu'=>1, 'plante'=>0.5, 'foudre'=>1, 'psy'=>1, 'combat'=>1, 'spectre'=>1),
			'foudre'=>array('eau'=>2, 'feu'=>1, 'plante'=>0.5, 'foudre'=>0.5, 'psy'=>1, 'combat'=>1, 'spectre'=>1),
			'psy'=>array('eau'=>1, 'feu'=>1, 'plante'=>1, 'foudre'=>1, 'psy'=>0.5, 'combat'=>2, 'spectre'=>1),
			'combat'=>array('eau'=>1, 'feu'=>1, 'plante'=>1, 'foudre'=>1, 'psy'=>0.5, 'combat'=>1, 'spectre'=>0),
			'spectre'=>array('eau'=>1, 'feu'=>1, 'plante'=>1, 'foudre'=>1, 'psy'=>0.5, 'combat'=>1, 'spectre'=>2)
			);
		public $hp;
		public $hpmax;
		public $atk;
		public $def;
		public $cri;
		public $img;
		public $actions=array('echoue'=>array(' rate son attaque !', ' se ramasse lamentablement', ' ne vous écoute pas', ' a choisi d\'ignoré votre ordre', ' se couche dans l\'herbe', ' ronfle bruyamment', ' a la trouille', ' est paralysé'),
							  'attaque'=>array(' attaque avec panache', ' attaque avec les dents', ' frappe de toutes ses forces', ' attaque', ' griffe', ' mord'),
							  'defendre'=>array(' parre brillament', ' bloque tant bien que mal', ' prend cher'),
							  'esquive'=>array(' évite le coup', ' esquive like a ninja', ' se vautre et esquive par chance'),
							  'autre'=>array(' est K.O. !', ' monte au niveau ', 'fin du tour'));
		public $text_log=array();
		public $evolution=array();
		public function __construct($nom, $id, $type, $hpmax, $atk, $def, $cri, $img){
			$this->id=$id;
			$this->level=1;
			$this->nom=$nom;
			$this->hpmax=$hpmax;
			$this->hp=$hpmax;
			$this->type=$type;
			$this->types=$this->types[$type];
			$this->atk=$atk;
			$this->def=$def;
			$this->cri=$cri;
			$this->img=$img;
		}
		public function attaquer(&$pokemon, $contre=true){
			$proba=ceil(rand(1, 10));
			if($proba==1){
				$i=ceil(rand(0,7));
				$dgt=0;
				$text_log=array();
				$text_log=array($this->nom.''.($this->actions['echoue'][$i]).' dégats=0');
			} else {
				$ecart = round($this->atk*20/100);
				$dgt_brut=rand($this->atk-$ecart,$this->atk+$ecart);
				$dgt=$dgt_brut*($this->types[$pokemon->type]);
				$i=ceil(rand(0,5));
				$text_log=array();
				$text_log[]=($this->nom.''.($this->actions['attaque'][$i]));
			}
			$text_log=array_merge($text_log, [$pokemon->defendre($dgt, $this, $contre)]);
			return $text_log;
		}
		public function defendre($dgt, &$pokemon, $contre ){
			$text_log = array();
			$proba=ceil(rand(1, 15));

			if($proba==1){
				$hpperdu=0;
				$i=ceil(rand(0,2));
				$text_log[]=$this->nom.($this->actions['esquive'][$i]);
			} else {
				$hpperdu=round($dgt-($this->def/2));
				if($hpperdu<0){
					$hpperdu=0;
				}
				$text_log[]=($this->nom.' perd '.$hpperdu.' hp');
			}
			
			$text_log=array_merge($text_log, $this->souffrir($hpperdu, $pokemon, $contre));
			return $text_log;
		}
		public function souffrir($hpperdu, &$pokemon, $contre){
			$text_log = array();
			$this->hp=round(($this->hp)-$hpperdu);
			if($this->hp<=0){
				$this->hp=0;
				$text_log[]=(($this->nom).' hp='.($this->hp));
				$text_log=array_merge($text_log, $this->mourir($pokemon));
			} else {
				switch($contre){
					case true:
						$text_log[]=$this->nom.' contre-attaque';
						$text_log=array_merge($text_log, $this->attaquer($pokemon, false));
					break;
					case false:
						$text_log=array();
						$text_log[]=($this->actions['autre'][2]);
					break;
				}
			}
			return $text_log;
		}
		public function mourir(&$pokemon){
			$this->img='Poke_Ball_Sprite.png';
			$text_log=array();
			$text_log[]=($this->nom.($this->actions['autre'][0]));
			$text_log=array_merge($text_log, $pokemon->levelup());
			return $text_log;
		}/*
		public function levelup(){
			$this->nom.$this->level++;
			$levelup=$this->level*10/100;
			$this->def=round($this->def+($this->def * $levelup));
			$this->atk=round($this->atk+($this->atk * $levelup));
			$hp_plus=$this->hpmax * $levelup;
			$this->hpmax=round($this->hpmax+$hp_plus);
			$this->hp=round($this->hp+$hp_plus);
			$text_log=array();
			$text_log=array($this->nom.($this->actions['autre'][1]).$this->level);
			if(isset($this->evolution[$this->level])){
				$evo = new $this->evolution[$this->level]();
				$evo->ajust_level($this->level);
				$text_log=array();
				$text_log=array($this->nom.' évolue en '.$evo->nom.' !');
				//return $evo;
			}
			return $text_log;
		}*/
		public function rugir(){
			return $this->cri;
		}
		public function levelup(){
	        $this->nom.$this->level++;
	        $text_log=array();
	        $text_log=array($this->nom.' monte au niveau '.$this->level.'. Il a désormais '.$this->hpmax.' points de vie au maximum.'.PHP_EOL);
	        if(isset($this->evolution[$this->level])) {
	            $evo = new $this->evolution[$this->level]();
	            $text_log=array();
	            $text_log[]=($this->nom.' évolue en '.$evo->nom.' ! Il a désormais '.$evo->hpmax.' points de vie.'.PHP_EOL);
	            $this->nom=$evo->nom;
	            $this->cri=$evo->cri;
	            $this->img=$evo->img;
	            $levelup=$evo->level*10/100;
				$this->def=round($evo->def+($evo->def * $levelup));
				$this->atk=round($evo->atk+($evo->atk * $levelup));
				$hp_plus=$evo->hpmax * $levelup;
				$this->hpmax=round($evo->hpmax+$hp_plus);
				$this->hp=round($evo->hp+$hp_plus);
	            $evo->text_log = $this->text_log;
	        //    return $evo;
	        } else {
	        	$levelup=$this->level*10/100;
				$this->def=round($this->def+($this->def * $levelup));
				$this->atk=round($this->atk+($this->atk * $levelup));
				$hp_plus=$this->hpmax * $levelup;
				$this->hpmax=round($this->hpmax+$hp_plus);
				$this->hp=round($this->hp+$hp_plus);
	        }
	        return $text_log;
	    }
	}

	//Pour chaque pokemon, on renseigne leur valeur propre appliqué à celle de 
	//pokemon, la classe parent
	class florizarre extends Pokemon{
		public function __construct($nom='florizarre'){
			parent::__construct($nom, 1, 'plante', '50', '10', '13', 'floriiii !!!', '0031.gif');
		}
	}
	class herbizarre extends Pokemon{
		public $evolution=array(3 => 'florizarre');
		public function __construct($nom='herbizarre'){
			parent::__construct($nom, 1, 'plante', '23', '8', '10', 'herbiiii !!!', '003.gif');
		}
	}
	class bulbizarre extends Pokemon{
		public $evolution=array(2 => 'herbizarre');
		public function __construct($nom='bulbizarre'){
			parent::__construct($nom, 1, 'plante', '15', '5', '7', 'Bulbiiii !!!', '001.gif');
			$herbizarre=new $this->evolution[2]();
		}
	}
	class dracofeu extends Pokemon{
		public function __construct($nom='dracofeu'){
			parent::__construct($nom, 2, 'feu', '30', '12', '9', 'drraaaa !!!', '0071.gif');
		}
	}
	class reptincel extends Pokemon{
		public $evolution=array(3 => 'dracofeu');
		public function __construct($nom='reptincel'){
			parent::__construct($nom, 2, 'feu', '25', '9', '8', 'reptinceeell !!!', '006.gif');
		}
	}
	class salameche extends Pokemon{
		public $evolution=array(2 => 'reptincel');
		public function __construct($nom='salameche'){
			parent::__construct($nom, 2, 'feu', '20', '7', '6', 'Salamèèèche !!!', '004.gif');
		}
	}
	class tortank extends Pokemon{
		public function __construct($nom='tortank'){
			parent::__construct($nom, 3, 'eau', '30', '10', '12', 'tooortaank !!!', '000.gif');
		}
	}
	class carabaffe extends Pokemon{
		public $evolution=array(3 => 'tortank');
		public function __construct($nom='carabaffe'){
			parent::__construct($nom, 3, 'eau', '22', '8', '8', 'carabaaaffe !!!', '009.gif');
		}
	}
	class carapuce extends Pokemon{
		public $evolution=array(2 => 'carabaffe'/*, 3=> 'tortank'*/);
		public function __construct($nom='carapuce'){
			parent::__construct($nom, 3, 'eau', '18', '6', '6', 'caracarapuce !!!', '007.gif');
		}
	}
	class raichu extends Pokemon{
		public function __construct($nom='raichu'){
			parent::__construct($nom, 4, 'foudre', '25', '10', '8', 'raiiiiichu !!!', '026f.gif');
		}
	}
	class pikachu extends Pokemon{
		public $evolution=array(2 => 'raichu');
		public function __construct($nom='pikachu'){
			parent::__construct($nom, 4, 'foudre', '16', '7', '5', 'pikapikaaa !!!', '025.gif');
		}
	}
	class alakazam extends Pokemon{
		public function __construct($nom='alakazam'){
			parent::__construct($nom, 5, 'psy', '23', '10', '9', 'ala...kazammm !!!', '065.gif');
		}
	}
	class kadabra extends Pokemon{
		public $evolution=array(3 => 'alakazam');
		public function __construct($nom='kadabra'){
			parent::__construct($nom, 5, 'psy', '19', '8', '7', 'kadabraaaaa !!!', '064.gif');
		}
	}
	class abra extends Pokemon{
		public $evolution=array(2 => 'kadabra');
		public function __construct($nom='abra'){
			parent::__construct($nom, 5, 'psy', '17', '7', '5', 'aaaabraa !!!', '063.gif');
		}
	}
	class mew extends Pokemon{
		public function __construct($nom='mew'){
			parent::__construct($nom, 6, 'psy', '20', '8', '8', 'meeeeewwwww !!!', '151.gif');
		}
	}
	class colossinge extends Pokemon{
		public function __construct($nom='colossinge'){
			parent::__construct($nom, 7, 'combat', '25', '12', '10', 'colossiiinnnge !!!', '057.gif');
		}
	}
	class ferossinge extends Pokemon{
		public $evolution=array(2 => 'colossinge');
		public function __construct($nom='ferossinge'){
			parent::__construct($nom, 7, 'combat', '20', '8', '7', 'ferossiiinnnge !!!', '056.gif');
		}
	}
	class ectoplasma extends Pokemon{
		public function __construct($nom='ectoplasma'){
			parent::__construct($nom, 8, 'spectre', '25', '12', '10', 'ectoooplasma !!!', '094.gif');
		}
	}
	class spectrum extends Pokemon{
		public $evolution=array(3 => 'ectoplasma');
		public function __construct($nom='spectrum'){
			parent::__construct($nom, 8, 'spectre', '18', '9', '8', 'speeeeectruum !!!', '093.gif');
		}
	}
	class fantominus extends Pokemon{
		public $evolution=array(2 => 'spectrum');
		public function __construct($nom='fantominus'){
			parent::__construct($nom, 8, 'spectre', '17', '7', '6', 'fantoooominus !!!', '092.gif');
		}
	}
	class akwakwak extends Pokemon{
		public function __construct($nom='akwakwak'){
			parent::__construct($nom, 9, 'eau', '20', '12', '10', 'akwaaa !!!', '055.gif');
		}
	}
	class psykokwak extends Pokemon{
		public $evolution=array(2 => 'akwakwak');
		public function __construct($nom='psykokwak'){
			parent::__construct($nom, 9, 'eau', '15', '5', '8', 'psykoo !!!', '054.gif');
		}
	}
	class tartard extends Pokemon{
		public function __construct($nom='tartard'){
			parent::__construct($nom, 10, 'eau', '28', '12', '11', 'tartaarrrd !!!', '062.gif');
		}
	}
	class tetarte extends Pokemon{
		public $evolution=array(3 => 'tartard');
		public function __construct($nom='tetarte'){
			parent::__construct($nom, 10, 'eau', '20', '9', '8', 'teeetarte !!!', '061.gif');
		}
	}
	class ptitard extends Pokemon{
		public $evolution=array(2 => 'tetarte');
		public function __construct($nom='ptitard'){
			parent::__construct($nom, 10, 'eau', '15', '6', '6', 'ptiiiiiitard !!!', '060.gif');
		}
	}
	class mrmime extends Pokemon{
		public function __construct($nom='mrmime'){
			parent::__construct($nom, 11, 'psy', '17', '7', '6', 'Mr miiiiime !!!', '122.gif');
		}
	}
	class staross extends Pokemon{
		public function __construct($nom='staross'){
			parent::__construct($nom, 12, 'eau', '25', '12', '10', 'starooooss !!!', '123.gif');
		}
	}
	class stary extends Pokemon{
		public $evolution=array(2 => 'staross');
		public function __construct($nom='stary'){
			parent::__construct($nom, 12, 'eau', '17', '7', '6', 'staryy !!!', '120.gif');
		}
	}