<?php 


header('Content-Type: application/json; charset=utf-8');





include 'dbConnection.php';

		$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

		mysqli_set_charset($conn, "utf8");

		if ($conn->connect_error)
		{
			die("Ops, falhou...:" . $conn->connect_error);
		}
	
		
	
		$sql = "SELECT conteudotematica.codConteudo, conteudotematica.codTematica, 
	conteudo.codConteudo, conteudo.nomeConteudo, conteudo.descricaoConteudo, 
	conteudo.descricaoIndicacao, livro.codConteudo, livro.codLivro, livro.editoraLivro, 
	livro.capaLivro, livro.anoLivro, livro.paginasLivro, livro.autorLivro, livro.generoLivro, 
	tematica.cod, tematica.nome from conteudotematica INNER JOIN conteudo ON 
	conteudo.codConteudo = conteudotematica.codConteudo INNER JOIN livro ON 
	conteudo.codConteudo = livro.codConteudo INNER JOIN tematica on 
	conteudotematica.codTematica = tematica.cod order BY conteudotematica.codConteudo";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			
			$registro = mysqli_fetch_array($result);
			



			// recuperar a imagem Blob do banco de dados
			$image_blob = $registro["capaLivro"];// código para recuperar a imagem Blob do banco de dados
			$string = base64_encode($image_blob);
			echo $string;



      

		}

?>