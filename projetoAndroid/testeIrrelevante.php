 <?php 
header('Content-Type: application/json; charset=utf-8');

$series = array();
// Recebe os parâmetros das series

$tematica = array();
// Recebe os parâmetros das temáticas

$response = array();
// Recebe as series



	
	

		include 'dbConnection.php';

		$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

		mysqli_set_charset($conn, "utf8");

		if ($conn->connect_error)
		{
			die("Ops, falhou...:" . $conn->connect_error);
		}
	

	
		$sql = "SELECT conteudotematica.codConteudo, conteudotematica.codTematica, 
		conteudo.codConteudo, conteudo.nomeConteudo, conteudo.descricaoConteudo, 
		conteudo.descricaoIndicacao, serie.codConteudo, serie.codSerie, serie.capaSerie, 
		serie.sinopseSerie, serie.temporadaSerie, serie.anoLancamentoSerie, serie.plataformaSerie, 
		tematica.cod, tematica.nome from conteudotematica INNER JOIN conteudo ON 
		conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN serie ON 
		conteudo.codConteudo = serie.codConteudo INNER JOIN tematica on 
		conteudotematica.codTematica = tematica.cod order BY conteudotematica.codConteudo";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{ 

			$qtdDados = $result->num_rows;
			
		
		$codConteudoAnterior = -1;	
		$quantConteudos = 0;

		$tenhoFiltro = 1;
		$filtro = "Cansaço";
		
		$acheiFiltro = 0;
 
		for($i = 0; $i < $qtdDados; $i++)
		{
			$registro = mysqli_fetch_array($result);

			

			if ($codConteudoAnterior != $registro['codConteudo']) {	

				// nesse momento precisarei adicionar a tematica no livro (sera a primeira tematica desse elemento)
				$series["tematica"] = array();

				$quantTematicas = 0;

				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];
	
				

				
				
				if ($tenhoFiltro == 1) {
					if ($filtro == $tematica["nomeTematica"]) {
						
						$series["tematica"][$quantTematicas] = $tematica;

						$series["codConteudo"]   = $registro['codConteudo'];
						$series["nomeConteudo"]   = $registro['nomeConteudo'];
						$series["descricaoConteudo"]   = $registro['descricaoConteudo'];
						$series["descricaoIndicacao"]   = $registro['descricaoIndicacao'];
				

				
						$series["codSerie"]  = $registro['codSerie'];
				
						// recuperar a imagem Blob do banco de dados
						$imagemBlob = $registro["capaSerie"];// código para recuperar a imagem Blob do banco de dados
						$imagemString = base64_encode($imagemBlob);
			

						$series["capaSerie"] = $imagemString;
						$series["sinopseSerie"] = $registro['sinopseSerie'];
						$series["temporadaSerie"] = $registro['temporadaSerie'];
						$series["anoLancamentoSerie"] = $registro['anoLancamentoSerie'];
						$series["plataformaSerie"] = $registro['plataformaSerie'];

				

						$response["series"][$quantConteudos] = $series;
						$quantConteudos++;	
						$quantTematicas++;						

						$acheiFiltro = 1;
					} else {
						
					}
					
				}
				

				
			
			} else {
				
				if ($acheiFiltro == 1) {
					// nesse momento, devo apenas acrescentar a tematica no artigo criado anteriormente 			
					$tematica["codTematica"] = $registro['codTematica'];
					$tematica["nomeTematica"] = $registro['nome'];

					$response["series"][$quantConteudos - 1]["tematica"][$quantTematicas] = $tematica;
					$quantTematicas++;
				}

			}

			$codConteudoAnterior = $registro['codConteudo'];

			
		}

		//$response["registros"] = $result->num_rows;
		$response["erro"]= false;
		
		
		

	 } 
	 else
	 {
	 	$livros	["mensagem"] = "nenhuma serie encontrado";	
	 }
	 $conn->close();
	
	

	echo json_encode($response);
?>