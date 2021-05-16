<?php 
    header('charset=ISO-8859-1');
    //Session que pego o id e o name do Usuario.
    session_start();
    @$id_usuario = $_SESSION['user_id'];
    @$nome_usuario = $_SESSION['user_name'];

    //Include da conexao.
    include_once('../model/Conexao.php');
	$conn = new Conexao;
    $con = $conn->conectar();
    
    //Select para pegar os eventos dependendo do usuario.
    $sql = "select nome_evento, desc_evento, inicio_evento, final_evento from eventos where id_usuario = '$id_usuario'";
    $stmt = $con->prepare($sql);
	$stmt->execute();
    $eventos_sql = $stmt->fetchAll();

    
	//Select que pega os os ids dos eventos que foram compartilhados.
	$sql_events_shared = "SELECT id_evento_compartilhado FROM eventos_shared WHERE id_usuario_solicitado = '$id_usuario'";
	$stmt = $con->prepare($sql_events_shared);
	$stmt->execute();
	$id_events_shared = $stmt->fetchAll();

	//Aqui eu crio dois arrays para receber apenas os valores dos ids de eventos compartilhados/suas permissoes.
	$arrayEventosShared = array();

	//Foreach para popular os arrays criados acima, para assim pegar apenas o valor dos ids dos eventos compartilhados.
	foreach($id_events_shared as $agrvaipls) {
		//Aqui eu atribuo ao array criado ali em cima, os ids dos eventos compartilhados.
		$arrayEventosShared[] = $agrvaipls['id_evento_compartilhado'];
	}

	//Aqui eu converto os dois novos arrays em strings.
	$string = implode(",", $arrayEventosShared);

	//Aqui eu dou o select dos ids de eventos compartilhados, na tabela de eventos, para assim trazer todos os eventos compartilhados.
	$sql_eventsIds = "SELECT nome_evento, desc_evento, inicio_evento, final_evento FROM `agendaweb`.`eventos` WHERE id_evento IN ($string)";
	$stmt = $con->prepare($sql_eventsIds);
	$stmt->execute();
    $dados_events_shared = $stmt->fetchAll();
    
    $eventos = array_merge($eventos_sql, $dados_events_shared);

    //Contar as linhas que o array de eventos possui.
    $cont = count($eventos);

    //Buscando os dados do usuario para exibir imagem.
    $sql = "SELECT data_cadastro, foto_usuario FROM `usuario` WHERE id_usuario = '$id_usuario'";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $dados = $stmt->fetch();

    $nomeFoto = $dados['foto_usuario'];
    $dataFoto = $dados['data_cadastro'];
    
    //Para selecionar se o usuario enviou uma foto ou nao, se sim mostra a mesma, se nao, mostra uma foto default.
    if(!empty($dados['foto_usuario'])){ 
        $diretorio = '../App/userImages/'.$dataFoto.'/'.$id_usuario.'/'.$nomeFoto;
    }else{ 
        $diretorio = '../tools/img/logo-primary.jpg';
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Relatório</title>
    </head>
    <body>
        <?php 
			ob_start();
			require_once("../tools/lib/fpdf/fpdf.php");
			$pdf = new FPDF("P","mm","A4");
                     
            $pdf->AddPage();
            //Add new font.
            $pdf->AddFont('OpenSans','','OpenSans-Regular.php');
            $pdf->AddFont('OpenSansLight','','OpenSans-Light.php');

            //Header
            $pdf->SetFont('OpenSansLight','',28);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFillColor(255,255,255);

            $pdf->SetFont('OpenSansLight','',14);
            //Pegando data atual para exibir no topo.
            date_default_timezone_set('America/Sao_Paulo');
            $data_atual = date('d-m-Y H:i:s');
            $pdf->SetY(62); $pdf->SetX(20);
            $pdf->Cell(55,10,utf8_decode("Data e hora: $data_atual"),0,0,"C","false");
            $pdf->Image("../tools/img/logo.png",10,15,0,0);
            //Foto do usuario
            $pdf->Image("$diretorio",155,14,40,40);

            //Nome do Usuario
            $pdf->SetY(62); $pdf->SetX(148);
            $pdf->SetFont('','',14);
            $pdf->Cell(55,10,utf8_decode("Usuário: $nome_usuario"),0,0,"C","false");

            $pdf->SetY(80); 
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('','',22);
            $pdf->Cell(0,0,utf8_decode("Eventos"),0,1,'C');
            $pdf->SetFillColor(0,0,0);
            $pdf->SetY(70);
            $pdf->Cell(190,0,'',0,1,'C','true');

            if(empty($eventos)){
                $pdf->SetY(160); 
                $pdf->SetTextColor(192,192,192);
                $pdf->SetFont('','',40);
                $pdf->Cell(0,0,utf8_decode("Não há eventos para mostrar..."),0,1,'C');
                $pdf->SetFillColor(0,0,0);
                $pdf->SetY(70);
                $pdf->Cell(190,0,'',0,1,'C','true');
            }else{
                //Colunas da tabela
                $pdf->SetY(90); $pdf->SetX(5);
                $pdf->SetFont('','',12);
                $pdf->SetFillColor(0,139,139);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(55,10,utf8_decode('Nome do Evento'),1,0,"C","true");
                $pdf->Cell(55,10,utf8_decode('Descrição'),1,0,"C","true");
                $pdf->Cell(45,10,utf8_decode('Data e Hora de Inicio'),1,0,"C","true");
                $pdf->Cell(45,10,utf8_decode('Data e Hora de Final'),1,1,"C","true");

                //Linhas da tabela  
                for ($i = 0; $i < $cont; $i++) { 
                    //Atribuindo variaveis.
                    $a0 = $eventos[$i][0];
                    $a1 = $eventos[$i][1];
                    //Para inverter a data do banco e mostrar-la no padrao correto.
                    $dataini = date('d/m/Y H:i:s', strtotime($eventos[$i][2]));
                    $datafim = date('d/m/Y H:i:s', strtotime($eventos[$i][3]));
                    $a2 = $dataini;
                    $a3 = $datafim;
                    $pdf->SetY(100+$i*10); $pdf->SetX(5);
                    $pdf->SetFont('','',12);
                    $pdf->SetFillColor(173,216,230);
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(55,10, utf8_decode($a0) ,1,0,"C","true");
                    $pdf->Cell(55,10, utf8_decode($a1) ,1,0,"C","true");
                    $pdf->Cell(45,10, utf8_decode($a2) ,1,0,"C","true");
                    $pdf->Cell(45,10, utf8_decode($a3) ,1,1,"C","true");
                }
            }

            $pdf->Output("arquivo.pdf","I");

        ?>
    </body>
</html>