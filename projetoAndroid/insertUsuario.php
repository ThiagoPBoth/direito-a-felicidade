<?php 
header('Content-Type: application/json; charset=utf-8');

	
$response = array();
$response ["erro"] = true;
$response ["oa"] = "sssss";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	include 'dbConnection.php';

	$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_error) {
		die("Ops, falhou...:" . $conn->connect_error);
	}

	$nomeUsuario = "'".$_POST['nomeUsuario']."'";
	$generoUsuario = "'".$_POST['generoUsuario']."'";
	$tipoUsuario = "'".$_POST['tipoUsuario']."'";
	$emailUsuario = "'".$_POST['emailUsuario']."'";
	$senhaUsuario = "'".$_POST['senhaUsuario']."'";

	$sql = "INSERT INTO usuario (nomeUsuario, generoUsuario, tipoUsuario, emailUsuario, senhaUsuario, matriculaEstudante, matriculaServidor, cargoServidor, siapeServidor) 
		VALUES ($nomeUsuario, $generoUsuario, $tipoUsuario, $emailUsuario, $senhaUsuario, '123456','123456','123456','123456')"; 

	$result = $conn->query($sql);
	if ($result === TRUE) {

	

	//$registro = mysqli_fetch_array($result);	

	//$response["registros"] = $result->num_rows;
	$response["erro"] = false; 	
	$response["emailUsuario"]  = 	'Deu certo';
	
	
	 } else{

	 	$response["mensagem"] = "Não foi possível";
	 	
	 }

	 $conn->close();
	}
	
	echo json_encode($response);

?>