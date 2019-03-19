<?php

try {

	// On se connecte à MySQL
	$bdd = new PDO ('mysql:host=localhost;dbname=ahidamusavudb;charset=utf8', 'root', '');
}

catch (Exception $e) {
	// En cas d'eereur, on affiche un messname et on arrête tout
	die('Erreur : '.$e-> getMessname());
}


if (!isset($_GET['order'])) {
	$_GET['order'] = 'ASC';
}

// On envoie des données en POST et on les sécurise :
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$name = strip_tags(trim($_POST["name"]));
	$name = str_replace(array("\r", "\n"), array(" ", " "), $name);

	$position = $_POST["position"]

	$description = strip_tags(trim($_POST["description"]));

	if (isset($_POST['action']) && isset($_POST['categories_id']) && !empty($_POST['action'])) {
?>

<?php 

$requete = "SELECT * FROM categories";
if (isset($_GET['field'])) {
	$requete .= " ORDER BY " . $_GET['field'] . " " . $_GET['order'];
	$_GET['order'] = $_GET['order'] == "ASC" ? "DESC" : "ASC";
}
$reponse = $bdd-> query($requete);


if (isset($_POST['action']) && isset($_POST['categories_id']) && !empty($_POST['action'])) {


		// On modifie une entrée :
		if ($_POST['action'] == "update") {
			$requeteUpdate = "UPDATE categories SET position='$position', name='$name', description='$description' WHERE id='". $_POST['categories_id'] . "'";
			$reponseUpdate = $bdd-> query($requeteUpdate);
			$reponseUpdate-> closeCursor();
		}


		// On supprime une entrée :
		else if ($_POST['action'] == "delete") {
			$requeteDelete = "DELETE FROM categories WHERE id='" . $_POST['categories_id'] . "'";
			$reponseDelete = $bdd-> query($requeteDelete);
		}
	}

	// On cré une entrée :
	else {
		$requeteInsert = "INSERT INTO categories(position, name, description) VALUES ('$position', '$name', '$description')";
		$reponseInsert = $bdd->query($requeteInsert);
		$reponseInsert -> closeCursor();
	}

// Récupération des Id de chaque entrée :
if (isset($_GET['categories_id']) && isset($_GET['action']) && $_GET['action'] == "update") {
	
	$requeteCategoriesId = "SELECT * FROM categories WHERE id='". $_GET['categories_id'] . "'";
	$reponseCategoriesId = $bdd-> query($requeteCategoriesId);
	$currentCategories = $reponseCategoriesId-> fetch();
	$reponseCategoriesId-> closeCursor();
}
?>

<table>
	<tr>
		<th><a href="?pname=categories&order=<?php echo $_GET['order']; ?>&field=position">Position</th>
		<th><a href="?pname=categories&order=<?php echo $_GET['order']; ?>&field=name">name</th>
		<th><a href="?pname=categories&field=description">Description</th>
		<th colspan="2">Actions</th>
	</tr>
<?php 


// Affichname de la Bdd sous forme de tablau :
while ($categories = $reponse->fetch()) {
	echo '<tr style ="border : 1px solid; width : 5vw">'
		.'<td style ="border : 1px solid; width : 5vw">' . $categories['position'] . '</td>'
		.'<td style ="border : 1px solid; width : 5vw">' . $categories['name'] . '</td>'
		.'<td style ="border : 1px solid; width : 5vw">' . $categories['description'] . '</td>'
		.'<td style ="border : 1px solid; width : 5vw"><a href="?pname=bdd&categories_id='.$categories['id'].'&action=update">Modifier</td>'
		.'<td style ="border : 1px solid; width : 5vw"><a onclick="confirmerSuppression(\''.$categories['id'].'\', \''.$categories['name'].'\')">Supprimer</td>'
		.'</tr>';
}
$reponse-> closeCursor();

?>

</table>

<div>
	<h2>Ajouter une catégorie</h2>
	<form action="index.php?page=categories" method="POST">
		<div>
			<label for="position">position</label><br><input type="text" name="position" placeholder="position" value="<?php echo isset($_GET['action']) && $_GET['action'] == 'update' ? $currentCategories['position'] : ''; ?>">
		</div>
		<br>
		<div>
			<label for="name">name</label><br><input type="text" name="name" placeholder="name" value="<?php echo isset($_GET['action']) && $_GET['action'] == 'update' ? $currentCategories['name'] : ''; ?>">
		</div>
		<br>
		<div>
			<label for="description">Adresse email</label><br><input type="text" name="description" placeholder="Adresse email" value="<?php echo isset($_GET['action']) && $_GET['action'] == 'update' ? $currentCategories['description'] : ''; ?>">
		</div>
		<br>
		<div>
			<input id="inputDelete" type="hidden" name="action" value="<?php echo isset($_GET['action']) ? $_GET['action'] : ''; ?>">
			<input id="inputUserId" type="hidden" value="<?php echo isset($_GET['categories_id']) ? $_GET['categories_id'] : '-1'; ?>" name="categories_id">
			<input id="submitBtn" type="submit" value="<?php echo isset($_GET['categories_id']) ? "Modifier" : "Enregistrer"; ?>">
		</div>

	</form>
</div>