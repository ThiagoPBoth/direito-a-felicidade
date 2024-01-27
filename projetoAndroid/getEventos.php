<?php 
header('Content-Type: application/json; charset=utf-8');

$eventos = array();
// Recebe os parâmetros dos sites

$tematica = array();
// Recebe os parâmetros das temáticas

$response = array();
// Recebe os sites



	
	

		include 'dbConnection.php';

		$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

		mysqli_set_charset($conn, "utf8");

		if ($conn->connect_error)
		{
			die("Ops, falhou...:" . $conn->connect_error);
		}

		$codTematica = $_POST['codTematica'];
		$codIntTematica = (int)$codTematica;
	

		if ($codIntTematica != 0) {

			$sql = "SELECT conteudotematica.codConteudo, conteudotematica.codTematica, 
			conteudo.codConteudo, conteudo.nomeConteudo, conteudo.descricaoConteudo, 
			conteudo.descricaoIndicacao, evento.codEvento, evento.dataEvento, evento.localEvento, evento.responsavelEvento, tematica.cod, 
			tematica.nome FROM conteudoTematica INNER JOIN conteudo 
			on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN evento
			on conteudo.codConteudo = evento.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod 
			WHERE conteudo.codConteudo IN (
    			SELECT conteudotematica.codConteudo
    			FROM conteudotematica
    			INNER JOIN tematica ON conteudotematica.codTematica = tematica.cod
    			WHERE tematica.cod = $codIntTematica  
			)
			order by conteudotematica.codConteudo";


		} else {
			$sql = "SELECT conteudotematica.codConteudo, conteudotematica.codTematica, 
			conteudo.codConteudo, conteudo.nomeConteudo, conteudo.descricaoConteudo, 
			conteudo.descricaoIndicacao, evento.codEvento, evento.dataEvento, evento.localEvento, evento.responsavelEvento, tematica.cod, 
			tematica.nome FROM conteudoTematica INNER JOIN conteudo 
			on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN evento
			on conteudo.codConteudo = evento.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod order by conteudotematica.codConteudo";

		}

		

		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{ 

			$qtdDados = $result->num_rows;
			
		
		$codConteudoAnterior = -1;	
		$quantConteudos = 0;

		for($i = 0; $i < $qtdDados; $i++)
		{
			$registro = mysqli_fetch_array($result);



			if ($codConteudoAnterior != $registro['codConteudo']) {	
				
				

				$eventos["codConteudo"]   = $registro['codConteudo'];
				$eventos["nomeConteudo"]   = $registro['nomeConteudo'];
				$eventos["descricaoConteudo"]   = $registro['descricaoConteudo'];
				$eventos["descricaoIndicacao"]   = $registro['descricaoIndicacao'];
				

				
				$eventos["codEvento"]  = $registro["codEvento"];
				
			
				$eventos["dataEvento"] = $registro['dataEvento'];
				$eventos["localEvento"] = $registro['localEvento'];
				$eventos["responsavelEvento"] = $registro['responsavelEvento'];
			

				// nesse momento precisarei adicionar a tematica no livro (sera a primeira tematica desse elemento)
				$eventos["tematica"] = array();

				$quantTematicas = 0;

				$tematica["codTematica"] = $registro['cod'];
				$tematica["nomeTematica"] = $registro['nome'];

				$eventos["tematica"][$quantTematicas] = $tematica;

				$response["eventos"][$quantConteudos] = $eventos;
				$quantConteudos++;	
				$quantTematicas++;
			
			} else {
			
				// nesse momento, devo apenas acrescentar a tematica no artigo criado anteriormente 			
				$tematica["codTematica"] = $registro['cod'];
				$tematica["nomeTematica"] = $registro['nome'];

				$response["eventos"][$quantConteudos - 1]["tematica"][$quantTematicas] = $tematica;
				$quantTematicas++;

			}

			$codConteudoAnterior = $registro['codConteudo'];

			
		}

		//$response["registros"] = $result->num_rows;
		$response["erro"]= false;
		
		
		

	 } 
	 else
	 {
	 	$eventos["mensagem"] = "nenhum evento encontrado";	
	 }
	 $conn->close();
	
	

	echo json_encode($response);
?>