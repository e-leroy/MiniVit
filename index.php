<?php 
$version=file_get_contents('./version');
date_default_timezone_set('UTC');
ini_set('display_errors','0');
// DEBUG: ini_set('display_errors','1');

if(!file_exists('./admin.ini.php')) {
// MiniVit non configuré

// création utilisateur
if (!empty($_POST))
	if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email']) ) {
		$username = (string)$_POST['username'];
		$password = (string)$_POST['password'];
		$email = (string)$_POST['email'];
		$hash = password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);
		file_put_contents('./admin.ini.php', '; <?php header("Location: ./"); exit; ?> DO NOT REMOVE THIS LINE'."\n".'[0]'."\n".'user= "'.$username.'"'."\n".'email = "'.$email.'"'."\n".'password = "'.$hash.'"');
		die('Compte crée. <a href="./">Cliquez ici pour continuer</a>');
	}

 // formulaire de création du compte
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
Création du compte administrateur:
<form action="./" method="post">
<input type="text" name="username" value="" placeholder="Nom d'utilisateur" required><br>
<input type="password" name="password" value="" placeholder="Mot de passe" required><br>
<input type="text" name="email" value="" placeholder="Adresse email" required><br>
<input type="submit">
</form>
</body>
</html>
<?php
die;
}

// expiration de session
foreach (glob("*.session") as $filename)
	if ((time() - filemtime($filename)) > 3600)  // default: 1 hour
		unlink($filename); 

// contrôle cookie
if ( file_exists('./'.$_COOKIE["minivit_staySignedIn"].'.session')  )
	$isadmin = TRUE;

// authentication
function adminLogin($username, $password) {
	$ini_array = parse_ini_file('./admin.ini.php');
	if ($username == $ini_array['user']) {
		if ( password_verify($password, $ini_array['password']) ) {
			$sessionstring = '0'.bin2hex(openssl_random_pseudo_bytes(60));
			file_put_contents($sessionstring.'.session', '');
			setcookie("minivit_staySignedIn", $sessionstring, 0);
			return TRUE;
		}
		else
			return FALSE; // mot de passe faux
	}
	else {
		password_verify($password, $ini_array['password']); return FALSE;  // utilisateur faux (on fait quand même tourner le check du mot de passe à vide)
	}  
}

// antibot
function generate_antibot() {
    $letters = array('zéro', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf', 'vingt');
    return $letters[mt_rand(1, 20)];
}

function check_antibot($number, $text_number) {
    $letters = array('zéro', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf', 'vingt');
    return ( array_search( $text_number, $letters ) === intval($number) ) ? true : false;
}


// calcul de l'espace disque utilisé

function espaceutilise($max='') {
 foreach (glob("*") as $filename) {
    $size[] = filesize($filename);
  }
 $size = round(array_sum($size)/1000/1000, 1);
 if(!empty($max)) {
  $pourcent = round($size*100/$max, 1); return $pourcent;
 }
 else return $size;
}

function poidsfichier($fichier) {
 $size = filesize($fichier);
 $size = round($size/1000/1000, 3);
 return $size;
}

// initialisation de la configuration; valeurs par défaut
$panneau_admin = FALSE;
$config=array(
 'meta'=>array(
  'version'=>$version,
  'title'=>'Nom de la soci&eacute;t&eacute;',
  'description'=>'Description de la soci&eacute;t&eacute;',
  'license'=>'Tous droits r&eacute;serv&eacute;s',
  'tracking'=>'',
  'hostingspace'=>'9'),
 'style'=>array(
  'body_color'=>'black',
  'body_color_link'=>'brown',
  'body_color_link_hover'=>'red',
  'body_backgroundcolor1'=>'white',
  'body_backgroundcolor2'=>'lightgrey',
  'body_backgroundimage'=>''),
 'page'=>array(
  'main_title'=>'La soci&eacute;t&eacute;',
  'main_text'=>'(pr&eacute;sentation de la soci&eacute;t&eacute;)',
  'products_title'=>'Produits et Services',
  'products_text'=>'(pr&eacute;sentation des produits et services)',
  'offers_title'=>'Offres sp&eacute;ciales',
  'offers_text'=>'(pr&eacute;sentation des offres sp&eacute;ciales)',
  'contact_title'=>'Contact',
  'contact_text'=>'(informations de contact, horaires, localisation..)'
 )
);

// table des couleurs
$couleurs=array('aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');

// enregistrement / chargement de la configuration depuis le fichier de données
if(!file_exists('./data.json'))
 file_put_contents('./data.json', json_encode($config), LOCK_EX) or die('Le fichier de configuration n\'a pas pu être écrit. Vérifiez les permissions.');
else {

 if(json_decode(file_get_contents('./data.json')) === NULL) {   // correction de config invalide
  copy('./data-prev.json', './data.json'); die('ERREUR: la configuration actuelle est corrompue. Elle a été remplacée par la configuration précédente. <a href="javascript:location.reload();">Cliquer pour recharger la page</a>');
 }

 $config_input = json_decode(file_get_contents('./data.json'), TRUE);
 if(!isset($config_input['meta']['version']) or $config_input['meta']['version'] < $config['meta']['version']) $old = TRUE; // vérification de version de config
 foreach($config_input as $key=>$key2)
  foreach($key2 as $item=>$value)
   $config[$key][$item] = $value;  // récupération des valeurs actuelles

 if(isset($old) and $old == TRUE) { $config['meta']['version']=$version; file_put_contents('./data-new.json', json_encode($config), LOCK_EX) or die('Le fichier de configuration n\'a pas pu être écrit. Vérifiez les permissions.'); } // enregistrement de configuration neuve
}



if(isset($_POST) and !empty($_POST) and $isadmin == TRUE ) {
 foreach($_POST as $key=>$value) {
   if($key == 'tracking') { if(isset($config['meta'][$key])) $config['meta'][$key] = $value; }
   else { if(isset($config['meta'][$key])) $config['meta'][$key] = htmlentities($value, ENT_QUOTES); }
   if(isset($config['style'][$key])) $config['style'][$key] = htmlentities($value, ENT_QUOTES);
   if(isset($config['page'][$key])) $config['page'][$key] = $value;
 }

// nettoyage des anciennes valeurs de config
if(isset($config['meta']['antispam_q']))
	unset($config['meta']['antispam_q']);
if(isset($config['meta']['antispam_r']))
	unset($config['meta']['antispam_r']);
if(isset($config['meta']['email']))
	unset($config['meta']['email']);

// écriture du nouveau fichier de config
file_put_contents('./data-new.json', json_encode($config), LOCK_EX) or die('Le fichier de configuration n\'a pas pu être écrit. Vérifiez les permissions.');
}

// transfert du fichier de configuration
$ini_array = parse_ini_file('./admin.ini.php');
if(file_exists('./data-new.json'))
 if(filesize('./data-new.json') > 300) {
  copy('./data.json', './data-prev.json');
  copy('./data-new.json', './data.json');
  unlink('./data-new.json');
}


// contrôle de page
if(isset($_GET['produits']))
 $page = 'products';
else if(isset($_GET['offres']))
 $page = 'offers';
else if(isset($_GET['contact']))
 $page = 'contact';
else if(isset($_GET['admin'])) {
	if($isadmin == TRUE) 
		$page = 'admin';
	else
		$page = 'login'; 
}
else
 $page = 'main';

// ================|
// panneau de login|
// ================|
if($page == 'login') {
$autherror = ''; 
	if(!empty($_POST))
		if (!empty($_POST['username']) and !empty($_POST['password']) ) {
			$username = (string)$_POST['username'];
			$password = (string)$_POST['password'];
			if(adminLogin($username, $password) === TRUE)
				header('Location: ./?admin');
			else
				$autherror = 'Erreur: identifiant ou mot de passe incorrects'; 
		}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8">
<title>Panneau d'administration</title>
<meta name="robots" content="noindex, nofollow">
<style type="text/css">
div.content {background-color:white;width:50%;margin:1em auto;padding:1em;box-shadow:2px 2px 5px #888888;}
h1 { background-color:yellow;text-align:center; }
nav ul li {display:inline-block;margin-bottom:1.2em;}
nav ul li a {text-decoration:none;padding:0.5em 1em 0.5em 1em;border:1px outset red;font-weight:bold;}
input[type=text] {width:30em; }
input[type=text].gestion {width:8em;font-family:monospace; }
img.gestion {max-width:200px;max-height:200px;border:1px solid;}
</style>
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script>tinymce.init({ 
	plugins: "hr link image autoresize charmap table textcolor code",
	selector:'textarea',
	toolbar1: "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image | preview",
	toolbar2: "undo redo | styleselect | bold italic underline strikethrough | subscript superscript | forecolor backcolor",
	tools: "inserttable"});</script>
</head>
<body>
<div class="content">
<h1>Panneau d'administration</h1>
<nav>
 <ul>
  <li><a href="./">&lArr; Retour au site</a></li>
 </ul>
</nav>
<h2>Connexion</h2>
<?php echo $autherror; ?>
<form action="./?admin" method="post">
<input type="text" name="username" value="" placeholder="Nom d'utilisateur" required><br>
<input type="password" name="password" value="" placeholder="Mot de passe" required><br>
<input type="submit">
</form>
</body>
</html>

<?php die; }
// ========================|
// panneau d'administration|
// ========================|
if($page == 'admin') { ?>
<!DOCTYPE html><html><head><meta charset="UTF-8">
<title>Panneau d'administration</title>
<style type="text/css">
div.content {background-color:white;width:50%;margin:1em auto;padding:1em;box-shadow:2px 2px 5px #888888;}
h1 { background-color:yellow;text-align:center; }
nav ul li {display:inline-block;margin-bottom:1.2em;}
nav ul li a {text-decoration:none;padding:0.5em 1em 0.5em 1em;border:1px outset red;font-weight:bold;}
input[type=text] {width:30em; }
input[type=text].gestion {width:8em;font-family:monospace; }
img.gestion {max-width:200px;max-height:200px;border:1px solid;}
</style>
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script>tinymce.init({ 
	plugins: "hr link image autoresize charmap table textcolor code",
	selector:'textarea',
	toolbar1: "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image | preview",
	toolbar2: "undo redo | styleselect | bold italic underline strikethrough | subscript superscript | forecolor backcolor",
	tools: "inserttable"});</script>
</head>
<body>
<div class="content">
<h1>Panneau d'administration</h1>
<nav>
 <ul>
  <li><a href="./">&lArr; Retour au site</a></li>
  <li><a href="./?admin">Informations générales</a></li>
  <li><a href="./?admin=style">Couleurs</a></li>
  <li><a href="./?admin=main">(page1) <?php echo $config['page']['main_title']; ?></a></li>
  <li><a href="./?admin=produits">(page2) <?php echo $config['page']['products_title']; ?></a></li>
  <li><a href="./?admin=offres">(page3) <?php echo $config['page']['offers_title']; ?></a></li>
  <li><a href="./?admin=contact">(page4) <?php echo $config['page']['contact_title']; ?></a></li>
  <li><a href="./?admin=images">Images</a></li>
  <li><a href="./?admin=maintenance">Maintenance</a></li>
 </ul>
</nav>
<?php
if($_GET['admin'] == 'style') { 
  echo '<h2>Couleurs du site</h2><form action="./?admin=style"  method="post">';
  echo '<select name="body_color" onchange="changecolor(\'body_color\', value)">';
  foreach($couleurs as $couleur) {
   if($config['style']['body_color'] == $couleur)
    echo '<option style="color:'.$couleur.';" value="'.$couleur.'" selected>'.$couleur.'</option>';
  else
    echo '<option style="color:'.$couleur.';" value="'.$couleur.'">'.$couleur.'</option>';
   }
  echo '</select>';
  echo '<label for="body_color"> texte principal: <span id="body_color" style="color:'.$config['style']['body_color'].';background-color:white;font-weight:bold;">&nbsp;'.$config['meta']['title'].'&nbsp;</span></label><br>';


  echo '<select name="body_color_link" onchange="changecolor(\'body_color_link\', value)">';foreach($couleurs as $couleur) {if($config['style']['body_color_link'] == $couleur) echo '<option style="color:'.$couleur.';" value="'.$couleur.'" selected>'.$couleur.'</option>'; else echo '<option style="color:'.$couleur.';" value="'.$couleur.'">'.$couleur.'</option>';} echo '</select>'; echo '<label for="body_color_link"> liens (texte): <span id="body_color_link" style="color:'.$config['style']['body_color_link'].';background-color:white;font-weight:bold;">&nbsp;Cliquez ici pour nous contacter !&nbsp;</span></label><br>';

  echo '<select name="body_color_link_hover" onchange="changecolor(\'body_color_link_hover\', value)">';foreach($couleurs as $couleur) {if($config['style']['body_color_link_hover'] == $couleur) echo '<option style="color:'.$couleur.';" value="'.$couleur.'" selected>'.$couleur.'</option>'; else echo '<option style="color:'.$couleur.';" value="'.$couleur.'">'.$couleur.'</option>';} echo '</select>'; echo '<label for="body_color_link_hover"> liens au passage de la souris: <span id="body_color_link_hover" style="color:'.$config['style']['body_color_link_hover'].';background-color:white;font-weight:bold;">&nbsp;Cliquez ici pour nous contacter !&nbsp;</span></label><br><br>';

  echo '<select name="body_backgroundcolor1" onchange="changebgcolor(\'body_backgroundcolor1\', value)">';foreach($couleurs as $couleur) {if($config['style']['body_backgroundcolor1'] == $couleur) echo '<option style="color:'.$couleur.';" value="'.$couleur.'" selected>'.$couleur.'</option>'; else echo '<option style="color:'.$couleur.';" value="'.$couleur.'">'.$couleur.'</option>';} echo '</select>'; echo '<label for="body_backgroundcolor1"> couleur de fond (haut): <span id="body_backgroundcolor1" style="border:1px solid;background-color:'.$config['style']['body_backgroundcolor1'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label><br>';
  echo '<select name="body_backgroundcolor2" onchange="changebgcolor(\'body_backgroundcolor2\', value)">';foreach($couleurs as $couleur) {if($config['style']['body_backgroundcolor2'] == $couleur) echo '<option style="color:'.$couleur.';" value="'.$couleur.'" selected>'.$couleur.'</option>'; else echo '<option style="color:'.$couleur.';" value="'.$couleur.'">'.$couleur.'</option>';} echo '</select>'; echo '<label for="body_backgroundcolor2"> couleur de fond (bas) : <span id="body_backgroundcolor2" style="border:1px solid;background-color:'.$config['style']['body_backgroundcolor2'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label><br><br>';
  echo '<input type="text" name="body_backgroundimage" placeholder="(insérer l\'identifiant de l\'image)" value="'.$config['style']['body_backgroundimage'].'" onclick="select()"><label for="body_backgroundimage">(option) image de fond</label><br><br>';

if(!empty($config['style']['body_backgroundimage']))
 echo '<img class="gestion" alt="" src="'.$config['style']['body_backgroundimage'].'"><br><br>Aperçu du fond en dégradé actuel (<i style="color:red">l\'image de fond ci-dessus remplace le dégradé</i>):<br>';
else echo 'Aperçu du fond en dégradé actuel:<br>';
echo '
<div id="bgapercu" style="background:linear-gradient(to bottom, '.$config['style']['body_backgroundcolor1'].', '.$config['style']['body_backgroundcolor2'].');">
&nbsp;<br>
&nbsp;<br>
&nbsp;<br>
&nbsp;<br>
&nbsp;<br>
&nbsp;<br>
&nbsp;<br>
</div>
';

 echo '<br><input type="submit">';
echo '<script type="text/javascript">
function changecolor(id, couleur){
  document.getElementById(id).style.color = couleur;
}
function changebgcolor(id, couleur){
  document.getElementById(id).style.backgroundColor = couleur;
}
</script>';
 if(!empty($_POST)) echo '<br><br><i style="color:green;">Données enregistrées avec succès. <a href="./">Retour au site</a></i>';
}
else if($_GET['admin'] == 'main') {
   echo '<h2>Page 1: "'.$config['page']['main_title'].'"</h2>';
echo '<form action="./?admin=main"  method="post">';
  echo '<label for="main_title">Titre de la page</label><br><input type="text" name="main_title" placeholder="Titre de la page" value="'.$config['page']['main_title'].'" required><br><br>';
  echo '<label for="main_text"> Contenu de la page</label><br><textarea name="main_text">'.$config['page']['main_text'].'</textarea><br>';
 echo '<input type="submit">';

 if(!empty($_POST)) echo '<br><br><i style="color:green;">Données enregistrées avec succès. <a href="./">Retour au site</a></i>';
}
else if($_GET['admin'] == 'produits') {
   echo '<h2>Page 2: "'.$config['page']['products_title'].'"</h2>';
echo '<form action="./?admin=produits"  method="post">';
  echo '<label for="products_title">Titre de la page</label><br><input type="text" name="products_title" placeholder="Titre de la page" value="'.$config['page']['products_title'].'" required><br><br>';
  echo '<label for="products_text"> Contenu de la page</label><br><textarea name="products_text">'.$config['page']['products_text'].'</textarea><br>';
 echo '<input type="submit">';

 if(!empty($_POST)) echo '<br><br><i style="color:green;">Données enregistrées avec succès. <a href="./">Retour au site</a></i>';
}
else if($_GET['admin'] == 'offres') {
   echo '<h2>Page 3: "'.$config['page']['offers_title'].'"</h2>';
echo '<form action="./?admin=offres"  method="post">';
  echo '<label for="offers_title">Titre de la page</label><br><input type="text" name="offers_title" placeholder="Titre de la page" value="'.$config['page']['offers_title'].'" required><br><br>';
  echo '<label for="offers_text"> Contenu de la page</label><br><textarea name="offers_text">'.$config['page']['offers_text'].'</textarea><br>';
 echo '<input type="submit">';

 if(!empty($_POST)) echo '<br><br><i style="color:green;">Données enregistrées avec succès. <a href="./">Retour au site</a></i>';
}
else if($_GET['admin'] == 'contact') {
   echo '<h2>Page 4: "'.$config['page']['contact_title'].'"</h2>';
echo '<form action="./?admin=contact"  method="post">';
  echo '<label for="contact_title">Titre de la page</label><br><input type="text" name="contact_title" placeholder="Titre de la page" value="'.$config['page']['contact_title'].'" required><br><br>';
  echo '<label for="contact_text"> Contenu de la page</label><br><textarea name="contact_text">'.$config['page']['contact_text'].'</textarea><br>';
 echo '<input type="submit">';

 if(!empty($_POST)) echo '<br><br><i style="color:green;">Données enregistrées avec succès. <a href="./">Retour au site</a></i>';
}
else if($_GET['admin'] == 'images') { 
  if(isset($_GET['del'])) // suppression d'image
   unlink($_GET['del'].'.png');

// formulaire d'envoi d'images
   echo '<h2>Gestionnaire d\'images</h2>';
   echo 'Espace disque utilisé: <b>'.espaceutilise().' Mo</b>  ('.espaceutilise($config['meta']['hostingspace']).' % du quota)<br>';
if(espaceutilise($config['meta']['hostingspace']) > 80) echo '<b style="color:yellow;background-color:black">AVERTISSEMENT: '.espaceutilise($config['meta']['hostingspace']).' %</b><br><b style="color:red">Il y a peu ou pas d\'espace d\'hébergement libre. Veuillez supprimer des images non utilisées, ou utiliser un hébergement avec quota supérieur. Il est déconseillé d\'ajouter de nouvelles images actuellement.</b><br>';
  echo '<br>Ajouter une image: (format JPG ou PNG, poids maximum: 1 Mo)<br>
   <form method="post" action="./?admin=images" enctype="multipart/form-data">
   <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
   <input type="file" name="image">
   <input type="submit">
   </form><br><br>';
$erreur=array();


// envoi d'images: sont autorisés JPEG et PNG, si JPEG l'image est convertie en PNG, vérification du PNG à la sortie
  if(!empty($_FILES)) {
   if ($_FILES['image']['error'] == 1) $erreur[] = "Limite d'envoi du serveur dépassée";
   if ($_FILES['image']['error'] == 2) $erreur[] = "Fichier trop lourd";
   if ($_FILES['image']['error'] == 3) $erreur[] = "Envoi interrompu";
   if ($_FILES['image']['error'] == 4) $erreur[] = "Aucun fichier spécifié";
   $nom = './'.md5(uniqid(rand(), true));
   move_uploaded_file($_FILES['image']['tmp_name'],$nom);
   if($_FILES['image']['type'] !== 'image/png') { imagepng(imagecreatefromstring(file_get_contents($nom)), 'output.png'); unlink($nom); $nom = 'output.png'; }
   $nouveaunom = hash_file('crc32', $nom).'.png';
   rename($nom, $nouveaunom);
   if(imagecreatefrompng($nouveaunom)) { } else { unlink($nouveaunom); if(empty($erreur)) { $erreur[] = "Format de fichier invalide";} }
   if(filesize($nouveaunom) > 1048576) { $size = poidsfichier($nouveaunom);  unlink($nouveaunom); $erreur[] = "Fichier trop lourd après conversion: $size Mo"; }
  }

  if(!empty($erreur))
   { echo '<b style="color:red">Une erreur est survenue:</b><br>'; foreach($erreur as $message) echo $message.'<br>';echo '<br><br>';  }
  if(!empty($_FILES) and empty($erreur)) echo '<b style="color:green">Envoi réussi: '.$nouveaunom.'</b><br><br>';

// listing des images 
  echo 'Pour insérer une image dans une page, cliquez sur son identifiant (par ex: "c24e5f6b.png") et copiez-collez l\'identifiant dans le panneau d\'édition.<br>';
  foreach (glob("*.png") as $filename) {
    $id = substr($filename, 0, 8);
    echo '<a href="'.$filename.'"><img class="gestion" alt="" src="'.$filename.'"></a> <a style="color:red;text-decoration:none;" title="Supprimer cette image" href="javascript:if(confirm(\'Supprimer cette image ?\')) document.location.href=\'./?admin=images&del='.$id.'\';">X</a><br><input title="Cliquer pour sélectionner cette image" class="gestion" type="text" value="'.hash_file('crc32', $filename).'.png" onclick="select()"> <b>'.poidsfichier($filename).' Mo</b><br><br>';
  }
} 

// page de maintenance
else if($_GET['admin'] == 'maintenance') {
   echo '<h2>Maintenance du site</h2>';
   $newversion='';$checknewversion=file_get_contents('https://raw.github.com/e-leroy/MiniVit/master/version');
   if($checknewversion > $version) $newversion=' ~ <a href="https://github.com/e-leroy/MiniVit">une mise à jour est disponible: '.$checknewversion.' !</a>';
   echo '<pre>Version du gestionnaire: '.$version.$newversion'<br>';
   echo 'Dernière modification de la configuration: '.date('d/m/Y H:i (e)', filemtime('./data.json')).'<br>';
   echo 'Espace disque utilisé: <b>'.espaceutilise().' Mo</b>  ('.espaceutilise($config['meta']['hostingspace']).' % du quota)<br>';
  echo '</pre><br><br><b><a href="./data.json">Sauvegarder la configuration</a></b>  (clic droit > "Enregistrer la cible du lien sous...")';
  echo '<br><br><b>Restaurer un fichier de configuration</b><br>
   <form method="post" action="./?admin=maintenance" enctype="multipart/form-data">
   <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
   <input type="file" name="config">
   <input type="submit">
   </form><br><br>';
// restauration de configuration
  if(!empty($_FILES)) {
   if ($_FILES['image']['error'] == 1) $erreur[] = "Limite d'envoi du serveur dépassée";
   if ($_FILES['image']['error'] == 2) $erreur[] = "Fichier trop lourd";
   if ($_FILES['image']['error'] == 3) $erreur[] = "Envoi interrompu";
   if ($_FILES['image']['error'] == 4) $erreur[] = "Aucun fichier spécifié";
   $nom = './data-import.json';
   move_uploaded_file($_FILES['config']['tmp_name'],$nom);
   if(empty($erreur)) {
    copy('data.json', 'data-prev.json');
    copy('data-import.json', 'data.json');
    unlink('data-import.json');
    echo '<b style="color:green">Fichier restauré.</b> <a href="./?admin=maintenance">Cliquer pour recharger la page</a>';
    }
   else { unlink('data-import.json'); echo '<b style="color:red">Une erreur est survenue:</b><br>'; foreach($erreur as $message) echo $message.'<br>'; }

  }
}

// informations générales
else {
 echo '<h2>Informations générales</h2><form action="./?admin"  method="post">';
  echo '<input type="text" name="title" placeholder="Nom de la société" value="'.$config['meta']['title'].'" required><label for="title">Nom de la société</label><br>';
  echo '<input type="text" name="description" placeholder="Description de la société" value="'.$config['meta']['description'].'" required><label for="description">Description de la société</label><br>';
  echo '<input type="text" name="license" placeholder="Licence copyright" value="'.$config['meta']['license'].'" required><label for="license">Licence copyright</label><br>';
  echo '<input type="text" name="hostingspace" placeholder="Espace web" value="'.$config['meta']['hostingspace'].'" required><label for="hostingspace">Quota d\'espace d\'hébergement (en Mo)</label><br>';
  echo '<input type="text" name="tracking" placeholder="Code de tracking" value="'.htmlentities($config['meta']['tracking'], ENT_QUOTES).'"><label for="tracking">(option) code de tracking</label><br>';
 echo '<input type="submit">';
 if(!empty($_POST)) echo '<br><br><i style="color:green;">Données enregistrées avec succès. <a href="./">Retour au site</a></i>';
}

?>
</div>
</body></html>

<?php } else { /* affichage des pages publiques  */ ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $config['meta']['title'].' | '.$config['page'][$page.'_title']; ?></title>
<meta name="description" content="<?php echo $config['meta']['description']; ?>">
<meta name="viewport" content="initial-scale=1.0, user-scalable=yes">
<style type="text/css">
body {color:<?php echo $config['style']['body_color']; ?>;font-family:sans-serif;text-align:justify;background:linear-gradient(to bottom, <?php echo $config['style']['body_backgroundcolor1']; ?>, <?php echo $config['style']['body_backgroundcolor2']; ?>);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $config['style']['body_backgroundcolor1']; ?>', endColorstr='<?php echo $config['style']['body_backgroundcolor2']; ?>');<?php if(!empty($config['style']['body_backgroundimage']) and file_exists($config['style']['body_backgroundimage'])) echo 'background-image: url("'.$config['style']['body_backgroundimage'].'");background-repeat: no-repeat;background-position:top center;'; ?> background-attachment:fixed;}
a {color:<?php echo $config['style']['body_color_link']; ?>;font-weight:bold;}
a:hover {color:<?php echo $config['style']['body_color_link_hover']; ?>;}
div.content {background-color:white;width:50%;min-width:35em;margin:1em auto;padding:1em;box-shadow:2px 2px 5px #888888;border-radius:15px;} nav ul li a {text-decoration:none;padding:0.5em 1em 0.5em 1em;border:1px outset #bbb;border-radius:5px;}
footer {text-align:center;font-size:small;}
header h1,h2 {text-align:center;}
nav ul li {display:inline-block;margin-bottom:1.2em;}
article h1 {margin-left:1em;}
article img {border:1px solid;}
</style>
</head>
<body>
<div class="content">
<header>
<h1><?php echo $config['meta']['title']; ?></h1>
<h2><?php echo $config['meta']['description']; ?></h2>
<nav>
 <ul>
  <li><a href="./"><?php echo $config['page']['main_title']; ?></a></li>
  <li><a href="./?produits"><?php echo $config['page']['products_title']; ?></a></li>
  <li><a href="./?offres"><?php echo $config['page']['offers_title']; ?></a></li>
  <li><a href="./?contact"><?php echo $config['page']['contact_title']; ?></a></li>
 </ul>
</nav>
</header>

<article>
<h1><?php echo $config['page'][$page.'_title']; ?></h1>
<?php
// envoi email
if($page == 'contact') {
echo '<b>Formulaire de contact:</b><br><br>';
$nom='';$email='';$texte='';
if(isset($_POST) and !empty($_POST)) {
 $nom = htmlentities($_POST['nom']);
 $email = htmlentities($_POST['addremail']);

 $texte = $_POST['texte'];
 if(check_antibot($_POST['number'], $_POST['antibot'])) {
 	$headers  = 'MIME-Version: 1.0' . "\r\n";
 	$headers .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
 	$headers .= "From: $email\r\n";
   mail($ini_array['email'], '[formulaire de contact site web]', '** adresse IP: '.$_SERVER['REMOTE_ADDR']."\n\n$texte", $headers);
   echo '<br><b style="color:darkgreen">Message transmis avec succès.</b><br>Nous vous répondrons dans les meilleurs délais.<br>';
   }
 else echo '<br><b>Une erreur a été détectée.<br>Merci de vérifier votre saisie:</b><br>';
 }

$antibot = generate_antibot();
echo '<form action="./?contact" method="post">
<input type="text" name="nom" value="'.$nom.'" size="50%" placeholder="Votre nom" required><br>
<input type="email" name="addremail" value="'.$email.'" size="50%" placeholder="Votre adresse mail" required><br>
<textarea name="texte" placeholder="Votre message" rows="10" cols="50%" required>'.$texte.'</textarea><br>
antispam:<br>
           <input placeholder="Écrivez « '. $antibot .' » en chiffre" type="text" name="number" size="50%" required><br>
           <input type="hidden" name="antibot" value="'. $antibot .'" /><br>
<input type="submit" value="Envoyer"><br><br>';
}
echo $config['page'][$page.'_text']; ?>
</article>

<footer><?php echo '&copy;'.date('Y').' '.$config['meta']['title']; ?> - <?php echo $config['meta']['license'].$config['meta']['tracking']; ?><br><span style="font-size:x-small">site propulsé par <a href="https://github.com/e-leroy/MiniVit">MiniVit</a> ~ <a href="./?admin">admin</a></span></footer>
</div>

</body>
</html>
<?php
}
?>
