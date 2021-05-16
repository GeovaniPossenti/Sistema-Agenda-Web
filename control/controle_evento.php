<?php
	include_once "../model/Conexao.php";
	session_start();

	@$op = $_GET['op'];
	if ($op == 'cad') {
		$idusuario = isset($_POST['idusuario']) ? $_POST['idusuario'] : '';
		$nome = filter_input(INPUT_POST, 'nomeEvento', FILTER_SANITIZE_STRING);
		$desc = filter_input(INPUT_POST, 'descEvento', FILTER_SANITIZE_STRING);
		$cor = filter_input(INPUT_POST, 'corEvento', FILTER_SANITIZE_STRING);

		$startdate = filter_input(INPUT_POST, 'dataInicio', FILTER_SANITIZE_STRING);
		$starttime = filter_input(INPUT_POST, 'horaInicio', FILTER_SANITIZE_STRING);

		$enddate = filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_STRING);
		$endtime = filter_input(INPUT_POST, 'horaFinal', FILTER_SANITIZE_STRING);

		$emailContatoShared = $_POST['emailContatoShared'];
		$permissao = $_POST['permissao'];
		
		if(empty($desc)){
			$desc = "Não há descrição...";
		}

		$conn = new Conexao;
		$con = $conn->conectar();

		//Para juntar data e hora, que vieram separadas do formulario.
		$dataInicio = "".$startdate." ".$starttime.":00";
		$dataFim = "".$enddate." ".$endtime.":00";

		//Converter a data e hora do formato brasileiro para o formato do Banco de Dados.
		$data = explode(" ", $dataInicio);
		list($date, $hora) = $data;
		$data_sem_barra = array_reverse(explode("/", $date));
		$data_sem_barra = implode("-", $data_sem_barra);
		$start_sem_barra = $data_sem_barra . " " . $hora;
						
		$data = explode(" ", $dataFim);
		list($date, $hora) = $data;
		$data_sem_barra = array_reverse(explode("/", $date));
		$data_sem_barra = implode("-", $data_sem_barra);
		$end_sem_barra = $data_sem_barra . " " . $hora;

		/*
		Permissoes dos eventos.
		0 = Ler evento
		1 = Ler & Editar evento
		*/

		//Se o campo de compartilhar evento estiver vazio, apenas grava no banco.
		if(empty($emailContatoShared)){
			$sql = ("call eventoInsert (?,?,?,?,?,?)");
			$stmt = $con->prepare($sql);
			$stmt->bindParam(1, $idusuario);
			$stmt->bindParam(2, $nome);
			$stmt->bindParam(3, $desc);
			$stmt->bindParam(4, $cor);
			$stmt->bindParam(5, $start_sem_barra);
			$stmt->bindParam(6, $end_sem_barra);
			$stmt->execute();
			header("location: ../view/agenda.php");
			//Session dos alertas.
			$_SESSION['alerts'] = 'evCad';
		}
		elseif(!empty($emailContatoShared)){
			$sql = ("call eventoInsert (?,?,?,?,?,?)");
			$stmt = $con->prepare($sql);
			$stmt->bindParam(1, $idusuario);
			$stmt->bindParam(2, $nome);
			$stmt->bindParam(3, $desc);
			$stmt->bindParam(4, $cor);
			$stmt->bindParam(5, $start_sem_barra);
			$stmt->bindParam(6, $end_sem_barra);
			$stmt->execute();
			
			/* Aqui eu pego o id do evento que acabou de ser inserido. */ 

			$sql_select_id_evento_insert = "SELECT `id_evento` FROM `eventos` WHERE id_usuario = '$idusuario' AND nome_evento = '$nome'";
			$stmt = $con->prepare($sql_select_id_evento_insert);
			$stmt->execute();
			$array_id_evento = $stmt->fetch();
			$id_evento_inserido = $array_id_evento['id_evento'];

			/* ------------------------------------------------------ */

			/* Aqui eu pego o id do contato, para salvar na tabela. */ 

			$sql_select_id_contato = "SELECT `id_usuario` FROM `usuario` WHERE email_usuario = '$emailContatoShared'";
			$stmt = $con->prepare($sql_select_id_contato);
			$stmt->execute();
			$array_id_contato = $stmt->fetch();
			$id_contato = $array_id_contato['id_usuario'];

			/* ------------------------------------------------------ */

			//Aqui eu dou insert na tabela de eventos compartilhados.
			$sql_insert_shared = "INSERT INTO `eventos_shared`(`id_usuario_solicitante`, `id_evento_compartilhado`, `id_usuario_solicitado`, `permissao`) VALUES ('$idusuario','$id_evento_inserido','$id_contato','$permissao')";
			$stmt = $con->prepare($sql_insert_shared);
			$stmt->execute();

			header("location: ../view/agenda.php");
			//Session dos alertas.
			$_SESSION['alerts'] = 'evCad';
		}
	}
	elseif($op == 'alt'){
		$idusuario = isset($_POST['idusuario']) ? $_POST['idusuario'] : '';
		$idevento = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
		$nome = filter_input(INPUT_POST, 'nomeEvento', FILTER_SANITIZE_STRING);
		$desc = filter_input(INPUT_POST, 'descEvento', FILTER_SANITIZE_STRING);
		$cor = filter_input(INPUT_POST, 'corEvento', FILTER_SANITIZE_STRING);

		$startdate = filter_input(INPUT_POST, 'dataInicio', FILTER_SANITIZE_STRING);
		$starttime = filter_input(INPUT_POST, 'horaInicio', FILTER_SANITIZE_STRING);

		$enddate = filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_STRING);
		$endtime = filter_input(INPUT_POST, 'horaFinal', FILTER_SANITIZE_STRING);

		$emailContatoShared = $_POST['emailContatoShared'];
		$permissao = $_POST['permissao'];

		if(empty($desc)){
			$desc = "Não há descrição...";
		}

		//Para juntar data e hora, que vieram separadas do formulario.
		$dataInicio = "".$startdate." ".$starttime.":00";
		$dataFim = "".$enddate." ".$endtime.":00";

		//Converter a data e hora do formato brasileiro para o formato do Banco de Dados.
		$data = explode(" ", $dataInicio);
		list($date, $hora) = $data;
		$data_sem_barra = array_reverse(explode("/", $date));
		$data_sem_barra = implode("-", $data_sem_barra);
		$start_sem_barra = $data_sem_barra . " " . $hora;
					
		$data = explode(" ", $dataFim);
		list($date, $hora) = $data;
		$data_sem_barra = array_reverse(explode("/", $date));
		$data_sem_barra = implode("-", $data_sem_barra);
		$end_sem_barra = $data_sem_barra . " " . $hora;

		$conn = new Conexao;
		$con = $conn->conectar();

		if(empty($emailContatoShared)){
			//Aqui eu verifico se esse evento ja esta compartilhado, se sim atualizo o mesmo para o novo contato.
			$sql_select = "SELECT * FROM `eventos_shared` WHERE id_evento_compartilhado = '$idevento'";
			$stmt = $con->prepare($sql_select);
			$stmt->execute();
			$array_select = $stmt->fetch();
			$id_compartilhado = $array_select['id_compartilhado'];

			if(empty($array_select)){
				$sql = ("UPDATE `eventos` SET `nome_evento`= ?,`desc_evento`= ?,`color`= ?,`inicio_evento`= ?,`final_evento`= ? WHERE id_evento = '$idevento'");
				$stmt = $con->prepare($sql);
				$stmt->bindParam(1, $nome);
				$stmt->bindParam(2, $desc);
				$stmt->bindParam(3, $cor);
				$stmt->bindParam(4, $start_sem_barra);
				$stmt->bindParam(5, $end_sem_barra);
				$stmt->execute();

				header("location: ../view/agenda.php");
				//Session dos alertas.
				$_SESSION['alerts'] = 'evUpd';
			}else{

				$sql = ("UPDATE `eventos` SET `nome_evento`= ?,`desc_evento`= ?,`color`= ?,`inicio_evento`= ?,`final_evento`= ? WHERE id_evento = '$idevento'");
				$stmt = $con->prepare($sql);
				$stmt->bindParam(1, $nome);
				$stmt->bindParam(2, $desc);
				$stmt->bindParam(3, $cor);
				$stmt->bindParam(4, $start_sem_barra);
				$stmt->bindParam(5, $end_sem_barra);
				$stmt->execute();

				$select_brabo = "SELECT * FROM `eventos_shared` WHERE id_usuario_solicitado = '$idusuario' AND id_evento_compartilhado = '$idevento'";
				$stmt = $con->prepare($select_brabo);
				$stmt->execute();
				$array_brabo = $stmt->fetch();

				if(empty($array_brabo)){
					//Aqui eu deleto aquele evento compartilhado.
					$sql_delete_event_shared = "DELETE FROM `eventos_shared` WHERE id_compartilhado = '$id_compartilhado'";
					$stmt = $con->prepare($sql_delete_event_shared);
					$stmt->execute();
				}

				header("location: ../view/agenda.php");
				//Session dos alertas.
				$_SESSION['alerts'] = 'evUpd';
			}


		}elseif(!empty($emailContatoShared)){
			//Aqui eu verifico se esse evento ja esta compartilhado, se sim atualizo o mesmo para o novo contato.
			$sql_select = "SELECT * FROM `eventos_shared` WHERE id_evento_compartilhado = '$idevento'";
			$stmt = $con->prepare($sql_select);
			$stmt->execute();
			$array_select = $stmt->fetch();
			$id_compartilhado = $array_select['id_compartilhado'];

			//Se o array for vazio, eu gravo o evento e o evento compartilhado.
			if(empty($array_select)){
				$sql = ("UPDATE `eventos` SET `nome_evento`= ?,`desc_evento`= ?,`color`= ?,`inicio_evento`= ?,`final_evento`= ? WHERE id_evento = '$idevento'");
				$stmt = $con->prepare($sql);
				$stmt->bindParam(1, $nome);
				$stmt->bindParam(2, $desc);
				$stmt->bindParam(3, $cor);
				$stmt->bindParam(4, $start_sem_barra);
				$stmt->bindParam(5, $end_sem_barra);
				$stmt->execute();

				//Aqui eu gravo tambem na tabela de eventos compartilhados.
				/* Aqui eu pego o id do evento que acabou de ser inserido. */ 
				$sql_select_id_evento_insert = "SELECT `id_evento` FROM `eventos` WHERE id_usuario = '$idusuario' AND nome_evento = '$nome'";
				$stmt = $con->prepare($sql_select_id_evento_insert);
				$stmt->execute();
				$array_id_evento = $stmt->fetch();
				$id_evento_inserido = $array_id_evento['id_evento'];
				/* ------------------------------------------------------ */
				/* Aqui eu pego o id do contato, para salvar na tabela. */ 
				$sql_select_id_contato = "SELECT `id_usuario` FROM `usuario` WHERE email_usuario = '$emailContatoShared'";
				$stmt = $con->prepare($sql_select_id_contato);
				$stmt->execute();
				$array_id_contato = $stmt->fetch();
				$id_contato = $array_id_contato['id_usuario'];
				/* ------------------------------------------------------ */
				//Aqui eu dou insert na tabela de eventos compartilhados.
				$sql_insert_shared = "INSERT INTO `eventos_shared`(`id_usuario_solicitante`, `id_evento_compartilhado`, `id_usuario_solicitado`, `permissao`) VALUES ('$idusuario','$id_evento_inserido','$id_contato','$permissao')";
				$stmt = $con->prepare($sql_insert_shared);
				$stmt->execute();
	
				header("location: ../view/agenda.php");
				//Session dos alertas.
				$_SESSION['alerts'] = 'evUpd';
			}
			//Se nao eu gravo o evento e atualizo o evento compartilhado.
			else{
				$sql = ("UPDATE `eventos` SET `nome_evento`= ?,`desc_evento`= ?,`color`= ?,`inicio_evento`= ?,`final_evento`= ? WHERE id_evento = '$idevento'");
				$stmt = $con->prepare($sql);
				$stmt->bindParam(1, $nome);
				$stmt->bindParam(2, $desc);
				$stmt->bindParam(3, $cor);
				$stmt->bindParam(4, $start_sem_barra);
				$stmt->bindParam(5, $end_sem_barra);
				$stmt->execute();

				//Aqui eu dou update tambem na tabela de eventos compartilhados.
				/* Aqui eu pego o id do evento que acabou de ser inserido. */ 
				$sql_select_id_evento_insert = "SELECT `id_evento` FROM `eventos` WHERE id_usuario = '$idusuario' AND nome_evento = '$nome'";
				$stmt = $con->prepare($sql_select_id_evento_insert);
				$stmt->execute();
				$array_id_evento = $stmt->fetch();
				$id_evento_inserido = $array_id_evento['id_evento'];
				/* ------------------------------------------------------ */
				/* Aqui eu pego o id do contato, para salvar na tabela. */ 
				$sql_select_id_contato = "SELECT `id_usuario` FROM `usuario` WHERE email_usuario = '$emailContatoShared'";
				$stmt = $con->prepare($sql_select_id_contato);
				$stmt->execute();
				$array_id_contato = $stmt->fetch();
				$id_contato = $array_id_contato['id_usuario'];
				/* ------------------------------------------------------ */
				//Aqui eu dou insert na tabela de eventos compartilhados.

				$sql_insert_shared = "UPDATE `eventos_shared` SET `id_usuario_solicitante`='$idusuario',`id_evento_compartilhado`='$id_evento_inserido',`id_usuario_solicitado`='$id_contato',`permissao`= '$permissao' WHERE id_compartilhado = '$id_compartilhado'";
				$stmt = $con->prepare($sql_insert_shared);
				$stmt->execute();

				header("location: ../view/agenda.php");
				//Session dos alertas.
				$_SESSION['alerts'] = 'evUpd';
			}
		}
	}
	//Caso o usuario modifique o evento pelo drag and drop.
	elseif($op == 'altDragAndDrop'){
		$id_evento = $_POST['id_evento'];
		$start = $_POST['start'];
		$end = $_POST['end'];

		$conn = new Conexao;
		$con = $conn->conectar();

		//Aqui eu dou update nos eventos, com o ajax.
		$sql_update_eventos_drag = "UPDATE eventos SET inicio_evento = '$start', final_evento = '$end' WHERE id_evento = '$id_evento'";
		$stmt = $con->prepare($sql_update_eventos_drag);
		$stmt->execute();
		
	}elseif($op == 'del'){
		$idevento = $_POST['id'];
		
		$conn = new Conexao;
		$con = $conn->conectar();

		//Aqui eu verifico se o evento que ele deseja excluir, é um evento compartilhado.
		$sql_select_eventos_shared = "SELECT id_evento_compartilhado FROM `eventos_shared` WHERE id_evento_compartilhado = '$idevento'";
		$stmt = $con->prepare($sql_select_eventos_shared);
		$stmt->execute();
		$dados_eventos_shared = $stmt->fetch();

		//Aqui eu verifico se o array possue dados, se sim, deleta da tabela de eventos compartilhados e eventos, se nao, apenas da tabela de eventos.
		if(empty($dados_eventos_shared)){
			$sql = ("DELETE FROM `eventos` WHERE id_evento = '$idevento'");
			$stmt = $con->prepare($sql);
			$stmt->execute();
		}else{
			$sql_delete_shared = ("DELETE FROM `eventos_shared` WHERE id_evento_compartilhado = '$idevento'");
			$stmt = $con->prepare($sql_delete_shared);
			$stmt->execute();

			$sql_delete_eventos = ("DELETE FROM `eventos` WHERE id_evento = '$idevento'");
			$stmt = $con->prepare($sql_delete_eventos);
			$stmt->execute();
		}


		header("location: ../view/agenda.php");
		//Session dos alertas.
		$_SESSION['alerts'] = 'evDel';
	}
?>