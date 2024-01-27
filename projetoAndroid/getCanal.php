<?php 
header('Content-Type: application/json; charset=utf-8');

$canal = array();
// Recebe os parâmetros dos sites

$tematica = array();
// Recebe os parâmetros das temáticas

$response = array();
// Recebe os sites


$canal ["erro"] = true;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
		$sql = "SELECT conteudo.codConteudo, conteudo.nomeConteudo, conteudo.descricaoConteudo, conteudo.descricaoIndicacao,
        	canalyoutube.codCanal, canalyoutube.linkCanal, canalyoutube.capaCanal, canalyoutube.codConteudo, 
		tematica.cod, tematica.nome
		FROM conteudo
		INNER JOIN canalyoutube ON conteudo.codConteudo = canalyoutube.codConteudo
		INNER JOIN conteudotematica ON conteudo.codConteudo = conteudotematica.codConteudo
		INNER JOIN tematica ON conteudotematica.codTematica = tematica.cod
		WHERE conteudo.codConteudo IN (
    			SELECT conteudotematica.codConteudo
    			FROM conteudotematica
    			INNER JOIN tematica ON conteudotematica.codTematica = tematica.cod
    			WHERE tematica.cod = $codIntTematica  
		)
		ORDER BY conteudotematica.codConteudo";
	} else  {
		$sql = "SELECT conteudotematica.codConteudo, conteudotematica.codTematica, 
		conteudo.codConteudo, conteudo.nomeConteudo, conteudo.descricaoConteudo, 
		conteudo.descricaoIndicacao, canalyoutube.codConteudo, canalyoutube.codCanal, canalyoutube.linkCanal , 
		canalyoutube.capaCanal, tematica.cod, 
		tematica.nome FROM conteudoTematica INNER JOIN conteudo 
		on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN canalyoutube
		on conteudo.codConteudo = canalyoutube.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod 
		order by conteudotematica.codConteudo";
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

					
	
				$canal["codConteudo"]   = $registro['codConteudo'];
				$canal["nomeConteudo"]   = $registro['nomeConteudo'];
				$canal["descricaoConteudo"]   = $registro['descricaoConteudo'];
				$canal["descricaoIndicacao"]   = $registro['descricaoIndicacao'];
				
				$canal["linkCanal"] = $registro['linkCanal'];

				// recuperar a imagem Blob do banco de dados
				$imagemBlob = $registro["capaCanal"];// código para recuperar a imagem Blob do banco de dados
				$imagemString = base64_encode($imagemBlob);
			

				$canal["capaCanal"] = $imagemString;

				// nesse momento precisarei adicionar a tematica no artigo (sera a primeira tematica desse elemento)
				$canal["tematica"] = array();

				$quantTematicas = 0;

				$tematica["codTematica"] = $registro['cod'];
				$tematica["nomeTematica"] = $registro['nome'];

				$canal["tematica"][$quantTematicas] = $tematica;
				
				$response["canal"][$quantConteudos] = $canal;
				$quantConteudos++;	
				$quantTematicas++;
				
				} else {
					// nesse momento, devo apenas acrescentar a tematica no artigo criado anteriormente
					$tematica["codTematica"] = $registro['cod'];
					$tematica["nomeTematica"] = $registro['nome'];
			
					$response["canal"][$quantConteudos - 1]["tematica"][$quantTematicas] = $tematica;
					$quantTematicas++;

				}

				$codConteudoAnterior = $registro['codConteudo'];


			//$response["registros"] = $result->num_rows;
			//$response["erro"]= false;
			//$response["canal"][$i] = $canal;
		}

		$response["registros"] = $result->num_rows;
		$response["erro"]= false;
		
		

	 } 
	 else {
	 	$canal	["mensagem"] = "nenhum canal encontrado";	
	 }
	 	$conn->close();
	
}

	echo json_encode($response);
?>