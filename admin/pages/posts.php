<?php

	try
	{
		// On se connecte à MySQL
		$bdd = new PDO ('mysql:host=localhost;dbname=ahidamusavudb;charset=utf8', 'root', '');
	}
	catch(Exception $e)
	{
		// En cas d'erreur on affiche un message et on arrête tout
			die('Erreur : '.$e->getMessage());
	}

	if (!isset($_GET['order'])) {
		$_GET['order'] = 'ASC';
	}

	// On a reçu des données en POST
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$post_title = strip_tags(trim($_POST["post_title"]));
		$post_title = str_replace(array("\r","\n"),array("",""), $post_title);

		$post_content = strip_tags(trim($_POST["post_content"]));
		//var_dump($_POST);
	
		if (isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['post_id'])) {
			// Cas du UPDATE 
			if ($_POST['action'] == "update") {
				$requeteUpdate = "UPDATE post SET postTitle='$post_title', postContent='$post_content', " . "WHERE id='" . $_POST['post_id'] . "'";
				$reponseUpdate = $bdd->query($requeteUpdate);
				$reponseUpdate->closeCursor();
			}
			// Cas du DELETE 
			else if ($_POST['action'] == "delete") {
				$requeteDelete = "DELETE FROM posts WHERE id= '" .  $_POST['post_id'] . "'" ;
				$reponseDelete = $bdd->query($requeteDelete);
			}
		}else{
			$requeteInsert = "INSERT INTO posts(post_title, post_content) VALUES('$post_title', '$post_content')";
			$reponseInsert = $bdd->query($requeteInsert);
			$reponseInsert->closeCursor();

		}
	}

	if (isset($_GET['post_id']) && isset($_GET['action']) && $_GET['action'] == "update") {
		$requestUserId = "SELECT * FROM posts WHERE id='" . $_GET['post_id'] . "'";
		$reponseUserId = $bdd->query($requestUserId); 
		$currentUser = $reponseUserId->fetch();
		$reponseUserId->closeCursor();
	}

	$requete = "SELECT * FROM posts";
		if (isset($_GET['field'])) {
			$requete .= " ORDER BY " . $_GET['field'] . " " . $_GET['order'];
			$_GET['order'] = $_GET['order'] == "ASC" ? "DESC" : "ASC";
		}
		
	$reponse = $bdd->query($requete);

?>

<table>
	<tr>
		<th><a href="?page=bdd&order=<?php echo $_GET['order']; ?>&field=post_title">Titre de l'article</a></th>
		<th><a href="?page=bdd&order=<?php echo $_GET['order']; ?>&field=post_content">Contenu de l'article</a></th>
		<th>Actions</th>
	</tr>
<?php 

	while ($articles = $reponse->fetch()) {
		echo '<tr style="border : 1px solid; width : 5vw">' . '
			<td style="border : 1px solid; width 5vw">'	. $articles['post_title'] . '</td>
			<td style="border : 1px solid; width : 5vw">' . $articles['post_content'] . '</td>
			<td><a href="?page=bdd&user_id='.$articles['id'].'&action=update">Modifier</a></td>
			<td><a onclick="confirmerSuppression(\'' .$articles['id'].'\', \''.$articles['post_title'].'\', \''.$articles['post_content'].'\')">Supprimer</a></td>
		</tr>';
}

?>
</table>
<div>
	<h2>Ajouter un article</h2>
	<form action="index.php?page=bdd" method="POST">
		<div>
			<label for="post_title">Titre de l'article</label><input type="text" name="post_title" placeholder="Entrez votre titre ici" value="<?php echo isset($_GET['action']) && $_GET['action'] == 'update' ? $currentUser['post_title'] : ''; ?>">
		</div>
		<div>
			<label for="post_content">Contenu de l'article</label><input type="text" name="post_content" placeholder="Entrez votre texte ici" value="<?php echo isset($_GET['action']) && $_GET['action'] == 'update' ? $currentUser['post_content'] : ''; ?>">
		</div>
		<div>
			<input id="inputDelete" type="hidden" name="action" value="<?php echo isset($_GET['action']) ? $_GET['action'] : '' ; ?>";/>
			<input id="inputUserId" type="hidden" value="<?php echo isset($_GET['post_id']) ? $_GET['post_id'] : '-1'; ?>" name="post_id"/>
			<input id="submitBtn" type="submit" value="<?php echo isset($_GET['post_id']) ? "Modifier" : "Enregistrer"; ?>" />
		</div>
	</form>
	<script type="text/javascript">
		function confirmerSuppression (post_id, post_title, post_content) {
			if (confirm("Voulez vous vraiment supprimer cette entrée [" + post_title + "] ?")) { 
				document.querySelector("#inputDelete").value = "delete";
				document.querySelector("#inputUserId").value = post_title;
				document.querySelector("#submitBtn").click();
				return false;
			}
		}
	</script>
</div>