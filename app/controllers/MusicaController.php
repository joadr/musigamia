<?php

class MusicaController extends Controller {

    public function get_mp3($id) {
        $mp3 = 'http://www.goear.com/action/sound/get/'.$id;
        return Redirect::to($mp3);
    }

    public function obtener_descarga($id, $nombre = 'noArtist - noName') {
    	$mp3 = 'http://www.goear.com/action/sound/get/'.$id;
    	$headers = get_headers($mp3, 1);
    	$headers2 = get_headers($headers['location']);
    	//var_dump($headers2);
    	foreach($headers2 as $cabecera){
    		header($cabecera);
    	}
    	header('Content-Type: application/octet-stream');
    	header('Content-Disposition: attachment; filename="'.$nombre.".mp3");
    	readfile($headers['location']);
    	/*$headers['Content-Type'] = 'application/octet-stream';
    	$headers['Content-Disposition'] = 'attachment; filename='.basename($nombre);
    	unset($headers[0]);
    	unset($headers[1]);
    	unset($headers['Date']);
    	unset($headers['Set-Cookie']);
    	unset($headers['Expires']);
    	unset($headers['Server']);
    	unset($headers['Last-Modified']);
    	unset($headers['ETag']);

    	$headers['Connection'] = $headers['Connection'][1];
    	$headers['Cache-Control'] = $headers['Cache-Control'][1];
    	$headers['Content-Length'] = $headers['Content-Length'][1];
    	//dd($headers);
    	foreach($headers as $head => $header){
    		header($head.': '.$header);
    		//print_r($header);
    	}
    	readfile($headers['Location']);*/
      	//return Response::download(readfile($mp3), $nombre);
    }

}