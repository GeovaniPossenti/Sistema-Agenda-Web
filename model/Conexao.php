<?php
class Conexao{
	public function conectar(){
		$user ="root";
		$senha ="";
		$banco ="agendaweb";
		$local ="127.0.0.1";
	$conn = new PDO("mysql:host=$local;dbname=$banco;charset=utf8","$user","$senha");
	return $conn;
	print $conn;
	}
}
	$con = new Conexao;
	if($con->conectar() == true){
		print "";
	}else{
		print "Conexão com o banco não estabelecida corretamente";
	}

	//Algumas funcoes importantes.
    //Cria o hash da senha.
    function make_hash($str){
        return md5($str);
    }
    
    
    //Verifica se o usuário está logado.
    function isLoggedIn(){
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
            return false;
        }
        return true;
	}
	
	function datatime_conversor($str){
        $array = array("T");
        $data_convertida = str_replace($array, " ","$str"); 
        $str = $data_convertida.":00";
        return $str;
    }
?>