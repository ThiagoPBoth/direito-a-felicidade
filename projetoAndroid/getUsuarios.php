<?php 
header('Content-Type: application/json; charset=utf-8');

	
$response = array();
$response ["erro"] = true;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	include 'dbConnection.php';

	$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_error) {
		die("Ops, falhou...:" . $conn->connect_error);
	}

	$emailUsuario = "'".$_POST['emailUsuario']."'";
	$senhaUsuario = "'".$_POST['senhaUsuario']."'";

	$sql = "SELECT * FROM usuario WHERE emailUsuario = $emailUsuario AND senhaUsuario = $senhaUsuario "; 

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {

	$registro = mysqli_fetch_array($result);	

	$response["registros"] = $result->num_rows; 
	$response["erro"] = false; 	
	$response["codUsuario"]  = 	$registro['codUsuario'];
	$response["nomeUsuario"]  = 	$registro['nomeUsuario'];
	$response["generoUsuario"]  = 	$registro['generoUsuario'];
	$response["tipoUsuario"]  = 	$registro['tipoUsuario']; 
	$response["emailUsuario"]  = 	$registro['emailUsuario'];
	$response["senhaUsuario"]  = 	$registro['senhaUsuario'];
	$response["matriculaEstudante"]  = 	$registro['matriculaEstudante'];
	$response["matriculaServidor"]  = 	$registro['matriculaServidor'];
	$response["cargoServidor"]  = 	$registro['cargoServidor'];
	$response["siapeServidor"]  = 	$registro['siapeServidor'];
	
	
	 
	
	 } else{

	 	$response["mensagem"] = "Usuario não existe";
	 	
	 }

	 $conn->close();
	}
$response ["chegou?"] = "sim";
	echo json_encode($response);

?>