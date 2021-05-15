<?php 
	date_default_timezone_set('America/Sao_Paulo');
	$dataAtual = date('Y-m-d');
?>
<!doctype html>
<html lang="pt-br">
 	<head>
		<title>Cadastro</title>
		<!-- FaviCon -->
		<link rel="shorcut icon" href="../tools/img/favicon.ico">
		<!--CSS da Page-->
		<link rel="stylesheet" type="text/css" href="../tools/css/stylecadastro.css">
		<!--Bootstrap CSS-->
		<link rel="stylesheet" type="text/css" href="../tools/lib/bootstrap/css/bootstrap.min.css">
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
		<!--Fonts Awesome-->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	  </head>
  	<body class="text-center">
	  	<section>
		  	<!--Formulario de Cadastro de Usuario-->
			<div class="container form_cadastro">
				<div class="row">
					<div class="icon-cad">
						<i class="far fa-address-book"></i>
					</div>
					<!--<Form>-->
					<form method="post" action="../control/controle_usuario.php?id=cad" enctype="multipart/form-data">
						<div class="form_group">
							<label>Digite seu Nome</label> 
							<input type="text" name="nomeUsuarioReg" minlength="1" size="52" maxlength="45" class="form-control" placeholder="" required autofocus>
						</div>
						<hr>
						<div class="form_group">
							<label>Digite seu E-Mail</label>
							<input type="email" name="emailUsuarioReg" minlength="1" maxlength="45" class="form-control" placeholder="example@gmail.com" required>
						</div>
						<hr>
						<div class="form_group">
							<label>Digite sua Senha</label>
							<input type="password" name="senhaUsuarioReg" minlength="1" maxlength="255" class="form-control" placeholder required>
						</div>
						<hr>
						<div class="form_group">
							<label>Digite sua Data de Nascimento</label>
							<input type="date" id="dataNascimento" name="dataNascReg" class="form-control" max="<?php echo $dataAtual; ?>" placeholder required>
						</div>
						<hr>
						<div class="form_group file-input">
							<label>Foto de Perfil (Opcional)</label>
							<input type="file" class="form-control-file" accept="image/*" name="fotoUsuario">
						</div>
						<div class="botao-submit">
							<input type="reset" class="btn btn-outline-secondary btn-lg" value="Limpar">
							<input type="submit" class="btn btn-outline-info btn-lg" name="submit" value="Registrar-se">
						</div>
					</form>  
					<!--</form>-->
					<div class="footer-form">
						<a href="../index.php"><h4>Designed by Memory Times.</h4></a>
					</div>
				</div>
			</div>
		</section>
  	</body>
</html>
<?php
	//Include dos alertas.
	include_once('../view/alerts.php');
?>
