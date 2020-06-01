<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

</body>
</html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Musigamia 3.0</title>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://jplayer.org/latest/js/jquery.jplayer.min.js"></script>
		<script type="text/javascript" src="http://jplayer.org/latest/js/jplayer.playlist.min.js"></script>
		<script type="text/javascript" src="http://jplayer.org/latest/js/jquery.jplayer.inspector.js"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<!--<script type="text/javascript" src="js/jquery.infinitescroll.min.js"></script>-->
		<link href="css/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
		<link href="http://bootswatch.com/slate/bootstrap.min.css" rel="stylesheet" type="text/css" />
	</head>
	<script type="text/javascript">
	$(document).ready(function() {
		// The Jplayer Playlist
		window.myPlaylist = new jPlayerPlaylist({
			jPlayer: "#jquery_jplayer_2",
			cssSelectorAncestor: "#jp_container_2"
		},
		{
			playlistOptions: {
				enableRemoveControls: true
			},
			swfPath: "js",
            solution: 'html',
			supplied: "mp3",
			preload: 'metadata',
			errorAlerts: false,
			warningAlerts: false,
			wmode: "window",
			smoothPlayBar: true,
			keyEnabled: true
		});
		myPlaylist.option("enableRemoveControls", true); // Set option
		$('#jquery_jplayer_2').jPlayer("volume", 1);

		// Download button
		$("#jquery_jplayer_2").bind($.jPlayer.event.play, function(event) {
			var current = myPlaylist.current;
			var playlist = myPlaylist.playlist;
			$.each(playlist, function(index, object) {
				if(index == current) {
					//$("#cancion").html(object.title + " - " + object.artist + " -- " + object.mp3);
					CancionActual = object.mp3;
					CancionId = obtenerId(CancionActual);
					$("#downloadSong").attr("href", "{{ url('getdownload') }}/" + CancionId + "/" + object.title + " - " + object.artist);
				}
			});
		});

		// Función para obtener el id de la canción
		function obtenerId(url){
			var exploded = url.split('/');
			var id = exploded[4];
			return id;
		}
		// Función para guardar las playlists.
		function guardarPlaylist(){
			var lista = myPlaylist.playlist; // Se define el reproductor
			var titulos = new Array(); // Se crean los arrays donde vamos a meter toda la info
			var artistas = new Array();
			var links = new Array();
			$.each(lista, function(index, object) { // Metemos toda la info en los arrays
				titulos.push(object.title);
				artistas.push(object.artist);
				links.push(object.mp3);
			});
			titles = titulos.join(";"); // Separamos los arrays en un string, para meterlos en localStorage
			artists = artistas.join(";");
			urls = links.join(";");
			if(titles != ""){
				localStorage.title = titles; // Metemos las cosas en el localStorage
				localStorage.artist = artists;
				localStorage.mp3 = urls;
				alert('Playlist saved correctly');
			} else {
				alert('There are no songs saved in current playlist');
			}
		}

		// Función para cargar las playlists guardadas
		function cargarPlaylist(){
			// Verificamos si hay algo en el localStorage
			if (localStorage['title']) {
				var titulos = localStorage.title.split(";"); // Creamos un array, con las cosas en el localStorage
				var artistas = localStorage.artist.split(";");
				var links = localStorage.mp3.split(";");
				//alert(titulos.length);
				//alert(titulos.serialize());
				for(i=0;i<=titulos.length-1; i++){ // Creamos un bucle para cargar las canciones
					myPlaylist.add({
						title: titulos[i],
						artist: artistas[i],
						mp3: links[i]
					});
				}
			} else {
				alert('No songs in saved playlist'); // Mensaje en caso de que no haya nada en localStorage
			}
		}

		// Función para borrar las playlist guardadas
		function borrarPlaylist(){
			if(localStorage["title"]){
				delete localStorage.title;
				delete localStorage.artist;
				delete localStorage.mp3;
				alert("Playlist deleted correctly");
			} else {
				alert("There is no playlist saved");
			}
		}

        function contar(){
            var i = 0;
            var lista = myPlaylist.playlist; // Se define el reproductor
            $.each(lista, function(index, object) {
                i=i+1;
            });
            return i;
        }

		$('body').on('click', '.song', function(event) {
			event.preventDefault();
			window.myPlaylist.add({
				title: $(this).attr("titulo"),
				artist: $(this).attr("grupo"),
				mp3:"{{url('getmp3')}}/"+$(this).attr("id")
			});
			//$('#Added').popup("open", { history: false, positionTo: 'window'}).delay(800).queue(function(next) { $(this).popup("close"); next() });
			var count = contar();
            if(count == 1){
                $("#jquery_jplayer_2").jPlayer("play"); 
            }
		});

		$("#botonGuardar").click(function() {
			guardarPlaylist();
		});
		$("#botonCargar").click(function() {
			cargarPlaylist();
		});
		$("#botonBorrar").click(function() {
			borrarPlaylist();
		});
		var showPlaylistOptions = 0;
		$("#showHide").click(function (){
			if(showPlaylistOptions == 0){
				//$("#showHideDiv").show('slow');
				$("#showHideDiv").slideDown('fast');
				showPlaylistOptions = 1;
			} else {
				//$("#showHideDiv").hide('slow');
				$("#showHideDiv").slideUp('fast');
				showPlaylistOptions = 0;
			}
		});

		/* aquí va lo nuevo */
		$('.reproductor').on('click', function(event){
			event.preventDefault();
			$('.player').show();
			$('.contenido').hide();
		});

		$('.search').on('click', function(event){
			event.preventDefault();
			$('.player').hide();
			$('.contenido').show();
		});

		$('.busqueda').on('submit', function(event){
			event.preventDefault();
			window.pagina = 0;
			buscar();
		});
		function buscar(){
			$.getJSON( "{{url('search')}}/" + $('.busqueda').find('input[type=search]').val() + '/' + pagina, function( data ) {
				var items = [];
			  	if(data.length == 0){
			  		items.push('<span class="text-danger">No hay canciones para mostrar</span>');
				} else {
					$.each( data, function( key, val ) {
					items.push( "<a href='#' class='song list-group-item' id='" + val.id + "' titulo='"+ val.title +"' grupo='"+ val.artist+"'><img src='" + val.imgpath +"' width='80' height='80' class='imgpath' />" + val.title + " - " + val.artist + "</a>" );
					});
				}
				$('.my-new-list').html(items.join( "" ));
			})
		}

		$('.anterior').on('click', function(){
			if(pagina >= 2){
				window.pagina = window.pagina-2;
				buscar();
			}
		});
		$('.siguiente').on('click', function(){
			if(pagina >= 0){
				window.pagina = window.pagina+2;
				buscar();
			}
		});
		$('#downloadSong').tooltip();


		/* FIN */
	});
	</script>
	<style>
		.imgpath{
			margin-right: 15px;	
		}
		#jp_container_2{
			margin: 0 auto;
		}
	</style>
	<body>
		<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
		  <div class="container">
		    <div class="navbar-header">
		      <!-- <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button> -->
		      <a href="#" class="navbar-brand">Musigamia 3.0</a>
		    </div>
		   <!--  <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation"> -->
		      <ul class="nav navbar-nav">
		        <li class="active">
		          <a href="" class="search">Search</a>
		        </li>
		        <li>
		          <a href="#" class="reproductor">Player</a>
		        </li>
		        <li>
		          <a href="#" id='downloadSong' target="_blank" title="Descargar canción en curso" data-toggle="tooltip" data-placement="right">Descargar</a>
		        </li>
		      </ul>
		      <ul class="nav navbar-nav navbar-right">
		        <li><a href="#">Playlists</a></li>
		        <li><a href="#">Button</a></li>
		      </ul>
		    <!-- </nav> -->
		  </div>
		</header>
		<div id="jquery_jplayer_2" class="jp-jplayer"></div>
		<div class="container" id="contenedor">
			<div class="contenido">
				<form method="POST" action="{{ route('search') }}" class="busqueda">
					<input type="search" class="form-control" name="q" placeholder="Buscar canción o artista" />
					<input type="submit" class="btn btn-primary" value="Buscar"/>
				</form>
			</div>
			<div class="row player" style="display: none">
				<div class="col-md-8">
					<!-- JPlayer Starts -->
					<div id="jp_container_2" class="jp-audio">
					    <div class="jp-type-playlist">
					        <div class="jp-gui jp-interface" style="overflow:hidden">
					            <ul class="jp-controls">
					                <li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
					                <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
					                <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
					                <li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
					                <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
					                <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
					                <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
					                <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
					            </ul>
					            <div class="jp-progress">
					                <div class="jp-seek-bar">
					                    <div class="jp-play-bar"></div>
					                </div>
					            </div>
					            <div class="jp-volume-bar">
					                <div class="jp-volume-bar-value"></div>
					            </div>
					            <div class="jp-time-holder">
					                <div class="jp-current-time"></div>
					                <div class="jp-duration"></div>
					            </div>
					            <ul class="jp-toggles">
					                <li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a></li>
					                <li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off">shuffle off</a></li>
					                <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
					                <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
					            </ul>
					        </div>
					        <div class="jp-playlist">
					            <ul>
					                <li></li>
					            </ul>
					        </div>
					        <div class="jp-no-solution">
					            <span>Update Required</span>
					            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
					        </div>
					    </div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="well" style="">
						Acciones sobre la playlist actual
						<button type="button" id="botonGuardar" class="btn btn-info btn-block">Guardar Playlist</button>
						<button type="button" id="botonCargar" class="btn btn-default btn-block">Cargar Playlist</button>
					</div>
				</div>
			</div>
			<!-- JPlayer ENDS -->
			<div class="my-new-list list-group"></div>
		</div>
		<div class="pages">
		<center><button class="btn btn-primary anterior"><< Página anterior</button><button class="btn btn-primary siguiente">Siguiente página >></button></center>
		</div>
		<center style="margin-top: 20px;">
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- Musigamia -->
			<ins class="adsbygoogle"
			     style="display:inline-block;width:970px;height:90px"
			     data-ad-client="ca-pub-9481378689557730"
			     data-ad-slot="9970635406"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</center>
	</body>
</html>