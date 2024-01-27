<?php 
header('Content-Type: application/json; charset=utf-8');

$artigo = array();
// Recebe os parâmetros dos artigos

$tematica = array();
// Recebe os parâmetros das temáticas


$response = array();
// Recebe os sites




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
		conteudo.descricaoIndicacao, artigo.codArtigo, artigo.linkArtigo , 
		artigo.resumoArtigo, artigo.anoPublicacao, artigo.autorArtigo, tematica.cod, 
		tematica.nome FROM conteudoTematica INNER JOIN conteudo 
		on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN artigo
		on conteudo.codConteudo = artigo.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod 
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
		conteudo.descricaoIndicacao, artigo.codArtigo, artigo.linkArtigo , 
		artigo.resumoArtigo, artigo.anoPublicacao, artigo.autorArtigo, tematica.cod, 
		tematica.nome FROM conteudoTematica INNER JOIN conteudo 
		on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN artigo
		on conteudo.codConteudo = artigo.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod order by conteudotematica.codConteudo";

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
	
				$artigo["codConteudo"]   = $registro['codConteudo'];
				$artigo["nomeConteudo"]   = $registro['nomeConteudo'];
				$artigo["descricaoConteudo"]   = $registro['descricaoConteudo'];
				$artigo["descricaoIndicacao"]   = $registro['descricaoIndicacao'];
			
				$artigo["linkArtigo"] = $registro['linkArtigo'];
				$artigo["resumoArtigo"] = $registro['resumoArtigo'];
				$artigo["anoPublicacao"] = $registro['anoPublicacao'];
				$artigo["autorArtigo"] = $registro['autorArtigo'];
				// nesse momento precisarei adicionar a tematica no artigo (sera a primeira tematica desse elemento)

				$artigo["tematica"] = array();
				$quantTematicas = 0;
			
				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];			
			
				$artigo["tematica"][$quantTematicas] = $tematica;

			
				$response["artigo"][$quantConteudos] = $artigo;
				$quantConteudos++;	
				$quantTematicas++;

			} else {
				// nesse momento, devo apenas acrescentar a tematica no artigo criado anteriormente 			
				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];
			
				$response["artigo"][$quantConteudos - 1]["tematica"][$quantTematicas] = $tematica;
				$quantTematicas++;

			}
		
			$codConteudoAnterior = $registro['codConteudo'];



		
		
		}
		$response["registros"] = $result->num_rows;
		$response["erro"]= false;
		

	  
	 } else
	 {
	 	$artigo	["mensagem"] = "nenhum artigo encontrado";	
	 }
	 $conn->close();

}
	
	
	echo json_encode($response);
?>