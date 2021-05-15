<?php   
    include_once "../model/Conexao.php"; //Posui As funcoes de hash e de Session.
    session_start();
    //Para ver se é Cadastro ou Login
    @$op = $_GET['id'];
    //Para Se cadastrar.
        if ($op == 'cad'){
            $nome = isset($_POST['nomeUsuarioReg']) ? $_POST['nomeUsuarioReg'] : '';
            $email = isset($_POST['emailUsuarioReg']) ? $_POST['emailUsuarioReg'] : '';
            $password = isset($_POST['senhaUsuarioReg']) ? $_POST['senhaUsuarioReg'] : '';
            $datanasc = isset($_POST['dataNascReg']) ? $_POST['dataNascReg'] : '';
            $dataCad = date('Y-m-d');
            $imagem = $_FILES['fotoUsuario']['name'];

            $typeImagem = $_FILES['fotoUsuario']['type'];
            $sizeImagem = $_FILES['fotoUsuario']['size'];
            //Tamanho maximo de uma imagem, que o usuario pode enviar.
            $max_size = 1000000;

            //Para validar o campo de file, se o cara enviar algo diferente de uma imagem, diz que nao e possivel.
            if(!empty($imagem) && $typeImagem != 'image/jpeg' && $typeImagem != 'image/jpg' && $typeImagem != 'image/png' && $typeImagem != 'image/gif'){
                header('Location: ../view/cadastroForm.php');
                //Para passar alerta que a imagem é inválida.
                $_SESSION['alerts'] = "imgUplFail";
            }
            //Para verificar size da imagem, se for maior que 1,5mb ele nao deixa enviar.
            elseif(!empty($imagem) && $sizeImagem > $max_size){
                header('Location: ../view/cadastroForm.php');
                //Para passar alerta que a imagem é inválida.
                $_SESSION['alerts'] = "imgSizeFail";
            }elseif($datanasc >= $dataCad){
                header('Location: ../view/cadastroForm.php');
                //Para passar alerta que a imagem é inválida.
                $_SESSION['alerts'] = "dataNascInvalida";
            }else{
                //Cria a senha em MD5 com uma funcao que esta na conexao.php.
                $passwordHash = make_hash($password);

                $conn = new Conexao;
                $con = $conn->conectar();

                $sql1 = ("SELECT email_usuario, senha_usuario FROM usuario WHERE email_usuario = ?");
                $stmt = $con->prepare($sql1);
                $stmt->bindParam(1, $email);
                $stmt->execute();

                //Coloca todos os resultados dentro de um array.
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //Faz a contagem pra ver se ja existe no banco.
                if (count($users) >= 1){
                    header("location: ../view/cadastroForm.php");
                    //Session do alerta.
                    $_SESSION['alerts'] = 'cadFail';
                }else{
                    //Else faz a insercao dos dados no banco.
                    $sql = "INSERT INTO `usuario`(`nome_usuario`, `email_usuario`, `senha_usuario`, `data_nasc`, `data_cadastro`, `foto_usuario`, `wallpaper_usuario`) VALUES (?,?,?,?,?,?,'')";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(1, $nome);
                    $stmt->bindParam(2, $email);
                    $stmt->bindParam(3, $passwordHash);
                    $stmt->bindParam(4, $datanasc);
                    $stmt->bindParam(5, $dataCad);
                    $stmt->bindParam(6, $imagem);
                    $stmt->execute();

                    //Pega o ultimo id que foi inserido.
                    $ultimoid = $con->lastInsertId();

                    //Criando diretorio para salvar imagem.
                    //Cria na pasta img_usuarios, a pasta do dia do cadastro, dentro uma nova pasta com o ID do usuario.
                    $diretorio = '../img_usuarios/'.$dataCad.'/'.$ultimoid.'/';

                    //Aqui eu realmente crio o diretorio.
                    mkdir($diretorio, 0777, true);

                    //Salvando a imagem na pasta criada.
                    move_uploaded_file($_FILES['fotoUsuario']['tmp_name'], $diretorio.$imagem);

                    //Indo a pagina de login.
                    header("location: ../view/loginForm.php");
                    //Session do alerta.
                    $_SESSION['alerts'] = 'cadOk';
                }
            }
        }
    //Para efetuar Login.
        elseif($op == 'log'){
            $email = isset($_POST['emailUsuarioLog']) ? $_POST['emailUsuarioLog'] : '';
            $password = isset($_POST['senhaUsuarioLog']) ? $_POST['senhaUsuarioLog'] : '';
            $lembrar = $_POST['lembrar'];

            //Verificando se o checkbox do "Lembrar-se" foi marcado, se sim ele cria um cookie, se nao ele destroi o mesmo.
            if ($lembrar == 'on'){
                setcookie('email', $email, time() + (7200), "/"); //Dura 2 horas.
            }else{
                setcookie('email', $email, time() - (7200), "/");
            }
            
            //Cria a senha em MD5 com uma funcao que esta na conexao.php.
            $passwordHash = make_hash($password);
            
            $conn = new Conexao;
            $con = $conn->conectar();
            
            $sql = "SELECT id_usuario, nome_usuario FROM `usuario` WHERE BINARY email_usuario = ? AND BINARY senha_usuario = ?";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $email);
            $stmt->bindParam(2, $passwordHash);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            //Faz a contagem pra ver se aquele login existe no banco.
            if (count($users) <= 0){
                //Para apagar o cookie quando o usuario erra a senha ou algo do genero.
                setcookie('email', $email, time() - (7200), "/");
                header("location: ../view/loginForm.php");
                //Session do alerta.
                $_SESSION['alerts'] = 'logFail';
                exit;
            }
            
            //Pega o primeiro usuário do array.
            $user = $users[0];
            
            //Session com o logado, id e name do usuarioq que efeutuou login.
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_name'] = $user['nome_usuario'];
            header('Location: ../agenda.php');
            //Session do alerta.
            $_SESSION['alerts'] = 'logOk';
        }
    //Para alterar dados do Usuario.
        elseif($op == 'alt'){
            $id_usuario = $_POST['idusuario'];
            $nome = isset($_POST['newnomeUsuario']) ? $_POST['newnomeUsuario'] : '';
            $email = isset($_POST['newemailUsuario']) ? $_POST['newemailUsuario'] : '';
            $password = isset($_POST['newsenhaUsuario']) ? $_POST['newsenhaUsuario'] : '';
            $imagem = $_FILES['fotoUsuarioNew']['name']; 
             
            $typeImagem = $_FILES['fotoUsuarioNew']['type'];
            $sizeImagem = $_FILES['fotoUsuarioNew']['size'];
            //Tamanho maximo de uma imagem, que o usuario pode enviar.
            $max_size = 1500000;

            //Para validar o campo de file, se o cara enviar algo diferente de uma imagem, diz que nao e possivel.
            if(!empty($imagem) && $typeImagem != 'image/jpeg' && $typeImagem != 'image/jpg' && $typeImagem != 'image/png' && $typeImagem != 'image/gif'){
                header('Location: ../view/perfil.php');
                //Para passar alerta que a imagem é inválida.
                $_SESSION['alerts'] = "imgUplFail";
            }
            //Para verificar size da imagem, se for maior que 1,5mb ele nao deixa enviar.
            elseif(!empty($imagem) && $sizeImagem > $max_size){
                header('Location: ../view/perfil.php');
                //Para passar alerta que a imagem é inválida.
                $_SESSION['alerts'] = "imgSizeFail";
            }else{
                //Senha ATUAL digitada pelo usuario, sempre required.
                $senhaAtual = $_POST['senhaAtual'];
                //Fazendo o hash MD5 da senha atual.
                $senhaAtualHash = make_hash($senhaAtual);

                $conn = new Conexao;
                $con = $conn->conectar();

                //Select para pegar o email do usuario, e ver se o mesmo esta digitando um email que não existe no banco.
                $sqlEmail = "SELECT email_usuario, nome_usuario FROM `usuario` WHERE id_usuario = '$id_usuario'";
                $stmt = $con->prepare($sqlEmail);
                $stmt->execute();
                $emailUsuarioBanco = $stmt->fetch();
                $emailBanco = $emailUsuarioBanco['email_usuario'];

                if($email == $emailBanco){
                    //Grava no banco normalmente
                    //Select para pegar a senha do usuario do banco.
                    $sql = "SELECT senha_usuario FROM `usuario` WHERE id_usuario = '$id_usuario'";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $senha_usuario_banco = $stmt->fetch();
                    $senhaBanco = $senha_usuario_banco['senha_usuario'];

                    //Se a senha ATUAL que o usuario digitar estiver incorreta, ele retorna para a pagina e mostra um alerta.
                    if($senhaAtualHash != $senhaBanco){
                        header('Location: ../view/perfil.php');
                        //Session do alerta.
                        $_SESSION['alerts'] = 'senhaAtualInv';
                    }elseif(($senhaAtualHash == $senhaBanco) && (!empty($imagem))){
                        //Se a senha ATUAL estiver correta e ele enviar uma imagem.

                        //Buscando os dados da imagem do usuario.
                        $sql = "SELECT data_cadastro, foto_usuario FROM `usuario` WHERE id_usuario = '$id_usuario'";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();
                        $dados = $stmt->fetch();
                        $dataFoto = $dados['data_cadastro'];
                        $nomeFoto = $dados['foto_usuario'];

                        //Para verificar se o usuario digitou ou nao uma senha no campo (Nova Senha).
                        //Se (Nova Senha) for vazio, ele atribui a variavel senha a SenhaAtual Digitada pelo usuario (required).
                        if(empty($password)){
                            $senhaTop = $senhaAtualHash;
                        }else{
                        //Se (Nova Senha) for diferente de vazio, ele faz o hash da seha e atribui a variavel de senha.
                            $senhaTop = make_hash($password);
                        }

                        //Dou o update nos dados digitados pelo usuario, caso ele tenha enviado uma foto.
                        $sql = "UPDATE usuario SET nome_usuario=?, email_usuario=? ,senha_usuario=?, foto_usuario=? WHERE id_usuario = '$id_usuario'";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(1, $nome);
                        $stmt->bindParam(2, $email);
                        $stmt->bindParam(3, $senhaTop);
                        $stmt->bindParam(4, $imagem);
                        $stmt->execute();

                        //Diretorio que fica responsavel por pegar o nome da foto que esta no banco, e conferir de ele precisa excluir ou nao.
                        $diretorio = '../img_usuarios/'.$dataFoto.'/'.$id_usuario.'/'.$nomeFoto;
                        
                        //IF para saber se o usuario ja possue uma foto quando ele der update, se ele ja possuir, apaga a antiga e substitui pela nova foto.
                        if (file_exists($diretorio)) {
                            //Se o arquivo que esta no banco existe eu deleto o mesmo e atribuo o valor default ao diretorio.
                            unlink("$diretorio");
                            //Atribuo novamente o valor default ao diretorio.
                            $diretorio = '../img_usuarios/'.$dataFoto.'/'.$id_usuario.'/';
                        }else{
                            //Se o arquivo nao existir, eu somente atribuo o valor default ao diretorio.
                            $diretorio = '../img_usuarios/'.$dataFoto.'/'.$id_usuario.'/';
                        }
            
                        //Criando as pastas dos usuarios e do dia.
                        mkdir($diretorio, 0777, true);

                        //Salvo a nova imagem na pasta criada.
                        move_uploaded_file($_FILES['fotoUsuarioNew']['tmp_name'], $diretorio.$imagem);

                        //Para voltar para a agenda.
                        header('Location: ../agenda.php');
                        //Session do alerta.
                        $_SESSION['alerts'] = 'perOk';
                    }elseif(($senhaAtualHash == $senhaBanco) && (empty($imagem))){
                        //Para verificar se o usuario digitou ou nao uma senha no campo (Nova Senha).
                        //Se (Nova Senha) for vazio, ele atribui a variavel senha a SenhaAtual Digitada pelo usuario (required).
                        if(empty($password)){
                            $senhaTop = $senhaAtualHash;
                        }else{
                        //Se (Nova Senha) for diferente de vazio, ele faz o hash da seha e atribui a variavel de senha.
                            $senhaTop = make_hash($password);
                        }

                        //Dou o update nos dados, porem com a condicao de que o usuario nao enviou uma foto.
                        $sql = "UPDATE usuario SET nome_usuario=?, email_usuario=? ,senha_usuario=? WHERE id_usuario = '$id_usuario'";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(1, $nome);
                        $stmt->bindParam(2, $email);
                        $stmt->bindParam(3, $senhaTop);
                        $stmt->execute();

                        //Para voltar para a agenda.
                        header('Location: ../agenda.php');
                        //Session do alerta.
                        $_SESSION['alerts'] = 'perOk';
                    }
                }elseif($emailBanco != $email){
                    //busco o email digitado para ver se existe no banco.
                    $sql = "SELECT email_usuario FROM `usuario` WHERE email_usuario = '$email'";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $emailGravadoBanco = $stmt->fetchAll();
                    //Se haver algum email gravado no banco igual ao que o usuario digitou, volta e mostra msg de erro.
                    if(count($emailGravadoBanco) > 0){
                        header('Location: ../view/perfil.php');
                        //Session do alerta.
                        $_SESSION['alerts'] = 'emailJaExiste';
                    }elseif(count($emailGravadoBanco) == 0){
                        //Select para pegar a senha do usuario do banco.
                        $sql = "SELECT senha_usuario FROM `usuario` WHERE id_usuario = '$id_usuario'";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();
                        $senha_usuario_banco = $stmt->fetch();
                        $senhaBanco = $senha_usuario_banco['senha_usuario'];

                        //Se a senha ATUAL que o usuario digitar estiver incorreta, ele retorna para a pagina e mostra um alerta.
                        if($senhaAtualHash != $senhaBanco){
                            header('Location: ../view/perfil.php');
                            //Session do alerta.
                            $_SESSION['alerts'] = 'senhaAtualInv';
                        }elseif(($senhaAtualHash == $senhaBanco) && (!empty($imagem))){
                            //Se a senha ATUAL estiver correta e ele enviar uma imagem.

                            //Buscando os dados da imagem do usuario.
                            $sql = "SELECT data_cadastro, foto_usuario FROM `usuario` WHERE id_usuario = '$id_usuario'";
                            $stmt = $con->prepare($sql);
                            $stmt->execute();
                            $dados = $stmt->fetch();
                            $dataFoto = $dados['data_cadastro'];
                            $nomeFoto = $dados['foto_usuario'];

                            //Para verificar se o usuario digitou ou nao uma senha no campo (Nova Senha).
                            //Se (Nova Senha) for vazio, ele atribui a variavel senha a SenhaAtual Digitada pelo usuario (required).
                            if(empty($password)){
                                $senhaTop = $senhaAtualHash;
                            }else{
                            //Se (Nova Senha) for diferente de vazio, ele faz o hash da seha e atribui a variavel de senha.
                                $senhaTop = make_hash($password);
                            }

                            //Dou o update nos dados digitados pelo usuario, caso ele tenha enviado uma foto.
                            $sql = "UPDATE usuario SET nome_usuario=?, email_usuario=? ,senha_usuario=?, foto_usuario=? WHERE id_usuario = '$id_usuario'";
                            $stmt = $con->prepare($sql);
                            $stmt->bindParam(1, $nome);
                            $stmt->bindParam(2, $email);
                            $stmt->bindParam(3, $senhaTop);
                            $stmt->bindParam(4, $imagem);
                            $stmt->execute();

                            //Diretorio que fica responsavel por pegar o nome da foto que esta no banco, e conferir de ele precisa excluir ou nao.
                            $diretorio = '../img_usuarios/'.$dataFoto.'/'.$id_usuario.'/'.$nomeFoto;
                            
                            //IF para saber se o usuario ja possue uma foto quando ele der update, se ele ja possuir, apaga a antiga e substitui pela nova foto.
                            if (file_exists($diretorio)) {
                                //Se o arquivo que esta no banco existe eu deleto o mesmo e atribuo o valor default ao diretorio.
                                unlink("$diretorio");
                                //Atribuo novamente o valor default ao diretorio.
                                $diretorio = '../img_usuarios/'.$dataFoto.'/'.$id_usuario.'/';
                            }else{
                                //Se o arquivo nao existir, eu somente atribuo o valor default ao diretorio.
                                $diretorio = '../img_usuarios/'.$dataFoto.'/'.$id_usuario.'/';
                            }
                
                            //Criando as pastas dos usuarios e do dia.
                            mkdir($diretorio, 0777, true);

                            //Salvo a nova imagem na pasta criada.
                            move_uploaded_file($_FILES['fotoUsuarioNew']['tmp_name'], $diretorio.$imagem);

                            //Para voltar para a agenda.
                            header('Location: ../agenda.php');
                            //Session do alerta.
                            $_SESSION['alerts'] = 'perOk';
                        }elseif(($senhaAtualHash == $senhaBanco) && (empty($imagem))){
                            //Para verificar se o usuario digitou ou nao uma senha no campo (Nova Senha).
                            //Se (Nova Senha) for vazio, ele atribui a variavel senha a SenhaAtual Digitada pelo usuario (required).
                            if(empty($password)){
                                $senhaTop = $senhaAtualHash;
                            }else{
                            //Se (Nova Senha) for diferente de vazio, ele faz o hash da seha e atribui a variavel de senha.
                                $senhaTop = make_hash($password);
                            }

                            //Dou o update nos dados, porem com a condicao de que o usuario nao enviou uma foto.
                            $sql = "UPDATE usuario SET nome_usuario=?, email_usuario=? ,senha_usuario=? WHERE id_usuario = '$id_usuario'";
                            $stmt = $con->prepare($sql);
                            $stmt->bindParam(1, $nome);
                            $stmt->bindParam(2, $email);
                            $stmt->bindParam(3, $senhaTop);
                            $stmt->execute();

                            //Para voltar para a agenda.
                            header('Location: ../agenda.php');
                            //Session do alerta.
                            $_SESSION['alerts'] = 'perOk';
                        }
                    }
                }
            }
            $_SESSION['user_name'] = $nome;
        }
    //Para deletar foto de usuario.
        elseif($op == 'del'){
            $id_usuario = $_POST['idusuario'];
            $diretorio = $_POST['diretorio'];

            //Apagando aquele diretorio, com o nome da foto.
            unlink("$diretorio");

            $conn = new Conexao;
            $con = $conn->conectar();

            //"Deletando" o nome da foto no banco.
            $sql = "UPDATE `usuario` SET `foto_usuario` = '' WHERE `usuario`.`id_usuario` = '$id_usuario'";
            $stmt = $con->prepare($sql);
            $stmt->execute();

            header('Location: ../agenda.php');
            //Session do alerta.
            $_SESSION['alerts'] = 'img';
        }
        elseif($op == 'wallpaper'){
            //Aqui eu pego os valores, do id do background a ser guardado no banco.
            $idWallpaper = $_GET['picture'];
            //Aqui a Session do ID do usuario, criada no momento em que ele loga.
            $id_usuarioWallpaper = $_SESSION['user_id'];

            $conn = new Conexao;
            $con = $conn->conectar();

            //Se eu passar o valor = 'default' ele grava no banco default, para mostrar wallpaper padrao.
            if ($idWallpaper == 'default') {
                //Update na tabela para adicionar o wallpaper que o usuario escolher.
                $sql = "UPDATE `usuario` SET `wallpaper_usuario` = '' WHERE `usuario`.`id_usuario` = '$id_usuarioWallpaper'";
                $stmt = $con->prepare($sql);
                $stmt->execute();
            }
            //Se nao, ele armazena no banco o id do wallpaper, de 1 ao infinito.
            else{
                //Update na tabela para adicionar o wallpaper que o usuario escolher.
                $sql = "UPDATE `usuario` SET `wallpaper_usuario` = '$idWallpaper' WHERE `usuario`.`id_usuario` = '$id_usuarioWallpaper'";
                $stmt = $con->prepare($sql);
                $stmt->execute();
            }

            //Volto para a Agenda e exibo um alerta com a session alerts.
            header('Location: ../agenda.php');
            //Session do alerta.
            $_SESSION['alerts'] = 'WallModif';
            
        }
?>