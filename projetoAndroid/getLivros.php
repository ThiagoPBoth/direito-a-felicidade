<?php 
header('Content-Type: application/json; charset=utf-8');

$livros = array();
// Recebe os parâmetros dos sites

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
			conteudo.descricaoIndicacao, livro.codConteudo, livro.codLivro, livro.editoraLivro, 
			livro.capaLivro, livro.anoLivro, livro.paginasLivro, livro.autorLivro, livro.generoLivro, 
			tematica.cod, tematica.nome from conteudotematica INNER JOIN conteudo ON 
			conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN livro ON 
			conteudo.codConteudo = livro.codConteudo INNER JOIN tematica on 
			conteudotematica.codTematica = tematica.cod 
			WHERE conteudo.codConteudo IN (
    			SELECT conteudotematica.codConteudo
    			FROM conteudotematica
    			INNER JOIN tematica ON conteudotematica.codTematica = tematica.cod
    			WHERE tematica.cod = $codIntTematica  
			)
			order BY conteudotematica.codConteudo";

		} else {

			$sql = "SELECT conteudotematica.codConteudo, conteudotematica.codTematica, 
			conteudo.codConteudo, conteudo.nomeConteudo, conteudo.descricaoConteudo, 
			conteudo.descricaoIndicacao, livro.codConteudo, livro.codLivro, livro.editoraLivro, 
			livro.capaLivro, livro.anoLivro, livro.paginasLivro, livro.autorLivro, livro.generoLivro, 
			tematica.cod, tematica.nome from conteudotematica INNER JOIN conteudo ON 
			conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN livro ON 
			conteudo.codConteudo = livro.codConteudo INNER JOIN tematica on 
			conteudotematica.codTematica = tematica.cod order BY conteudotematica.codConteudo";


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
				
				

				$livros["codConteudo"]   = $registro['codConteudo'];
				$livros["nomeConteudo"]   = $registro['nomeConteudo'];
				$livros["descricaoConteudo"]   = $registro['descricaoConteudo'];
				$livros["descricaoIndicacao"]   = $registro['descricaoIndicacao'];
				

				
				$livros["editoraLivro"]  = $registro['editoraLivro'];
				
				// recuperar a imagem Blob do banco de dados
				$imagemBlob = $registro["capaLivro"];// código para recuperar a imagem Blob do banco de dados
				$imagemString = base64_encode($imagemBlob);
			

				$livros["capaLivro"] = $imagemString;
				$livros["anoLivro"] = $registro['anoLivro'];
				$livros["paginasLivro"] = $registro['paginasLivro'];
				$livros["autorLivro"] = $registro['autorLivro'];
				$livros["generoLivro"] = $registro['generoLivro'];

				// nesse momento precisarei adicionar a tematica no livro (sera a primeira tematica desse elemento)
				$livros["tematica"] = array();

				$quantTematicas = 0;

				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];

				$livros["tematica"][$quantTematicas] = $tematica;

				$response["livros"][$quantConteudos] = $livros;
				$quantConteudos++;	
				$quantTematicas++;
			
			} else {
			
				// nesse momento, devo apenas acrescentar a tematica no artigo criado anteriormente 			
				$tematica["codTematica"] = $registro['codTematica'];
				$tematica["nomeTematica"] = $registro['nome'];

				$response["livros"][$quantConteudos - 1]["tematica"][$quantTematicas] = $tematica;
				$quantTematicas++;

			}

			$codConteudoAnterior = $registro['codConteudo'];

			
		}

		//$response["registros"] = $result->num_rows;
		$response["erro"]= false;
		
		
		

	 } 
	 else
	 {
	 	$livros	["mensagem"] = "nenhum livro encontrado";	
	 }
	 $conn->close();
	
	}

	echo json_encode($response);
?>