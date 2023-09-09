<?php 
header('Content-Type: application/json; charset=utf-8');

$tematicas = array();
// Recebe os parâmetros das tematicas


$response = array();
// Recebe os sites



	
	

		include 'dbConnection.php';

		$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

		mysqli_set_charset($conn, "utf8");

		if ($conn->connect_error)
		{
			die("Ops, falhou...:" . $conn->connect_error);
		}
	

	
		$sql = "SELECT * from tematica";

		$result = $conn->query($sql);

		$quantTematicas = 0;

		if ($result->num_rows > 0) 
		{ 

			$qtdDados = $result->num_rows;
			
			



			for($i = 0; $i < $qtdDados; $i++)
			{	
				
				$registro = mysqli_fetch_array($result);	
				

				$tematicas["codTematica"] = $registro['cod'];
				$tematicas["nomeTematica"] = $registro['nome'];


				$response["tematica"][$quantTematicas] = $tematicas;
						
				$quantTematicas++;
			
				

			
			

				//$response["registros"] = $result->num_rows;
				$response["erro"]= false;
			}
		
		
		

	 	} 
		 else
		 {
	 		$livros	["mensagem"] = "nenhum livro encontrado";	
	 	}
	 	$conn->close();
	
	

	echo json_encode($response);
?>