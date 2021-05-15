<?php	
	session_start();
	@$usuario = $_SESSION['user_id'];
	@$nome_usuario = $_SESSION['user_name'];

	//Conexao com o banco.
	include_once('model/Conexao.php');
	$conn = new Conexao;
	$con = $conn->conectar();

	/*-------------------------------------------------Tudo sobre eventos---------------------------------------------*/
	/*----------------------------------------------------------------------------------------------------------------*/

	//Select que pega os dados do banco de eventos daquele usuario.
	$sql_eventos = "SELECT * FROM `eventos` where id_usuario = '$usuario'";
	$stmt = $con->prepare($sql_eventos);
	$stmt->execute();
	$dados_events_default = $stmt->fetchAll();
	/*----------------------------------------------------------------------------------------------------------------*/

	/*-----------------------------------------Tudo sobre eventos compartilhados--------------------------------------*/
	/*----------------------------------------------------------------------------------------------------------------*/

	//Select que pega os os ids dos eventos que foram compartilhados.
	$sql_events_shared = "SELECT id_evento_compartilhado FROM eventos_shared WHERE id_usuario_solicitado = '$usuario'";
	$stmt = $con->prepare($sql_events_shared);
	$stmt->execute();
	$id_events_shared = $stmt->fetchAll();

	//Select que pega as permissoes que aquele usuario possui.
	$sql_permissoes = "SELECT permissao FROM eventos_shared WHERE id_usuario_solicitado = '$usuario'";
	$stmt = $con->prepare($sql_permissoes);
	$stmt->execute();
	$permissoes_array = $stmt->fetchAll();

	//Aqui eu crio dois arrays para receber apenas os valores dos ids de eventos compartilhados/suas permissoes.
	$arrayEventosShared = array();
	$arrayPermissoes = array();

	//Foreach para popular os arrays criados acima, para assim pegar apenas o valor dos ids dos eventos compartilhados.
	foreach($id_events_shared as $agrvaipls) {
		//Aqui eu atribuo ao array criado ali em cima, os ids dos eventos compartilhados.
		$arrayEventosShared[] = $agrvaipls['id_evento_compartilhado'];
	}
	//Foreach para popular os arrays criados acima, para assim pegar apenas o valor das permissoes dos eventos compartilhados.
	foreach($permissoes_array as $agrvaipls2) {
		//Aqui eu atribuo ao array criado ali em cima, os ids dos eventos compartilhados.
		$arrayPermissoes[] = $agrvaipls2['permissao'];
	}

	//Aqui eu converto os dois novos arrays em strings.
	$string = implode(",", $arrayEventosShared);
	$stringPermissoes = implode(",", $arrayPermissoes);

	//Aqui eu dou o select dos ids de eventos compartilhados, na tabela de eventos, para assim trazer todos os eventos compartilhados.
	$sql_eventsIds = "SELECT * FROM `agendaweb`.`eventos` WHERE id_evento IN ($string)";
	$stmt = $con->prepare($sql_eventsIds);
	$stmt->execute();
	$dados_events_shared = $stmt->fetchAll();

	/*----------------------------------------------------------------------------------------------------------------*/

	//Para juntar os dois arrays em apenas um, que eu uso la em baixo para exibir no calendario.
	$dados_eventos = array_merge($dados_events_default, $dados_events_shared);

	
	/*-----------------------------------------Tudo sobre a foto de usuario-------------------------------------------*/
	/*----------------------------------------------------------------------------------------------------------------*/

	//Buscando os dados do usuario para exibir imagem do mesmo.
	//Select para buscar a data e o nome da foto, para buscar no diretorio.
	$sql1 = "SELECT data_cadastro, foto_usuario FROM `usuario` WHERE id_usuario = '$usuario'";
	$stmt = $con->prepare($sql1);
	$stmt->execute();
	$dados = $stmt->fetch();
	//Atribuindo o fetch para uma variavel
	$nomeFoto = $dados['foto_usuario'];
	$dataFoto = $dados['data_cadastro'];

	/*----------------------------------------------------------------------------------------------------------------*/

	/*---------------------------------------Tudo sobre o wallpaper do usuario----------------------------------------*/
	/*----------------------------------------------------------------------------------------------------------------*/

	//Para buscar os dados de wallpaper de fundo (DA AGENDA/PERFIL) do usuario.
	$sqlWallpaper = "SELECT wallpaper_usuario FROM `usuario` WHERE id_usuario = '$usuario'";
	$stmt = $con->prepare($sqlWallpaper);
	$stmt->execute();
	$dadosWallpaper = $stmt->fetch();
	//Atribuindo o fetch para uma variavel
	$WallpaperUsuarioId = $dadosWallpaper['wallpaper_usuario'];

	//Select dos contatos, para exibir na lista dinamica e outros.
	$sql_select_contatos = "SELECT * FROM `contatos` WHERE id_usuario = '$usuario'";
	$stmt = $con->prepare($sql_select_contatos);
	$stmt->execute();
	$dados_select_contatos = $stmt->fetchAll();

	//Para verificar se o usuario salvou um wallpaper, se sim, ele mostra o mesmo.
	switch (@$WallpaperUsuarioId) {
		case '1':
			$class = "wallpaper1";
		break;
		case '2':
			$class = "wallpaper2"; 
		break;
		case '3':
			$class = "wallpaper3";
		break;
		case '4':
			$class = "wallpaper4";
		break;
		case '5':
			$class = "wallpaper5";
		break;
		case '6':
			$class = "wallpaper6";
		break;
		
		//Caso nao tenha nenhum desses numeros gravados no banco, ele mostra um default.
		default:
			$class = 'wallpaperPadrao';
		break;
	} 

	/*----------------------------------------------------------------------------------------------------------------*/

	/*---------------------------------------Tudo sobre os contatos do usuario----------------------------------------*/
	/*----------------------------------------------------------------------------------------------------------------*/
	//Para mostrar os dados daquele contato, quando o usuario quiser alterar os dados do contato.
	@$id_contato_editar = $_GET['id_contato'];
	$sql_editar_contatos = "SELECT * from contatos WHERE id_contato = '$id_contato_editar'";
	$stmt = $con->prepare($sql_editar_contatos);
	$stmt->execute();
	$dados_contatos_editar = $stmt->fetch();
	$nomeContatoEditar = $dados_contatos_editar['nome_contato'];
	$emailContatoEditar = $dados_contatos_editar['email_contato'];

	/*----------------------------------------------------------------------------------------------------------------*/
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset='utf-8'>
		<title>Agenda Web</title>
		<!-- FaviCon -->
		<link rel="shorcut icon" href="tools/img/favicon.ico">
		<!------------------------------------------------------------------------------------------------>
		<?php if(!empty($usuario)){ 
		//Para nao mostrar imagem de fundo, ou seja, css, quando ele estiver tentando acessar pelo URL.	
		?>
		<!--Referencia do Style da Agenda-->
		<link href='tools/css/styleagenda.css' rel='stylesheet'>
		<?php } ?>
		<!------------------------------------------------------------------------------------------------>
		<!--Referencia do bootstrap-->
		<link rel="stylesheet" type="text/css" href="tools/lib/bootstrap/css/bootstrap.min.css">
		<!--Referencia CSS que o calendario usa-->
		<link href='tools/css/fullcalendar.min.css' rel='stylesheet'>
		<link href='tools/css/fullcalendar.print.min.css' rel='stylesheet' media='print'>
		<!--Scrips do calendario-->
		<script src='tools/js/moment.min.js'></script>
		<!--JQuery-->
		<script src="tools/lib/jquery/jquery-3.3.1.min.js"></script>
		<script src='tools/js/fullcalendar.min.js'></script>
		<script src='tools/js/pt-br.js'></script>
		<!-- Bootstrap JS -->
		<script src="tools/lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="tools/js/bootbox.min.js"></script>
		<!--Script dos alertas de Adicionar, Atualizar e remover eventos.-->
		<link rel="stylesheet" href="tools/lib/alertifyjs/css/alertify.min.css" />
		<link rel="stylesheet" href="tools/lib/alertifyjs/css/themes/default.min.css" />
		<script src="tools/lib/alertifyjs/alertify.min.js"></script>
		<!--Fonts Awesome-->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
		<!--Scrips Gerais do topo-->
		<script src="tools/js/scriptstop.js"></script>
		<!--Script do Calendario-->
		<script>
			$(document).ready(function() {
   				var calendar = $('#calendar').fullCalendar({
					header: {
						left: 'prev,next today',
						center: 'title',
						right: 'month,agendaWeek,agendaDay,listWeek'
					},
					defaultDate: Date(),
					//Vou Usar depois. defaultView: 'agendaDay',
    				nowIndicator: true,
					navLinks: true, 
					eventLimit: true, 
					//Para cadastrar clicando no dia do calendario.
					selectable: true,
					selectHelper: true,
					editable: true,

					//Para alterar evento quando o usuario mover o evento.
					eventDrop: function(event){
						//Atribuindo id do evento, a cada vez que ele clica.
						var id_evento = event.id

						//Aqui eu crio um array JavaScript, vindo de um array de um Select do PHP.
						var string_array_eventos = new Array(<?php echo $string; ?>);
						var string_array_permissoes = new Array(<?php echo $stringPermissoes; ?>);

						//Aqui eu verifico se o id que o usuario clicou, esta presente no array de eventos compartilhados.
						var procuraArray = string_array_eventos.indexOf(parseInt(id_evento));
						
						//Funcao para saber se o array esta vazio ou nao. Quando ele possui apenas um valor é considerado vazio.
						function isEmptyObject(obj) {
							var name;
							for (name in obj) {
								return false;
							}
							return true;
						}

						var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
						var end = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD HH:mm:ss");

						//Se a busca for empty, eu mostro os botoes.
						if(procuraArray == -1 && isEmptyObject(string_array_eventos) == true && string_array_eventos.length != parseInt(id_evento)){
							$.ajax({
								url:"control/controle_evento.php?op=altDragAndDrop",
								type:"POST",
								data:{start:start, end:end, id_evento:id_evento},
								success:function(){
									alertify.warning('Evento Atualizado!');
								}
							});

							console.log("1");
						}else if(procuraArray > -1){
							//Aqui eu crio um array JavaScript, vindo de um array de um Select do PHP.
							var string_array_permissoes = new Array(<?php echo $stringPermissoes; ?>);

							//Aqui eu busco a permissao daquele evento, dependendo da posicao que o evento esta no array.
							var posicao = string_array_permissoes[procuraArray];

							//Aqui eu verifico se a permissao e igual a 1 ou 0.
							if(posicao == 1){
								$.ajax({
									url:"control/controle_evento.php?op=altDragAndDrop",
									type:"POST",
									data:{start:start, end:end, id_evento:id_evento},
									success:function(){
										alertify.warning('Evento Atualizado!');
									}
								});
								console.log("2=1");
							}else{
								//Se for igual a 0 o evento nao atualiza.
								alertify.error('Você não possui permissão para editar este evento!');
								alertify.error('Dados não foram alterados!');
								console.log("2=0");
							}
						}else if(procuraArray == -1 && isEmptyObject(string_array_eventos) == true && string_array_eventos.length == parseInt(id_evento)){
							//Aqui eu verifico se a permissao e igual a 1 ou 0.
							//Lembrando sempre que quando um array tem apenas um dado, o tamanho do array passa a ter este valor.
							if(string_array_permissoes.length == 1){
								$.ajax({
									url:"control/controle_evento.php?op=altDragAndDrop",
									type:"POST",
									data:{start:start, end:end, id_evento:id_evento},
									success:function(){
										alertify.warning('Evento Atualizado!');
									}
								});
							}else{
								//Se for igual a 0 o evento nao atualiza.
								alertify.error('Você não possui permissão para editar este evento!');
								alertify.error('Dados não foram alterados!');
								console.log("3=0");
							}
						}else{
							$.ajax({
								url:"control/controle_evento.php?op=altDragAndDrop",
								type:"POST",
								data:{start:start, end:end, id_evento:id_evento},
								success:function(){
									alertify.warning('Evento Atualizado!');
								}
							});

							console.log("1/2");
						}
					},

					//Quando clicar no evento exibe informacoes detalhadas do mesmo.
					eventClick: function(event, jsEvent, view) {
						//Atribuindo id do evento, a cada vez que ele clica.
						var id_evento = event.id

						//Aqui eu crio um array JavaScript, vindo de um array de um Select do PHP.
						var string_array_eventos = new Array(<?php echo $string; ?>);
						var string_array_permissoes = new Array(<?php echo $stringPermissoes; ?>);

						//Aqui eu verifico se o id que o usuario clicou, esta presente no array de eventos compartilhados.
						var procuraArray = string_array_eventos.indexOf(parseInt(id_evento));
						
						//Funcao para saber se o array esta vazio ou nao. Quando ele possui apenas um valor é considerado vazio.
						function isEmptyObject(obj) {
							var name;
							for (name in obj) {
								return false;
							}
							return true;
						}

						//if(isEmptyObject(string_array_eventos) == true){
							//console.log("Array vazio.");
						//}else{
							//console.log("Array Com dados.");
						//}

						var botaoEditar = document.getElementById("botaoEditar");
						var botaoExcluir = document.getElementById("botaoExcluir");

						var dtPermissao = document.getElementById('dtPermissao');
						var dtPermissaoCampo = document.getElementById('dtPermissaoCampo');
						
						var selectContatosEditar = document.getElementById('selectContatosEditar');

						//Se a busca for empty, eu mostro os botoes.
						if(procuraArray == -1 && isEmptyObject(string_array_eventos) == true && string_array_eventos.length != parseInt(id_evento)){
							dtPermissao.hidden=true;
							dtPermissaoCampo.hidden=true;
							botaoEditar.disabled=false;
							botaoExcluir.disabled=false;
							selectContatosEditar.disabled = false;

							document.getElementById('botaoEditar').className = 'btn btn-info';
							document.getElementById('botaoExcluir').className = 'btn btn-danger';

							console.log("1");
						}else if(procuraArray > -1){
							//Aqui eu crio um array JavaScript, vindo de um array de um Select do PHP.
							var string_array_permissoes = new Array(<?php echo $stringPermissoes; ?>);

							//Aqui eu busco a permissao daquele evento, dependendo da posicao que o evento esta no array.
							var posicao = string_array_permissoes[procuraArray];

							//Aqui eu verifico se a permissao e igual a 1 ou 0.
							if(posicao == 1){
								//Se for igual a 1 eu mostro os botoes.
								dtPermissao.hidden=true;
								dtPermissaoCampo.hidden=false;
								selectContatosEditar.disabled = true;

								botaoEditar.disabled=false;
								botaoExcluir.disabled=false;
								document.getElementById('botaoEditar').className = 'btn btn-info';
								document.getElementById('botaoExcluir').className = 'btn btn-danger';
								console.log("2=1");
							}else{
								//Se for igual a 0 eu desabilito os botoes de altera/excluir.
								document.getElementById('botaoEditar').className = 'btn btn-secondary';
								document.getElementById('botaoExcluir').className = 'btn btn-secondary';

								dtPermissao.hidden=false;
								dtPermissaoCampo.hidden=false;
								botaoEditar.disabled=true;
								botaoExcluir.disabled=true;
								selectContatosEditar.disabled = true;

								console.log("2=0");
							}
						}else if(procuraArray == -1 && isEmptyObject(string_array_eventos) == true && string_array_eventos.length == parseInt(id_evento)){
							//Aqui eu verifico se a permissao e igual a 1 ou 0.
							//Lembrando sempre que quando um array tem apenas um dado, o tamanho do array passa a ter este valor.
							if(string_array_permissoes.length == 1){
								//Se for igual a 1 eu mostro os botoes.
								dtPermissao.hidden=true;
								dtPermissaoCampo.hidden=false;
								botaoEditar.disabled=false;
								botaoExcluir.disabled=false;
								selectContatosEditar.disabled = true;

								document.getElementById('botaoEditar').className = 'btn btn-info';
								document.getElementById('botaoExcluir').className = 'btn btn-danger';

								console.log("3=1");
							}else{
								//Se for igual a 0 eu desabilito os botoes de altera/excluir.
								document.getElementById('botaoEditar').className = 'btn btn-secondary';
								document.getElementById('botaoExcluir').className = 'btn btn-secondary';


								selectContatosEditar.disabled = true;
								dtPermissao.hidden=false;
								dtPermissaoCampo.hidden=false;
								botaoEditar.disabled=true;
								botaoExcluir.disabled=true;
								console.log("3=0");
							}
						}else{
							dtPermissao.hidden=true;
							dtPermissaoCampo.hidden=true;
							botaoEditar.disabled=false;
							botaoExcluir.disabled=false;
							selectContatosEditar.disabled = false;

							document.getElementById('botaoEditar').className = 'btn btn-info';
							document.getElementById('botaoExcluir').className = 'btn btn-danger';


							console.log("1/2");
						}

						var botaoformEditar = document.getElementById('botaoformEditar');
						botaoformEditar.disabled = false;

						//Text, para usar para exibir as infoamcoes, mas nao no formulario de edicao.
						$('#visualizar #id').text(event.id);
						$('#visualizar #title').text(event.title);
						$('#visualizar #desc').text(event.classNames);
						$('#visualizar #start').text(event.start.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #end').text(event.end.format('DD/MM/YYYY HH:mm:ss'));

						//Value, para usar no formulario de editar eventos.
						$('#visualizar #id').val(event.id);
						$('#visualizar #title').val(event.title);
						$('#visualizar #desc').val(event.classNames);
						$('#visualizar #color').val(event.color);

						$('#visualizar #startdate').val(event.start.format('YYYY-MM-DD'));
						$('#visualizar #starttime').val(event.start.format('HH:mm'));

						$('#visualizar #enddate').val(event.end.format('YYYY-MM-DD'));

						$('#visualizar #endtime').val(event.end.format('HH:mm'));

						$('#visualizar').modal('show');
					},

					//Para cadastrar o evento, aqui eu passo os valores dos campos de data e hora. isso muda conforme o dia em que o usuario clica.
					select: function(start, end){
						//Para data.
						$('#cadastrar #startdateCadastro').val(moment(start).format('YYYY-MM-DD'));
						$('#cadastrar #enddateCadastro').val(moment(end).format('YYYY-MM-DD'));
						//Para hora.
						$('#cadastrar #starttimeCadastro').val(moment(start).format('HH:mm'));
						$('#cadastrar #endtimeCadastro').val(moment(end).format('HH:mm'));

						var botaoCadastra = document.getElementById('botaoCadastra');
						botaoCadastra.disabled = false;

						$('#cadastrar').modal('show');						
					},

					events: [
						<?php 
							//Para percorrer a array de eventos criado no topo da pagina.
							foreach($dados_eventos as $eventos){ ?>
							{
								id: '<?php echo $eventos['id_evento']; ?>',
								title: '<?php echo $eventos['nome_evento']; ?>',
								classNames: '<?php echo $eventos['desc_evento']; ?>',
								start: '<?php echo $eventos['inicio_evento']; ?>',
								end: '<?php echo $eventos['final_evento']; ?>',
								color: '<?php echo $eventos['color']; ?>',
								textColor: 'white',
							},
							<?php } ?>
							]

				});
			});

		</script>
	</head>
	<body class="<?php echo $class; ?>">
		<!--Caso o usuario tente acessar a agenda.php pelo URL, ele nao deixa o usuario fazer isso-->
		<?php if(empty($usuario)){ ?>
			<!--E mostrar um alerta dizendo que ele nao pode acessar, e o callback retorna ele a pagina anterior-->
			<script>
				bootbox.confirm({
					message: "É necessário logar para acessar esta página!",
					callback: function (result) {
						if (result === true) {
							history.back(-1)
						}else{
							history.back(-1)
						}
					}
				});
				alertify.error('Acesso Negado!');
			</script>
		<!--As chaves fecham no final do codigo-->
		<?php }else{ ?>
		<!------------------------------------------------------------------------------------------>
		<!--Header-->
		<header>
			<div class="container">
				<!-- NAVBAR HEADER -->
				<nav class="navbar navbar-expand-lg navbar-light">
					<div class="logo-header container">
						<a class="navbar-brand" href="agenda.php" style="color: white; position:relative; right:20px;"><img id="logotipo" src="tools/img/logo.png" alt="" width="220px" height="100px"></a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"></button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item active">
								<a class="nav-link header-ul1" href="control/logout.php" style="color: white">Página Inicial</a>
							</li>
							<li class="nav-item dropdown">
								<a style="color: white" class="nav-link dropdown-toggle header-ul2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Funções
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
									<a class="dropdown-item" href="view/relatorio.php">Relatórios</a>
								</div>
							</li>
							<li class="nav-item dropdown">
								<a style="color: white" class="nav-link dropdown-toggle header-ul3" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Guias
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
									<a class="dropdown-item" href="#">Cadastrar Eventos<span class="span-top">
										<video width="530px" height="300px" loop autoplay muted>
											<source src="tools/img/Guias/Cadastrodeeventos.mp4" type="video/mp4">
										</video>
									</span></a>
									<a class="dropdown-item" href="#">Editar Eventos<span class="span-top">
										<video width="530px" height="300px" loop autoplay muted>
											<source src="tools/img/Guias/Editareventos.mp4" type="video/mp4">
										</video>
									</span></a>
									<a class="dropdown-item" href="#">Excluir Eventos<span class="span-top">
										<video width="530px" height="300px" loop autoplay muted>
											<source src="tools/img/Guias/Excluireventos.mp4" type="video/mp4">
										</video>
									</span></a>
									<a class="dropdown-item" href="#">Mover Eventos<span class="span-top">
										<video width="530px" height="300px" loop autoplay muted>
											<source src="tools/img/Guias/Movereventos.mp4" type="video/mp4">
										</video>
									</span></a>
									<a class="dropdown-item" href="#">Selecionar vários dias<span class="span-top">
										<video width="530px" height="300px" loop autoplay muted>
											<source src="tools/img/Guias/Variosdias.mp4" type="video/mp4">
										</video>
									</span></a>
								</div>
							</li>
							<li class="nav-item dropdown">
								<a style="color: white" class="nav-link dropdown-toggle header-ul4" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Papeis de Parede
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
									<a class="dropdown-item" href="control/controle_usuario.php?id=wallpaper&picture=default">Pôr do sol<span class="span-top"><img src="tools/img/backgroundAgenda/backgroundAgenda.jpg" width="600" height="400px"></span></a>
									<a class="dropdown-item" href="control/controle_usuario.php?id=wallpaper&picture=1">Estrada<span class="span-top"><img src="tools/img/backgroundAgenda/backgroundAgenda1.jpg" width="600" height="400px"></span></a>
									<a class="dropdown-item" href="control/controle_usuario.php?id=wallpaper&picture=2">Montanha<span class="span-top"><img src="tools/img/backgroundAgenda/backgroundAgenda2.jpg" width="600" height="400px"></span></a>
									<a class="dropdown-item" href="control/controle_usuario.php?id=wallpaper&picture=3">Oceano<span class="span-top"><img src="tools/img/backgroundAgenda/backgroundAgenda3.jpg" width="600" height="400px"></span></a>
									<a class="dropdown-item" href="control/controle_usuario.php?id=wallpaper&picture=4">Neblina<span class="span-top"><img src="tools/img/backgroundAgenda/backgroundAgenda4.jpg" width="600" height="400px"></span></a>
									<a class="dropdown-item" href="control/controle_usuario.php?id=wallpaper&picture=5">Nevasca<span class="span-top"><img src="tools/img/backgroundAgenda/backgroundAgenda5.jpg" width="600" height="400px"></span></a>
									<a class="dropdown-item" href="control/controle_usuario.php?id=wallpaper&picture=6">Topo nevado<span class="span-top"><img src="tools/img/backgroundAgenda/backgroundAgenda6.jpg" width="600" height="400px"></span></a>
								</div>
							</li>
							<li class="nav-item dropdown">
								<a style="color: white" class="nav-link dropdown-toggle header-ul5" class="" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Contatos
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
									<a class="dropdown-item" onclick="$('#visualizarContatos').modal('show');"><input type="button" class="btn btn-outline" value="Visualizar seus contatos"></a>
									<a class="dropdown-item" onclick="$('#cadastrarContatos').modal('show');"><input type="button" class="btn btn-outline" value="Cadastrar novos contatos"></a>
								</div>
							</li>
						</ul>
						<div class="row float-right">
							<div class="foto-usuario" style="margin-right: 15px; margin-top: 1px;">
								<a href="view/perfil.php">
									<!-- Para selecionar se o usuario enviou uma foto ou nao, se sim mostra a mesma, se nao, mostra uma foto default-->
									<?php if(!empty($dados['foto_usuario'])){ 
										//Diretorio se o usuario tiver uma foto.
										$diretorio = 'img_usuarios/'.$dataFoto.'/'.$usuario.'/'.$nomeFoto;
											?>
										<img src="<?php echo "$diretorio"; ?>" width="60px" height="60px" class="foto-perfil">
									<?php } else { 
										//Diretorio se usuario nao possuir uma foto.
										$diretorio = 'tools/img/logo-primary.jpg';
										?> 
										<img src="<?php echo "$diretorio"; ?>" width="60px" height="60px" class="foto-perfil">
									<?php } ?>
								</a>
							</div>
							<div class="botao-logout" style="margin-top: 13px;">
								<a href="control/logout.php?logout=on"><input type="button" id="botao-logout" class="btn btn-outline-danger" value="Logout"></a></p> 
							</div>
						</div>
					</div>
				</nav>	
			</div>			
		</header>
		<!--Section-->
		<section>

			<!--Div que exibe o calendario na tela-->
			<div class="transparente">
				<div id='calendar'>

				</div>
			</div>

			<!--Aqui Comeca o modal de visualizar contatos-->
			<div class="modal fade" id="visualizarContatos" tabindex="-1" role="dialog" aria-labelledby="">  <!--data-backdrop="static"--> <!--Quando eu clico fora do modal ele nao desaparece!-->
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Visualizar Contatos</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body text-center">
							<table class="table table-bordered">
								<tr>
									<th>Nome do Contato</th> 
									<th>Email do Contato</th>
									<th>Alterar</th>
									<th>Excluir</th>
								</tr>
								<?php 
									foreach ($dados_select_contatos as $array_dados_select_contatos) {
								?>
								<tr>
									<td><?php echo $array_dados_select_contatos['nome_contato']; ?></td>
									<td><?php echo $array_dados_select_contatos['email_contato']; ?></td>
									<td><a href="?id=alt&id_contato=<?php echo $array_dados_select_contatos['id_contato'];?>"><i class="far fa-edit" title="Editar Contato"></i></a></td>
									<td><a style="text-decoration:none;" href="control/controle_contatos.php?id=del&id_contato=<?php echo $array_dados_select_contatos['id_contato'];?>"><i class="far fa-trash-alt" title="Excluir Contato"></i></a></td>
								</tr>
									<?php } ?>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!--Aqui acaba o modal de visualizar contatos-->	

			<!--Aqui Comeca o cadastro de contatos-->
			<div class="modal fade" id="cadastrarContatos" tabindex="-1" role="dialog">  <!--data-backdrop="static"--> <!--Quando eu clico fora do modal ele nao desaparece!-->
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title text-center">Cadastrar Contatos</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<!--FORMULARIO DE CADASTRO DE CONTATOS-->
							<form class="form-horizontal" method="POST" action="control/controle_contatos.php?id=cad">
								<input type="hidden" name="idusuario" value="<?php print($usuario) ?>">
								<div class="form-group">
									<label for="" class="">Nome do Contato</label>
									<div class="">
										<input type="text" class="form-control" id="inputNomeContato" name="nomeContato" minlength="1" maxlength="45" placeholder="" required>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="">E-Mail do Contato</label>
									<div class="">
										<input type="email" class="form-control" id="" name="emailContato" minlength="1" maxlength="45" placeholder="exemplo@exemplo.com" required>
									</div>
								</div>
								<div class="form-group">
									<div class="" style="position:relative; left:180px; top: 20px;">
										<button type="submit" class="btn btn-success">Cadastrar</button>
									</div>
								</div>
							</form>
							<!--Final do Form de cadastro de eventos-->
						</div>
					</div>
				</div>
			</div>
			<!--Aqui acaba o modal de Cadastro de Contatos.-->			

			<!--Aqui Comeca o editar dos contatos-->
			<div class="modal fade" id="editarContatos" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">  <!--data-backdrop="static"--> <!--Quando eu clico fora do modal ele nao desaparece!-->
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title text-center">Editar Contatos</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="history.go(-1)"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<form class="form-horizontal" method="POST" action="control/controle_contatos.php?id=alt">
								<input type="hidden" name="id_contato" value="<?php echo @$id_contato_editar; ?>">
								<div class="form-group">
									<label for="" class="">Nome do Contato</label>
									<div class="">
										<input type="text" class="form-control" id="inputNomeContatoEditar" value="<?php echo $nomeContatoEditar; ?>" name="nomeContato" minlength="1" maxlength="45" placeholder="exemplo" required>
									</div>
								</div>
								<div class="form-group">
									<div class="" style="position:relative; left:180px; top: 20px;">
										<button type="submit" class="btn btn-success">Salvar</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!--Aqui acaba o editar dos contatos-->		

			<?php 
			//para exibir o modal de editar contatos, quando o usuario clicar no botao para editar.
				@$top = $_GET['id'];
			if(@$top == 'alt'){ ?>
				<script>
					$('#editarContatos').modal('show');
				</script>
			<?php } ?>

			<!--Aqui Comeca o cadastro de eventos-->
			<div class="modal fade" id="cadastrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">  <!--data-backdrop="static"--> <!--Quando eu clico fora do modal ele nao desaparece!-->
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title text-center">Cadastrar Eventos</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<!--FORMULARIO DE CADASTRO DE EVENTOS-->
							<form class="form-horizontal" method="POST" action="control/controle_evento.php?op=cad">
								<input type="hidden" name="idusuario" value="<?php print($usuario) ?>">
								<div class="form-group">
									<label for="" class="">Nome do Evento</label>
									<div class="">
										<input type="text" class="form-control" id="nomeEvento" name="nomeEvento" minlength="1" maxlength="45" placeholder="" required>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="">Descrição do Evento</label>
									<div class="">
										<textarea class="form-control" name="descEvento" id="textarea" cols="30" rows="3" minlength="1" maxlength="128"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="">Cor do evento no Calendário</label>
									<div class="">
										<select name="corEvento" class="form-control" id="color">
											<option value="#616161">Selecionar</option>                
											<option style="color:#0b8043;" value="#0b8043">Verde</option>
											<option style="color:#33b679;" value="#33b679">Verde Claro</option>
											<option style="color:#f4511e;" value="#f4511e">Laranja</option>
											<option style="color:#e67c73;" value="#e67c73">Salmão</option>
											<option style="color:#d50000;" value="#d50000">Vermelho</option>
											<option style="color:#039be5;" value="#039be5">Azul</option>
											<option style="color:#3f51b5;" value="#3f51b5">Azul Escuro</option>
											<option style="color:#7986cb;" value="#7986cb">Ciano</option>
											<option style="color:#8e24aa;" value="#8e24aa">Roxo</option>
											<option style="color:#616161;" value="#616161">Cinza</option>
										</select>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="" class="">Data de Início</label><label id="labelDataInvalida1" for="" style="color:red;" hidden>&nbsp;&nbsp;Data Inválida</label>
										<input type="date" class="form-control" name="dataInicio" id="startdateCadastro" onchange="validarData(); validarHora();" required>		
									</div>
									<div class="form-group col-md-6">
										<label for="" class="">Hora de Início</label><label id="labelHoraInvalida1" for="" style="color:red;" hidden>&nbsp;&nbsp;Hora Inválida</label>
										<input type="time" class="form-control" name="horaInicio" id="starttimeCadastro" onchange="validarHora();" required>
									</div>	
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="" class="">Data de Final</label><label id="labelDataInvalida2" for="" style="color:red;" hidden>&nbsp;&nbsp;Data Inválida</label>
										<input type="date" class="form-control" name="dataFinal" id="enddateCadastro" onchange="validarData2(); validarHora();" required>
									</div>
									<div class="form-group col-md-6">
										<label for="" class="">Hora de Final</label><label id="labelHoraInvalida2" for="" style="color:red;" hidden>&nbsp;&nbsp;Hora Inválida</label>
										<input type="time" class="form-control" name="horaFinal" id="endtimeCadastro" onchange="validarHora();" required>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-8">
										<label for="" class="">Compartilhar evento com seus contatos</label>
										<select name="emailContatoShared" id="selectContatos" class="form-control" onchange="habilitarSelectPermissao();">
											<?php if(empty($dados_select_contatos)){ ?>
											<option value="">Não há contatos para mostrar...</option>
											<?php }else{ ?>
											<option value="">Selecionar contato</option>
											<?php } ?>
											<!--Aqui eu faco a lista dinamica de acordo com os contatos do usuario-->
											<?php foreach ($dados_select_contatos as $array_dados_select_contatos) { ?>
											<option value="<?php echo $array_dados_select_contatos['email_contato']; ?>"><?php echo $array_dados_select_contatos['nome_contato']; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label for="" class="" id="labelselectPermissao" hidden>Convidados podem:</label>
										<select name="permissao" id="selectPermissao" class="form-control" disabled hidden required>
											<option value="0">Ler evento</option>
											<option value="1">Ler & Editar evento</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="" style="position:relative; left:180px;">
										<button type="submit" id="botaoCadastra" class="btn btn-success" disabled>Cadastrar</button>
									</div>
								</div>
							</form>
							<!--Final do Form de cadastro de eventos-->
						</div>
					</div>
				</div>
			</div>
			<!--Aqui acaba o modal de Cadastro de Eventos.-->
			
			<!--Modal de Selecionar Eventos.-->
			<div id="visualizar" class="modal fade" tabindex="-1" role="dialog" data-keyboard="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="visualizar">						
								<dl class="dl-horizontal">
									<dt id="dtPermissaoCampo" hidden style="position:relative; left: 145px;">Evento Compartilhado</dt>
									<dt>Nome do Evento:</dt>
									<dd id="title"></dd>
									<dt>Descrição do Evento:</dt>
									<dd id="desc"></dd>
									<dt>Data e Hora de Início:</dt>
									<dd id="start"></dd>
									<dt>Data e Hora de Final:</dt>
									<dd id="end"></dd>
								</dl>
								<hr>	
								<ul class="nav" style="position:relative; left: 53px; color: gray;">
									<li class="nav-item">
										<dt id="dtPermissao" hidden>Você não tem permissão para editar este evento!</dt>
									</li>
								</ul>
								<ul class="nav" style="position:relative; left:155px;">
									<li class="nav-item">
										<input type="button" class="btn btn-canc-vis btn-info" id="botaoEditar" value="Editar" disabled>
									</li>
									<li class="nav-item">
										<form action="control/controle_evento.php?op=del" method="post">
											<input type="hidden" class="form-control" name="id" id="id">
											<input type="submit" class="btn btn-danger" value="Excluir" id="botaoExcluir" style="position:relative; left:10px;" disabled>
										</form>
									</li>
								</ul>		
							</div>
						</div>
						<!--Formulario de edicao dos eventos-->
						<div class="form" id="formEditar">
							<form class="form-horizontal" method="POST" action="control/controle_evento.php?op=alt" style="position: relative; margin-left: 15px; margin-right:15px; margin-top:-20px;">
								<div class="form-group col-sm-10">
									<!--Id do evento.-->
									<input type="hidden" class="form-control" name="id" id="id">
									<!---->
									<input type="hidden" class="form-control" name="idusuario" value="<?php print($usuario) ?>">
								</div>
								<div class="form-group">
									<label for="" class="">Nome do Evento</label>
									<div class="form-group">
										<input type="text" class="form-control nome-evento" value="title" id="title" name="nomeEvento" minlength="1" maxlength="45" placeholder="" required autofocus>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="">Descrição do Evento</label>
									<div class="">
										<textarea class="form-control desc" value="desc" id="desc" name="descEvento" cols="30" rows="3" minlength="1" maxlength="128"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="">Cor do evento no Calendário</label>
									<div class="">
										<select name="corEvento" class="form-control" id="color" value="color">
											<option value="#616161">Selecionar</option>         
											<option style="color:#0b8043;" value="#0b8043">Verde</option>
											<option style="color:#33b679;" value="#33b679">Verde Claro</option>
											<option style="color:#f4511e;" value="#f4511e">Laranja</option>
											<option style="color:#e67c73;" value="#e67c73">Salmão</option>
											<option style="color:#d50000;" value="#d50000">Vermelho</option>
											<option style="color:#039be5;" value="#039be5">Azul</option>
											<option style="color:#3f51b5;" value="#3f51b5">Azul Escuro</option>
											<option style="color:#7986cb;" value="#7986cb">Ciano</option>
											<option style="color:#8e24aa;" value="#8e24aa">Roxo</option>
											<option style="color:#616161;" value="#616161">Cinza</option>
										</select>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="" class=" ">Data de Início</label><label id="labelDataInvalidaEditar1" for="" style="color:red;" hidden>&nbsp;&nbsp;Data Inválida</label>
										<input type="date" class="form-control startdate" name="dataInicio" value="startdate" id="startdate" onchange="validarDataEditar(); validarHoraEditar();" required>
									</div>
									<div class="form-group col-md-6">
										<label for="" class=" ">Hora de Início</label><label id="labelHoraInvalidaEditar1" for="" style="color:red;" hidden>&nbsp;&nbsp;Hora Inválida</label>
										<input type="time" class="form-control" name="horaInicio" value="starttime" id="starttime" onchange="validarHoraEditar();" required>
									</div>	
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="" class="">Data de Final</label><label id="labelDataInvalidaEditar2" for="" style="color:red;" hidden>&nbsp;&nbsp;Data Inválida</label>
										<input type="date" class="form-control enddate" name="dataFinal" value="enddate" id="enddate" onchange="validarDataEditar2(); validarHoraEditar();" required>
									</div>
									<div class="form-group col-md-6">
										<label for="" class="">Hora de Final</label><label id="labelHoraInvalidaEditar2" for="" style="color:red;" hidden>&nbsp;&nbsp;Hora Inválida</label>
										<input type="time" class="form-control" name="horaFinal" value="endtime" id="endtime" onchange="validarHoraEditar();" required>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-8">
										<label for="" class="">Compartilhar evento com seus contatos</label>
										<select name="emailContatoShared" id="selectContatosEditar" class="form-control" onchange="habilitarSelectPermissao2();" disabled>
											<?php if(empty($dados_select_contatos)){ ?>
											<option value="">Não há contatos para mostrar...</option>
											<?php }else{ ?>
											<option value="">Selecionar contato</option>
											<?php } ?>
											<!--Aqui eu faco a lista dinamica de acordo com os contatos do usuario-->
											<?php foreach ($dados_select_contatos as $array_dados_select_contatos) { ?>
											<option value="<?php echo $array_dados_select_contatos['email_contato']; ?>"><?php echo $array_dados_select_contatos['nome_contato']; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label for="" class="" id="labelselectPermissaoEditar" hidden>Convidados podem:</label>
										<select name="permissao" id="selectPermissaoEditar" class="form-control" disabled hidden required>
											<option value="0">Ler evento</option>
											<option value="1">Ler & Editar evento</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="" style="position:relative; left:145px;">
										<button type="button" class="btn btn-canc-edit btn-primary">Cancelar</button>
										<button type="submit" id="botaoformEditar" class="btn btn-success submit_on_enter" disabled>Salvar</button>
									</div>
								</div>
							</form>	
						</div>	
					</div>
				</div>
			</div>	
		<!--Aqui acaba o modal de Selecionar Eventos-->
		</section>

		<!--Scripts Tools/JS-->
		<script src="tools/js/scripsback.js"></script>
		<!--Chaves do Session-->
		<?php } ?>
	</body>
</html>
<?php 
	/* TUDO ISSO AQUI È PARA A NOTIFICAÇÃO DO EVENTO, QUANDO ELE TIVER EVENTOS NAQUELE DIA/HORA, ELE EXIBE A NOTIFICAÇAO. */
	//Include dos Alertas.
	include_once('view/alerts.php');
	//Defino o date default para SP.
	date_default_timezone_set('America/Sao_Paulo');
	//Pego a data atual do dia.
	$data_atual = date('Y-m-d H:i:s');
	//Somo 1 dia a essa data atual.
	$data_atual_1dia = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($data_atual))); 
	
	//Select que seleciona os eventos no intervalo de 1 dia a partir da data e horario atuais, com base no id de usuario.
	$sql_eventos_dia = "SELECT nome_evento, inicio_evento, final_evento FROM `eventos` WHERE inicio_evento > '$data_atual' and final_evento < '$data_atual_1dia' and id_usuario = '$usuario' order by inicio_evento";
	$stmt = $con->prepare($sql_eventos_dia);
	$stmt->execute();
	$dados_eventos_dia = $stmt->fetchAll();

	//Se o array dos eventos no intervalo de 1 dia, estiver diferente de vazio ele exibe este alerta
	if (!empty($dados_eventos_dia && $alerta == 'logOk')) { ?>
		<script>
			bootbox.alert({
				title: "Seja Bem-vindo(a) <?php print($nome_usuario) ?>.",
				backdrop: true,
				message: "<p class='text-center p_modal_notificacao'><?php echo date('d/m/Y'); ?> - No dia de hoje você tem os seguintes eventos: </p><hr> <?php foreach ($dados_eventos_dia as $eventos_dia) {
					//Aqui eu atribuo a uma variavel somente a hora do evento, e nao a data inteira, como vem do banco.
					$substrInicioEvento = substr($eventos_dia['inicio_evento'], 10);
					$substrFinalEvento = substr($eventos_dia['final_evento'], 10);
					//Aqui eu exibo os eventos com o horario de cada um.
					echo $eventos_dia['nome_evento']." - Das ".$substrInicioEvento." às ".$substrFinalEvento."<br>";
					?> <hr> <?php 
					} ?> ",
			});
		</script>
	<?php }
	//Se o array dos eventos for vazio, ele exibe a mensagem de bem vindo para o usuario!.
	elseif(empty($dados_eventos_dia) && $alerta == 'logOk') {
		//Um dos alertas de bem vindo para o usuario. 
		?>
			<script>
				bootbox.alert({
					message: "Seja Bem-vindo(a) <?php print($nome_usuario) ?>.",
					backdrop: true,
				});
			</script>	
	<?php }
	//Para limpar o Session dos alertas, para assim so aparecerem uma vez.
	$_SESSION['alerts'] = ' ';
?>



