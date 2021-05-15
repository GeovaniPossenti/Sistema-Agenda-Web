<?php
	@session_start();
	@$alerta = $_SESSION['alerts'];
?>
	<script>
        //Alertas sobre o cadastro de usuario.
		function mensagem1(){
			bootbox.alert({
				message: "Cadastro efetuado com sucesso!",
				backdrop: true
			});
		}
		function mensagem2(){
			bootbox.alert({
				size: "small",
				message: "Email já está em uso!",
				backdrop: true
			});
		}		
		function mensagem21(){
			alertify.error('Data de nascimento inválida!');
		}	
		//--------------------------------------
		//Alertas sobre login/logout de usuario.
		function mensagem3(){
			bootbox.alert({
				message: "Email ou senha incorretos!",
				backdrop: true
			});
		}
		function mensagem4(){
			alertify.success('Deslogado com sucesso!');
		}
		//--------------------------------------
		//Alertas sobre os eventos.
		function mensagem5(){
			alertify.success('Evento cadastrado!');
		}
		function mensagem6(){
			alertify.warning('Evento atualizado!');
		}
		function mensagem7(){
			alertify.error('Evento excluido!');
		}
		//--------------------------------------
		//Alertas sobre o usuario.
		function mensagem8(){
			bootbox.alert({
				message: "Dados cadastrais alterados com sucesso!",
				backdrop: true
			});
		}
		function mensagem9(){
			alertify.success('Imagem de perfil excluida!');
		}
		function mensagem10(){
			alertify.error('Senha atual incorreta!');
			alertify.error('Dados não foram modificados!');
		}
		function mensagem11(){
			alertify.error('Email já cadastrado!');
			alertify.error('Dados não foram modificados!');
		}
		function mensagem12(){
			alertify.success('Papel de parede modificado!');
		}
		function mensagem13(){
			alertify.error('Arquivo enviado não é uma imagem!');
			alertify.error('Dados não foram modificados!');
		}
		function mensagem14(){
			alertify.error('Imagem com resolução muito grande!');
			alertify.error('Dados não foram modificados!');
		}
		//--------------------------------------
		//Alertas sobre os contatos.
		function mensagem15(){
			alertify.error('Usúario não é cliente da Memory Times.');
		}
		function mensagem16(){
			alertify.success('Contato cadastrado com sucesso!');
		}
		function mensagem17(){
			alertify.error('Contato deletado com sucesso!');
		}
		function mensagem18(){
			alertify.success('Contato alterado com sucesso!');
		}
		function mensagem19(){
			alertify.error('Este contato Já está Cadastrado!');
		}
		function mensagem20(){
			alertify.error('Não é possível adicionar você mesmo como contato!');
		}
		//--------------------------------------
	</script>
<?php
	//Todos os alertas.
	switch (@$alerta){
		case 'cadOk': echo '<script> mensagem1(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'cadFail': echo '<script> mensagem2(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'logFail': echo '<script> mensagem3(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'logout': echo '<script> mensagem4(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'evCad': echo '<script> mensagem5(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'evUpd': echo '<script> mensagem6(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'evDel': echo '<script> mensagem7(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'perOk': echo '<script> mensagem8(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'img': echo '<script> mensagem9(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'senhaAtualInv': echo '<script> mensagem10(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'emailJaExiste': echo '<script> mensagem11(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'WallModif': echo '<script> mensagem12(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'imgUplFail': echo '<script> mensagem13(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'imgSizeFail': echo '<script> mensagem14(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'emailContatoInv': echo '<script> mensagem15(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'contatoCadOk': echo '<script> mensagem16(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'contatoDelOk': echo '<script> mensagem17(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'altContatosOk': echo '<script> mensagem18(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'emailContatoJaCadastrado': echo '<script> mensagem19(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'contatoIgualAoUsuario': echo '<script> mensagem20(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
		case 'dataNascInvalida': echo '<script> mensagem21(); </script>'; 
		$_SESSION['alerts'] = ' ';
		break;
	}
?>