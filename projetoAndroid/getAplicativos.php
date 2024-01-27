<?php 
header('Content-Type: application/json; charset=utf-8');

$aplicativo = array();
// Recebe os parâmetros dos sites

$tematica = array();
// Recebe os parâmetros das temáticas

$response = array();
// Recebe os sites


$aplicativo ["erro"] = true;

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

		$sql = "SELECT conteudotematica.codConteudo, conteudotematica.codTematica, 
		conteudo.codConteudo, conteudo.nomeConteudo, conteudo.descricaoConteudo, 
		conteudo.descricaoIndicacao, aplicativo.codConteudo, aplicativo.codAplicativo, 
		aplicativo.logoAplicativo, aplicativo.desenvolvedoresAplicativo, aplicativo.linkAplicativo, 
		aplicativo.gratisAplicativo, tematica.cod, 
		tematica.nome FROM conteudoTematica INNER JOIN conteudo 
		on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN aplicativo
		on conteudo.codConteudo = aplicativo.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod 
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
		conteudo.descricaoIndicacao, aplicativo.codConteudo, aplicativo.codAplicativo, 
		aplicativo.logoAplicativo, aplicativo.desenvolvedoresAplicativo, aplicativo.linkAplicativo, 
		aplicativo.gratisAplicativo, tematica.cod, 
		tematica.nome FROM conteudoTematica INNER JOIN conteudo 
		on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN aplicativo
		on conteudo.codConteudo = aplicativo.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod 
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

				$aplicativo["codConteudo"]   = $registro['codConteudo'];
				$aplicativo["nomeConteudo"]   = $registro['nomeConteudo'];
				$aplicativo["descricaoConteudo"]   = $registro['descricaoConteudo'];
				$aplicativo["descricaoIndicacao"]   = $registro['descricaoIndicacao'];
				
				// recuperar a imagem Blob do banco de dados
				$imagemBlob = $registro["logoAplicativo"];// código para recuperar a imagem Blob do banco de dados
				$imagemString = base64_encode($imagemBlob);
				$aplicativo["logoAplicativo"] = $imagemString;

				$aplicativo["linkAplicativo"] = $registro['linkAplicativo'];
				$aplicativo["desenvolvedoresAplicativo"] = $registro['desenvolvedoresAplicativo'];
				$aplicativo["gratisAplicativo"] = $registro['gratisAplicativo'];

				// nesse momento precisarei adicionar a tematica no artigo (sera a primeira tematica desse elemento)
				$aplicativo["tematica"] = array();
				$quantTematicas = 0;
			
				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];			
			
				$aplicativo["tematica"][$quantTematicas] = $tematica;

		

				$response["aplicativo"][$quantConteudos] = $aplicativo;
				$quantConteudos++;	
				$quantTematicas++;

			} else {

				// nesse momento, devo apenas acrescentar a tematica no artigo criado anteriormente 			
				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];
			
				$response["aplicativo"][$quantConteudos - 1]["tematica"][$quantTematicas] = $tematica;
				$quantTematicas++;

			}

			$codConteudoAnterior = $registro['codConteudo'];

		
		}

		$response["registros"] = $result->num_rows;
		$response["erro"]= false;
		
		
		

	 } 
	 else
	 {
	 	$aplicativo	["mensagem"] = "nenhum aplicativo encontrado";	
	 }
	 $conn->close();
	}


	echo json_encode($response);
?>