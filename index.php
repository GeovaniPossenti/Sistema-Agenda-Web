<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home</title>
		<!-- FaviCon -->
		<link rel="shorcut icon" href="tools/img/favicon.ico">
		<link rel="stylesheet" href="tools/css/styleindex.css">
      	<link rel="stylesheet" href="tools/lib/bootstrap/css/bootstrap.min.css">
		<!--Script dos alertas-->
		<script src="tools/lib/jquery/jquery-3.3.1.min.js"></script>
		<script src="tools/js/popper.min.js"></script>
		<script src="tools/lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="tools/js/bootbox.min.js"></script>
		<!--Script dos alertas de Adicionar, Atualizar e remover eventos.-->
		<link rel="stylesheet" href="tools/lib/alertifyjs/css/alertify.min.css" />
		<link rel="stylesheet" href="tools/lib/alertifyjs/css/themes/default.min.css" />
		<script src="tools/lib/alertifyjs/alertify.min.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
	</head>
	<body>
		<header>
            <nav>
                <div class="menu-icon">
                    <i class="fa fa-bars fa-2x"></i>
            	</div>
                <div class="logo">
                    <img src="tools/img/logo.png" width="260">
            	</div>
                <div class="menu">
					<ul>
						<li><a href="view/cadastroForm.php"><input type="button" id="button1" class="btn btn-outline-info botao1" style="width:130px; height:55px; position: relative; left: 70px;" value="Cadastrar-se"></a></li>
						<li><a href="view/loginForm.php"><input type="button" id="button2" class="btn btn-outline-info" style="width:130px; height:55px" value="Login"></a></li>
					</ul>
        		</div>
    		</nav>
        </header>
		<section>       
            <div class="content">
                <div class="img-html">
                    <img src="tools/img/Index/LOGO1.png" width="178">
            	</div>
                <p>Um projeto pensado e desenvolvido pensando exatamente em você que planeja aumentar seu rendimento diario, em você que busca otimizar os seus dias, eliminando o desperdicio de tempo, e também em você que esta planejando mudar seus hábitos, para obter uma vida mais saudável se livrando da procastinação. A <i>Memory Times</i>, tem como principal objetivo, o auxílio no registro de seus compromissos, e diversas outras funções que facilitam o seu Day-by-Day.</p>   
            </div>
            <div class="parallax">
                <div class="container">
                	<div class="box">
                        <div class="imgBx">
                            <img src="tools/img/Index/TelaPrincipal.png">     
                        </div>
                        <div class="conteudo">
                            <h3>Tela Principal</h3>
                            <p>Áreas de trabalho extremamente intuitivas e interativas, que buscam facilitar o uso de qualquer um dos nossos visitantes.</p>
                        </div>
                    </div>
                    <div class="box">
                        <div class="imgBx">
                            <img src="tools/img/Index/TelaPerfil2.png">     
                        </div>
                        <div class="conteudo">
                            <h3>Perfil Do Usuário</h3>
                            <p>O perfil de usuário é uma das principais funções depois de criar uma conta, para ter acesso ao perfil de usuário, necessita-se estar cadastrado e permanecer logado, na mesma interface é possível alterar imagem, nome, email e senha.</p>
                        </div>
                    </div>
                    <div class="box">
                        <div class="imgBx">
                            <img src="tools/img/Index/TelaCompromissos.png">     
                        </div>
                        <div class="conteudo">
                            <h3>Tela de Compromissos</h3>
                            <p>A função compromissos apresenta os eventos que o usuário terá naquela data ou em datas futuras, assim fazendo com que o cliente sempre esteja organizado com seus horários.</p>
                        </div>
                    </div>
                </div>        
            </div>
        </section>
		<footer>
			<img src="tools/img/Index/logo.png" height="110">
				<div class="socialIcons">
					<a href="https://www.facebook.com/Memory-Times-437307150429697/" target="_blank"><i class="fab fa-facebook"></i></a>
					<a href="https://twitter.com/times_memory" target="_blank"><i class="fab fa-twitter"></i></a>
					<a href="https://www.instagram.com/memorytimesweb/" target="_blank"><i class="fab fa-instagram"></i></a>
					<p class="direitos"> Copiright © Memory Times 2019. todos os direitos reservados.</p>
				</div>
		</footer>
		<script type="text/javascript">
			// Menu-toggle button
			$(document).ready(function() {
				$(".menu-icon").on("click", function() {
						$("nav ul").toggleClass("showing");
				});
			});
			// Scrolling Effect
			$(window).on("scroll", function() {
				if($(window).scrollTop()) {
					$('nav').addClass('black');
				}
				else {
					$('nav').removeClass('black');
				}
			});
		</script>
	</body> 

</html>
<?php
	//Include dos alertas.
	include_once('view/alerts.php');
	
	// finaliza a sessão
	session_destroy();
?>