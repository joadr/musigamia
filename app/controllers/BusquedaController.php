<?php
class BusquedaController extends BaseController {

	public function buscar($q, $p = 0)
	{
		$busqueda = str_replace(" ", "-", $q);
		$link = "http://www.goear.com/apps/iphone/search_songs_json.php?q=".$busqueda."&p=".$p;
		$sgtepag = "http://www.goear.com/apps/iphone/search_songs_json.php?q=".$busqueda."&p=".++$p;

		$colecciono_json[] = $this::get_url_contents($link);
		if($colecciono_json[0] == "[0]"){
			return '[]';
		}
		$colecciono_json[] = $this::get_url_contents($sgtepag);

		$coleccion = array_merge(json_decode($colecciono_json[0]), json_decode($colecciono_json[1]));

		return $coleccion;
	}

}