<?php 
	session_start();
	$cont = 
    //Include da conexao para buscar imagem do usuario.
    include_once('../model/Conexao.php');

    $conn = new Conexao;
	$con = $conn->conectar();

	//Select para pegar o id do usuario quando existe um cookie com o email.
	if(!empty($_COOKIE['email'])){
		$email_usuario = $_COOKIE['email'];
		//Buscando o id do usuario com o cookie de email.
		$sql = "SELECT id_usuario FROM `usuario` WHERE email_usuario = '$email_usuario'";
		$stmt = $con->prepare($sql);
		$stmt->execute();
		$dados_usuario = $stmt->fetch();
		$id_usuario = $dados_usuario['id_usuario'];

		//Buscando os dados do usuario para exibir imagem.
		$sql = "SELECT data_cadastro, foto_usuario FROM `usuario` WHERE id_usuario = '$id_usuario'";
		$stmt = $con->prepare($sql);
		$stmt->execute();
		$dados = $stmt->fetch();

		$nomeFoto = $dados['foto_usuario'];
		$dataFoto = $dados['data_cadastro'];
	}
?>
<!doctype html>
<html lang="pt-br">
 	<head>
		<title>Login</title>
		<!-- FaviCon -->
		<link rel="shorcut icon" href="../tools/img/favicon.ico">
		<!--Bootstrap CSS-->
		<link rel="stylesheet" type="text/css" href="../tools/lib/bootstrap/css/bootstrap.min.css">
		<!--CSS do Page-->
		<link rel="stylesheet" type="text/css" href="../tools/css/stylelogin.css">
		<!--Script dos alertas-->
		<script src="../tools/lib/jquery/jquery-3.3.1.min.js"></script>
		<script src="../tools/js/popper.min.js"></script>
		<script src="../tools/lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="../tools/js/bootbox.min.js"></script>
		<!--Fonts Awesome-->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  	</head>
  	<body class="text-center">
	  	<section>
			<!--Formulario de Login-->
			<div class="container form_login">
				<div class="row">
					<div class="icon-login">
						<?php if(empty($nomeFoto)){ ?>
						<i class="fas fa-user-circle"></i>
						<?php }else{ 
						$diretorio = '../img_usuarios/'.$dataFoto.'/'.$id_usuario.'/'.$nomeFoto;	
						?>
						<img src="<?php echo $diretorio;?>" alt="" width="168px" height="168px" class="foto-perfil">
						<?php } ?>
					</div>
					<!--<form>-->
					<form method="post" action="../control/controle_usuario.php?id=log">
						<div class="form_group" style="width: 340px;">
							<i class="fas fa-envelope icones-form"></i>
							<?php 
							//Para verificar se existe um coookie com o email do usuario, se sim, ele da um input com o value do email..
							if (isset($_COOKIE['email'])) { 
								$campo = $_COOKIE['email']; 
							?>
							<input type="email" id="inputEmail" name="emailUsuarioLog" size="" value="<?php echo "$campo"; ?>" class="form-control" placeholder="E-mail" required autofocus>
							<?php }else{ ?>
							<input type="email" id="inputEmail" name="emailUsuarioLog" size="" value="" class="form-control" placeholder="E-mail" required autofocus>
							<?php } ?>
						</div>
						<div class="form_group">
							<label for=""></label>
							<i class="fas fa-key icones-form"></i>
							<input type="password" id="inputPassword txtSenha" name="senhaUsuarioLog" class="form-control" placeholder="Password" required>
						</div>
						<div class="form-check">
							<label class="form-check-label" style="position:relative; right: 89px; top: -4px;">
							<!--Para verificar novamente se o cookie existe, se sim, ele mostra um checkbox ja mascado.-->
							<?php if (isset($_COOKIE['email'])) { ?>
							<input type="checkbox" class="form-check-input" name="lembrar" style="" checked>
							<?php }else{ ?>
							<input type="checkbox" class="form-check-input" name="lembrar" style="">
							<?php } ?>
							Lembrar-se</label>
						</div>
						<div class="botao-login">
							<input type="submit" class="btn btn-info btn-lg btn-block" name="submit" value="Login">
						</div>
					</form> 
					<!--</form>-->
					<div class="footer-form">
						<a href="../index.php"><h4>Designed by Memory Times.</h4></a>
					</div>
				</div> 
			</div>
		</section>
		<!--Foto do Usuario Aumentando com mouseover-->
		<script>
			$(document).ready(function(){
				$(".foto-perfil").on("mouseover",function(){
					$(".foto-perfil").addClass("efeito-foto-usuario");
				}).on("mouseout", function(){
					$(".foto-perfil").removeClass("efeito-foto-usuario");
				});
			});
		</script>
		<!-------------------------------------------------------->
  	</body>
</html>
<?php
	//Include dos alertas.
	include_once('../view/alerts.php');
?>
