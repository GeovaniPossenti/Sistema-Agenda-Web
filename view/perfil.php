<?php    
    //Session para pegar id do usuario deste perfil.
    session_start();
    @$idusuario = $_SESSION['user_id'];

    //Quando o usuario clica no botao editar, eu alterno entre o formulario.
    @$editar = $_GET['ed'];

    //Include da conexao para buscar imagem do usuario.
    include_once('../model/Conexao.php');

    $conn = new Conexao;
    $con = $conn->conectar();

    //Para mostrar os dados cadastrados, no formulario.
    $result_usu = "SELECT * FROM `usuario` where id_usuario = '$idusuario'";
    $stmt = $con->prepare($result_usu);
    $stmt->execute();
    //Colocando o resultado dentro da variavel.
    $dados_usuarios = $stmt->fetch();

    //Buscando os dados do usuario para exibir imagem.
    $sql = "SELECT data_cadastro, foto_usuario FROM `usuario` WHERE id_usuario = '$idusuario'";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $dados = $stmt->fetch();

    $nomeFoto = $dados['foto_usuario'];
    $dataFoto = $dados['data_cadastro'];

    //Para buscar os dados de wallpaper de fundo da agenda do usuario.
	$sqlWallpaper = "SELECT wallpaper_usuario FROM `usuario` WHERE id_usuario = '$idusuario'";
	$stmt = $con->prepare($sqlWallpaper);
	$stmt->execute();
	$dadosWallpaper = $stmt->fetch();
	//Atribuindo o fetch para uma variavel
	@$WallpaperUsuarioId = $dadosWallpaper['wallpaper_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Profile</title>
		<!-- FaviCon -->
		<link rel="shorcut icon" href="../tools/img/favicon.ico">
        <!--Referencia do Style da Agenda-->
		<link href='../tools/css/styleagenda.css' rel='stylesheet'>
		<!--Referencia do bootstrap-->
		<link rel="stylesheet" type="text/css" href="../tools/lib/bootstrap/css/bootstrap.min.css">
        <!--JQuery-->
		<script src="../tools/lib/jquery/jquery-3.3.1.min.js"></script>
		<!--Script dos alertas-->
		<script src="../tools/js/popper.min.js"></script>
		<script src="../tools/lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="../tools/js/bootbox.min.js"></script>
		<!--Script dos alertas de Adicionar, Atualizar e remover eventos.-->
		<link rel="stylesheet" href="../tools/lib/alertifyjs/css/alertify.min.css" />
		<link rel="stylesheet" href="../tools/lib/alertifyjs/css/themes/default.min.css" />
		<script src="../tools/lib/alertifyjs/alertify.min.js"></script>
    </head>
	<?php 
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
	}  ?>
	<body class="<?php echo $class; ?>">	
        <header>
            <div class="container">
                <!-- Image and text -->
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <div class="container">
                                    <div class="row float-left">
                                        <a href="../agenda.php"><input type="button" style="width:150px; margin-top: 13px;" class="btn btn-outline-info" onclick="" value="<< Voltar"></a></p> 
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>		
            </div>		
        </header>
        <section style="margin-top: 10px;">
            <div class="container fundo-transparente-perfil text-center">
                <?php if (isset($_POST['editar'])) { ?>
                <div class="form-perfil" style="width: 780px; position:relative; left: 40px;">
                    <form action="../control/controle_usuario.php?id=alt" method="post" enctype="multipart/form-data" style="color: white;">
                            <div class="container">
                                <h1 style="position:relative; top: -40px; left:130px;">Alterar dados</h1>
                                <hr style="background-color:white; width: 1000px; position:relative; left: 0px;"><br>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-4 col-form-label" style="position:relative; left:77px;">Foto de Perfil:</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control-file" accept="image/*" name="fotoUsuarioNew">
                                    </div>
                                    <div style="position:relative; color: gray; left: 275px;">
                                        <label>Caso não envie uma imagem, manterá a imagem atual.</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label" style="position:relative; left:50px;">Digite o novo Nome:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="newnomeUsuario" class="form-control" minlength="1" maxlength="45" id="" value="<?php echo $dados_usuarios['nome_usuario']; ?>" autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                <label for="" class="col-sm-4 col-form-label" style="position:relative; left:51px;">Digite o novo Email:</label>
                                    <div class="col-sm-8">
                                        <input type="email" name="newemailUsuario" class="form-control" id="" minlength="1" maxlength="45" value="<?php echo $dados_usuarios['email_usuario']; ?>" placeholder="example@gmail.com">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label" style="position:relative; left:50px;">Digite a Senha Atual:</label>
                                    <div class="col-sm-8">
                                        <input type="password" name="senhaAtual" class="form-control" id="" placeholder="Senha Atual (Preenchimento Obrigatório)" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 col-form-label" style="position:relative; left:50px;">Digite a nova Senha:</label>
                                    <div class="col-sm-8">
                                        <input type="password" name="newsenhaUsuario" class="form-control" id="" minlength="1" maxlength="255" placeholder="Nova Senha">
                                    </div>
                                    <div style="position:relative; color: gray; left: 275px;">
                                        <label>Caso não preencha este campo, manterá a senha atual.</label>
                                    </div>
                                </div>
                                <div class="form-group row" style="position: relative; top: 0px; left: 430px">
                                        <input type="hidden" name="idusuario" value="<?php echo $idusuario;?>">
                                        <input type="button" onclick="history.back(-4)" class="btn btn btn-danger" value="Cancelar">
                                        <input type="submit" class="btn btn btn-info" value="Salvar" style="position:relative; left: 12px; width: 80px;">
                                </div>
                            </div>
                    </form>
                </div>
                <?php }else{ ?>
                <div class="container" style="color: white;">
                    <h1 class="h1-perfil">Dados Cadastrados</h1>
                    <div class="form-perfil" style="width: 600px; position:relative; left: 190px;">
                        <hr style="background-color:white; width: 700px; position:relative; left:0px;"><br>
                        <div class="form-group row" style="position:relative; left:-90px;">
                            <label for="staticEmail" class="col-sm-4 col-form-label" style="position:relative; left: 18px;">Foto de Perfil:</label>
                            <div class=" float-left">
                                <!-- Para selecionar se o usuario enviou uma foto ou nao, se sim mostra a mesma, se nao, mostra uma foto default-->
                                <?php if(!empty($dados['foto_usuario'])){ 
                                    $diretorio = '../img_usuarios/'.$dataFoto.'/'.$idusuario.'/'.$nomeFoto;
                                    ?>
                                    <img src="<?php echo "$diretorio"; ?>" width="180px" height="180px" class="foto-perfil">
                                <?php } else { 
                                    $diretorio = '../tools/img/logo-primary.jpg';
                                    ?> 
                                    <img src="<?php echo "$diretorio"; ?>" width="180px" height="180px" class="foto-perfil">
                                <?php } ?>
                            </div>                   
                            <div class=" float-left" style="position:relative; left:20px; top:143px;">
                                <!--Formulario de exclusao da foto.-->
                                <!--Aonde eu passo o ID do usuario e o nome do diretorio para excluir.-->
                                <!--Aqui verifico se o diretorio foi para o DEFAULT, se sim atribuo um diretorio do usuario. Apenas para nao excluir a foto DEFAULT.-->
                                <?php if ($diretorio == '../tools/img/logo-primary.jpg') {
                                    $diretorio = '../img_usuarios/'.$dataFoto.'/'.$idusuario.'/'.$nomeFoto;
                                }else{?>
                                <form action="../control/controle_usuario.php?id=del" method="POST">
                                    <input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">
                                    <input type="hidden" name="diretorio" value="<?php echo "$diretorio"; ?>">
                                    <input type="submit" class="btn btn-danger" value="Excluir Foto">
                                </form>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Nome:</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control" id="" value="<?php echo $dados_usuarios['nome_usuario']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Email:</label>
                            <div class="col-sm-10">
                                <input type="email" readonly class="form-control" id="" value="<?php echo $dados_usuarios['email_usuario']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="botao-alterar" style="position:relative; left:120px; top:10px;">
                                <form action="#" method="post">
                                    <input type="submit" name="editar" class="btn btn-info" value="Alterar Dados">
                                </form>
                            </div>
                            <div style="position:relative; left:130px; top:18px; color: gray;">
                                <label>Clicar para alterar os dados.</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>
		<!--Foto do Usuario Aumentando com mouseover-->
		<script>
			$(document).ready(function(){
				$(".foto-perfil").on("mouseover",function(){
					$(".foto-perfil").addClass("efeito-foto-usuario-perfil");
				}).on("mouseout", function(){
					$(".foto-perfil").removeClass("efeito-foto-usuario-perfil");
				});
			});
		</script>
		<!-------------------------------------------------------->
    </body>
</html>
<?php 
	//Include dos Alertas.
    include_once('alerts.php');
?>