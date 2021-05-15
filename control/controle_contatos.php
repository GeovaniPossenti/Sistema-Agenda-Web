<?php   
    include_once "../model/Conexao.php"; //Posui As funcoes de hash e de Session.
    session_start();
    //Para ver se é Cadastro ou Login
    @$op = $_GET['id'];
    if($op == 'cad'){   

        @$usuario = $_SESSION['user_id'];
        @$nome_usuario = $_SESSION['user_name'];

        $idusuario = isset($_POST['idusuario']) ? $_POST['idusuario'] : '';
        $nomeContato = isset($_POST['nomeContato']) ? $_POST['nomeContato'] : '';
        $emailContato = isset($_POST['emailContato']) ? $_POST['emailContato'] : '';

        $conn = new Conexao;
        $con = $conn->conectar();

        //Pra ver se ele esta tentando adicionar ele mesmo.
        $sql_select_usuario = "SELECT * from usuario WHERE id_usuario = '$usuario'";
        $stmt = $con->prepare($sql_select_usuario);
        $stmt->execute();
        $array_select_usuarios = $stmt->fetch();
        $email_usuario = $array_select_usuarios['email_usuario'];

        if($emailContato == $email_usuario){
            header('Location: ../agenda.php');
            //Session do alerta.
            $_SESSION['alerts'] = 'contatoIgualAoUsuario';
        }else{
            //Aqui eu verifico se o email digitado é nosso cliente.
            $sql_select_email = "SELECT email_usuario FROM `usuario` WHERE BINARY email_usuario = '$emailContato'";
            $stmt = $con->prepare($sql_select_email);
            $stmt->execute();
            $ArrayselectEmail = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Se aquele email nao existir, ele volta pra agenda e exibe mensagem de erro.
            if (count($ArrayselectEmail) <= 0){
                header('Location: ../agenda.php');
                //Session do alerta.
                $_SESSION['alerts'] = 'emailContatoInv';
            }else{
                //Para ver se aquele contato ja foi cadastrado naquela conta.
                $sql_select_contatos = "SELECT * FROM `contatos` WHERE email_contato = '$emailContato' AND id_usuario = '$idusuario'";
                $stmt = $con->prepare($sql_select_contatos);
                $stmt->execute();
                $dados_contatos_daquele_usuario = $stmt->fetch();
                
                //Se o email digitado existir no banco, e ja nao estiver cadastrado nos contatos daquele usuario,  ele grava na tabela de contatos.
                if(empty($dados_contatos_daquele_usuario)){
                    $sql_insert_contatos = "INSERT INTO `contatos`(`id_usuario`, `nome_contato`, `email_contato`) VALUES (?,?,?)";
                    $stmt = $con->prepare($sql_insert_contatos);
                    $stmt->bindParam(1, $idusuario);
                    $stmt->bindParam(2, $nomeContato);
                    $stmt->bindParam(3, $emailContato);
                    $stmt->execute();
        
                    //e volta para a agenda com um alerta.
                    header('Location: ../agenda.php');
                    //Session do alerta.
                    $_SESSION['alerts'] = 'contatoCadOk';
                }else{
                    header('Location: ../agenda.php');
                    //Session do alerta.
                    $_SESSION['alerts'] = 'emailContatoJaCadastrado';
                }
            }
        }
    }elseif($op == 'alt'){
        $id_contato = $_POST['id_contato'];
        $nomeContato = $_POST['nomeContato'];

        $conn = new Conexao;
        $con = $conn->conectar();

        $sql_update_contatos = "UPDATE `contatos` SET `nome_contato`= '$nomeContato' WHERE id_contato = '$id_contato'";
        $stmt = $con->prepare($sql_update_contatos);
        $stmt->execute();

        //Sessions dos alertas.
        $_SESSION['alerts'] = 'altContatosOk';
        header('Location: ../agenda.php');
        
    }elseif($op == 'del'){
        $id_contato = $_GET['id_contato'];

        $conn = new Conexao;
        $con = $conn->conectar();
        
        $sql_delete_contato = "DELETE FROM `contatos` WHERE id_contato = '$id_contato'";
        $stmt = $con->prepare($sql_delete_contato);
        $stmt->execute();

        //e volta para a agenda com um alerta.
        header('Location: ../agenda.php');
        //Session do alerta.
        $_SESSION['alerts'] = 'contatoDelOk';
    }





?>