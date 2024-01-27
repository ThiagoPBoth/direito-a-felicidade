<?php 
header('Content-Type: application/json; charset=utf-8');

$filme = array();
// Recebe os parâmetros dos sites

$tematica = array();
// Recebe os parâmetros das temáticas

$response = array();
// Recebe os sites


$filme ["erro"] = true;

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
		conteudo.descricaoIndicacao, filme.codConteudo, filme.codFilme, filme.capaFilme, filme.sinopseFilme, filme.duracaoFilme, filme.anoLancamentoFilme, filme.plataformaFilme, tematica.cod, 
		tematica.nome FROM conteudoTematica INNER JOIN conteudo 
		on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN filme
		on conteudo.codConteudo = filme.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod 
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
		conteudo.descricaoIndicacao, filme.codConteudo, filme.codFilme, filme.capaFilme, filme.sinopseFilme, filme.duracaoFilme, filme.anoLancamentoFilme, filme.plataformaFilme, tematica.cod, 
		tematica.nome FROM conteudoTematica INNER JOIN conteudo 
		on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN filme
		on conteudo.codConteudo = filme.codConteudo inner JOIN tematica ON conteudotematica.codTematica = tematica.cod order by conteudotematica.codConteudo";

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
	
				$filme["codConteudo"]   = $registro['codConteudo'];
				$filme["nomeConteudo"]   = $registro['nomeConteudo'];
				$filme["descricaoConteudo"]   = $registro['descricaoConteudo'];
				$filme["descricaoIndicacao"]   = $registro['descricaoIndicacao'];
				
		
				// recuperar a imagem Blob do banco de dados
				$imagemBlob = $registro["capaFilme"];// código para recuperar a imagem Blob do banco de dados
				$imagemString = base64_encode($imagemBlob);

				$filme["capaFilme"] = $imagemString; 

				$filme["sinopseFilme"] = $registro['sinopseFilme'];
				$filme["duracaoFilme"] = $registro['duracaoFilme'];
				$filme["anoLancamentoFilme"] = $registro['anoLancamentoFilme'];
				$filme["plataformaFilme"] = $registro['plataformaFilme'];

				// nesse momento precisarei adicionar a tematica no artigo (sera a primeira tematica desse elemento)
				$filme["tematica"] = array();
				$quantTematicas = 0;

				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];			
				
				$filme["tematica"][$quantTematicas] = $tematica;

			
				$response["filme"][$quantConteudos] = $filme;
				$quantConteudos++;	
				$quantTematicas++;


			} else {

				// nesse momento, devo apenas acrescentar a tematica no artigo criado anteriormente 			
				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];
			
				$response["filme"][$quantConteudos - 1]["tematica"][$quantTematicas] = $tematica;
				$quantTematicas++;


			}
			
			$codConteudoAnterior = $registro['codConteudo'];


		
		
		}

		$response["registros"] = $result->num_rows;
		$response["erro"]= false;
		
		

	 } 
	 else
	 {
	 	$filme	["mensagem"] = "nenhum filme encontrado";	
	 }
	 $conn->close();
	}


	echo json_encode($response);
?>