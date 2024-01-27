<?php 
header('Content-Type: application/json; charset=utf-8');

$sites = array();
// Recebe os parâmetros dos sites

$tematica = array();
// Recebe os parâmetros das temáticas

$response = array();
// Recebe os sites


$response ["erro"] = true;
			
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
			conteudo.descricaoIndicacao, paginaweb.codPagina, paginaweb.linkPagina , 
			paginaweb.autorPagina, paginaweb.codConteudo, tematica.cod, 
			tematica.nome FROM conteudoTematica INNER JOIN conteudo 
			on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN paginaweb
			on conteudo.codConteudo = paginaweb.codConteudo inner JOIN tematica ON 
			conteudotematica.codTematica = tematica.cod 
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
			conteudo.descricaoIndicacao, paginaweb.codPagina, paginaweb.linkPagina , 
			paginaweb.autorPagina, paginaweb.codConteudo, tematica.cod, 
			tematica.nome FROM conteudoTematica INNER JOIN conteudo 
			on conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN paginaweb
			on conteudo.codConteudo = paginaweb.codConteudo inner JOIN tematica ON 
			conteudotematica.codTematica = tematica.cod order by conteudotematica.codConteudo";

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
					
	
					$sites["codConteudo"]   = $registro['codConteudo'];
					$sites["nomeConteudo"]   = $registro['nomeConteudo'];
					$sites["descricaoConteudo"]   = $registro['descricaoConteudo'];
					$sites["descricaoIndicacao"]   = $registro['descricaoIndicacao'];
				
				
					$sites["linkPagina"]  = $registro['linkPagina'];
					$sites["autorPagina"] = $registro['autorPagina'];

					// nesse momento precisarei adicionar a tematica no artigo (sera a primeira tematica desse elemento)

					$sites["tematica"] = array();
					$quantTematicas = 0;
			
					$tematica["codTematica"] = $registro['codTematica'];
					$tematica["nomeTematica"] = $registro['nome'];			
			
					$sites["tematica"][$quantTematicas] = $tematica;


				
					$response["site"][$quantConteudos] = $sites;
					$quantConteudos++;	
					$quantTematicas++;

				

				} else {
					// nesse momento, devo apenas acrescentar a tematica no artigo criado anteriormente 			
					$tematica["codTematica"] = $registro['codTematica'];
					$tematica["nomeTematica"] = $registro['nome'];
			
					$response["site"][$quantConteudos - 1]["tematica"][$quantTematicas] = $tematica;
					$quantTematicas++;

				}

				$codConteudoAnterior = $registro['codConteudo'];


			}

			$response["registros"] = $result->num_rows;
			$response["erro"]= false;
				
		
		

	 	} 
	 	else
	 	{
	 		$sites["mensagem"] = "nenhuma página encontrada";	
	 	}
	 	$conn->close();
	}


	echo json_encode($response);
?>