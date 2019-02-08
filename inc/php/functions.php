<?php
//funciones core
	session_start();
	//configuración local
		function mroNumber(){
			/* Software by Facundo Subiabre
			 * https://github.com/facuonline/mercurio
			 * 2018 / 2019
			 * 
			 */
			echo "0.9.33";
		}
		//fingerprint
		function mroName(){
			global $conn;
			$query = $conn->query("SELECT name FROM install");
			if (empty($query->fetch()['name'])) {
				echo 'Mercurio';
			} else {
				$query = $conn->query("SELECT name FROM install");
				echo $query->fetch()['name'];
			}
		}
		function mroLogo(){
			global $conn;
			$query = $conn->query("SELECT logo FROM install");
			if (empty($query->fetch()['logo'])) {
				echo "/inc/img/mercurio.png";
			} else {
				$query = $conn->query("SELECT logo FROM install");
				echo $query->fetch()['logo'];
			}
		}
		function mroHeaderBg(){
			global $conn;
			$query = $conn->query("SELECT headerbg FROM install");
			if (empty($query->fetch()['headerbg'])) {
				echo '333333';
			} else {
				$query = $conn->query("SELECT headerbg FROM install");
				echo $query->fetch()['headerbg'];
			}
		}
		function mroColor(){
			global $conn;
			$query = $conn->query("SELECT action FROM install");
			if (empty($query->fetch()['action'])) {
				echo '095fa9';
			} else {
				$query = $conn->query("SELECT action FROM install");
				echo $query->fetch()['action'];
			}
		}
		function mroWebmail(){
			global $conn;
			$query = $conn->query("SELECT webmail FROM install");
			if (empty($query->fetch()['webmail'])) {
				return false;
			} else {
				$query = $conn->query("SELECT webmail FROM install");
				return $query->fetch()['webmail'];
			}
		}
		function mroRules(){
			global $conn;
			$query = $conn->query("SELECT rules FROM install");
			if (empty($query->fetch()['rules'])) {
				return false;
			} else {
				$query = $conn->query("SELECT rules FROM install");
				return $query->fetch()['rules'];
			}
		}
		function mroMaxFileSize(){
			global $conn;
			$query = $conn->query("SELECT maxfilesize FROM install");
			echo $query->fetch()['maxfilesize'];
		}
		function mroMxFlSz(){
			global $conn;
			$query = $conn->query("SELECT maxfilesize FROM install");
			return $query->fetch()['maxfilesize']*1048576;
		}
		function mroStats(){
			global $conn;
			$posts = $conn->query("SELECT * FROM posts");
			$users = $conn->query("SELECT * FROM users");
			$sections = $conn->query("SELECT * FROM sections");
			$comments = $conn->query("SELECT * FROM comments");
			$stars = $conn->query("SELECT * FROM stars");
			return $posts->rowCount()." entradas de ".$users->rowCount()." usuarios en ".$sections->rowCount()." secciones con ".$comments->rowCount()." comentarios y ".$stars->rowCount()." estrellas.";
		}
		//instalación
		$query = $conn->query("SELECT fingerprint FROM install");
		if (empty($query->fetch()['fingerprint'])) {
			include 'install.php';
		}
	//herramientas
		function mroExcerpt($x, $length){
			if(strlen($x)<=$length){
				echo nl2br(strip_tags($x));
			} else {
				$y=substr($x,0,$length) . '...';
				echo nl2br(strip_tags($y));
			}
		}
		function mroSlug($string){
			$uniq = preg_replace('/[\W\s\/]+/', '_', substr(base64_encode(uniqid()), 16));
			$spaces = preg_replace('/\s+/', '-', $string);
			$slug = trim(strtolower(preg_replace('/[^0-9A-Za-z-]/', '', $spaces)))."-".substr(md5($_SESSION['username']), 0, 7).$uniq;
			return $slug;
		}
		function mroEncryption($string){
			$key = openssl_random_pseudo_bytes(32);
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
			$encrypted = openssl_encrypt($string, 'aes-256-cbc', $key, 0, $iv);
			return base64_encode($encrypted.'::'.$key.'::'.$iv);
		}
		function mroDecryption($string){
			list($encrypted, $key, $iv) = explode('::', base64_decode($string), 3);
			return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
		}
		function mroResize($file, $width, $height) {
		    list($w, $h) = getimagesize($file);
		    $ratio = max($width/$w, $height/$h);
		    $h = ceil($height / $ratio);
		    $x = ($w - $width / $ratio) / 2;
		    $w = ceil($width / $ratio);
		    $imgString = file_get_contents($file);
		    $image = imagecreatefromstring($imgString);
		    $tmp = imagecreatetruecolor($width, $height);
		    imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
		    imagejpeg($tmp, $file, 85);
		    return $file;
		    imagedestroy($image);
		    imagedestroy($tmp);
		}
		function folder_size($path){
		    $bytestotal = 0;
		    $path = realpath($path);
		    if($path!==false && $path!='' && file_exists($path)){
		        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
		            $bytestotal += $object->getSize();
		        }
		    }
		    return $bytestotal;
		}
		function cover($file) {
			list($width, $height) = getimagesize($file);
			if ($width > $height) {	
				$ratio = $width / $height;
			} elseif ($width == $height) {
				$ratio = $width / $height;
			} else {
				$ratio = $height / $width;
			}
			return 780 / $ratio;
		}
		function curPageURL() {
			$pageURL = 'http';
			if (isset($_SERVER["HTTPS"])) {$pageURL .= "s";}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			return $pageURL;
		}
		function read_word($input_file){	
			$strip_texts = ''; 
			$texts = ''; 	
			if(!$input_file || !file_exists($input_file)) return false;
			$zip = zip_open($input_file);

			if (!$zip || is_numeric($zip)) return false;

			while ($zip_entry = zip_read($zip)) {
				if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
				if (zip_entry_name($zip_entry) != "word/document.xml") continue;
				$texts .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
				zip_entry_close($zip_entry);
			}

			zip_close($zip);

			$texts = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $texts);
			$texts = str_replace('</w:r></w:p>', "\r\n", $texts);
			$strip_texts = nl2br(strip_tags($texts));

			return $strip_texts;
		}
	function errors(){
		global $errors;
		if (isset($errors)) {
			foreach($errors as $error) { echo "<input type='checkbox' id='hide' /><div id='alert' class='error'>$error <label for='hide'>Ok</label></div>"; }
		}
	}
	$errors = array();
	function successes(){
		global $successes;
		if (isset($successes)) {
			foreach($successes as $success) { echo "<input type='checkbox' id='hide' /><div id='alert' class='success'>$success <label for='hide'>Ok</label></div>"; }
		}
	}
	$successes = array();
//funciones de usuario
	function uUser(){
		global $conn;
		if (isset($_SESSION['username'])) {
			return true;
		} else {
			return false;
		}
	}
	function uAdmin(){
		global $conn;
		if (uUser()) {
			$query = $conn->prepare("SELECT role FROM users WHERE username = ?");
			$query->execute([$_SESSION['username']]);
			if ($query->fetch()['role'] == '1') {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	function uBlocked(){
		global $conn;
		$query = $conn->prepare("SELECT access FROM users WHERE id = ?");
		$query->execute([$_SESSION['user_id']]);
		if ($query->fetch()['access'] != '1') {
			return "<section class='content'>
				<h2>Has sido bloqueado por un administrador.</h2>
				<img class='mainimg' src='".url()."/inc/img/phone_lock.png'>
				<p>No puedes publicar nuevas entradas ni comentarios ni editar las entradas que hayas publicado. Por favor, tómate un tiempo para descansar.</p>
				<p>Si crees que ya estás listo para volver a participar, puedes contactar con un <a href='".url()."/search.php?for=:admins'>administrador</a> para que te desbloquee. Deberás demostrar que tu bloqueo debe ser levantado.</p>
				</section>";
		} else {
			return false;
		}
	}
	function uNotifications(){
		global $conn;
		if (isset($_SESSION['username'])) {
			//notificaciones de la sesión actual
			$query = $conn->prepare("SELECT * FROM notifications WHERE user = ? AND unread = '1'");
			$query->execute([$_SESSION['username']]);
			while ($result = $query->fetch()) { ?>
				<li>
					<form method="POST" action="">
						<?php //borrar notificaciones una vez leídas
							if (isset($_POST['read'.$result['id']])) {
							$sql = $conn->prepare("DELETE FROM notifications WHERE id = ?");
							$sql->execute([$result['id']]);
							header('location: '.$result['target']);
						} ?>
						<button type="submit" name="read<?php echo $result['id']; ?>"><?php echo $result['content']; ?></button>
					</form>
				</li>
			<?php }
		}
	}
	function uDarkMode(){
		//modo oscuro en la sesión actual
		global $conn;
		$query = $conn->prepare("SELECT darkmode FROM users WHERE username = ?");
		$query->execute([$_SESSION['username']]);
		if ($query->fetch()['darkmode'] != '0') {
			return true;
		} else {
			return false;
		}
	}
	//login
	if (isset($_POST['login'])) {
		if (!empty($_POST['username'])) {
			$username = $_POST['username'];
		} else {
			$errors[] = "No puedes acceder sin tu nombre de usuario.";
		}
		if (!empty($_POST['password'])) {
			$password = $_POST['password'];
		} else {
			$errors[] = "No puedes acceder sin tu contraseña.";
		}
		if (empty($errors)) {
			$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
			$query->execute([$username]);
			//comprobar si existe el usuario
			if ($query->rowCount() != 0) {
				$hash = $query->fetch()['password'];
				//comprobar contraseña
				if (password_verify($password, $hash)) {
					session_start();
					$_SESSION['username'] = $username;
					$sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
					while ($user = $sql->fetch()) {
						$_SESSION['user_id'] = $user['id'];
						$_SESSION['since'] = $user['since'];
					}
					header('location:'.curPageURL());
				} else {
					$errors[] = "Contraseña incorrecta.";
				}
			} else {
				$errors[] = "No existe ese usuario.";
			}
		}
	}
	//registro
	if (isset($_POST['register'])) {
		//nombre de usuario
		if (!empty($_POST['username'])) {
			if (strlen($_POST['username']) < 3) {
				$errors[] = "Tu nombre de usuario debe tener al menos 3 caracteres de largo.";
			} elseif (strlen($_POST['username']) > 18) {
				$errors[] = "Tu nombre de usuario debe tener menos de 16 caracteres de largo.";
			} elseif (preg_match("/[^a-zA-Z0-9_]/", $_POST['username'])) {
				$errors[] = "Tu nombre de usuario solo puede contener letras Aa-Zz, números y _.";
			} else {
				//comprobar si ya está registrado
				$query = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
				$query->execute([trim($_POST['username'])]);
				if ($query->fetchColumn() == 0) {
					$username = trim($_POST['username']);
				} else {
					$errors[] = "Ya existe un usuario con ese nombre.";
				}
			}
		} else {
			$errors[] = "No puedes registrarte sin nombre de usuario.";
		}
		//correo
		if (mroWebmail()) {
			if (!empty($_POST['email'])) {
				//comprobar si ya está registrado
				$query = $conn->prepare("SELECT email FROM users WHERE email = ?");
				$query->execute([trim($_POST['email'])]);
				if ($query->fetchColumn() == 0) {
					$email = trim($_POST['email']);
				} else {
					$errors[] = "Ya existe un usuario con ese correo.";
				}
			} else {
				$errors[] = "Debes indicar una dirección de correo.";
			}
		} else {
			$email = false;
		}
		//contraseña
		if (!empty($_POST['password'])) {
			if (strlen($_POST['password']) >= 8) {
				$plain = $_POST['password'];
				$password = password_hash($plain, PASSWORD_DEFAULT);
			} else {
				$errors[] = "Tu contraseña debe tener al menos 8 caracteres de largo.";
			}
		} else {
			$errors[] = "No puedes registrate sin contraseña.";
		}
		//normas de la comunidad
		if (mroRules()) {
			if (empty($_POST['rules'])) {
				$errors[] = "Debes aceptar las normas de la comunidad.";
			}
		}
		if (empty($errors)) {
			$since = time();
			$sql = $conn->prepare("INSERT INTO users (username, name, email, password, since, access) VALUES (?, ?, ?, ?, ?, ?)");
			$sql->execute([
					$username, 
					$username, 
					$email, 
					$password, 
					$since,
					'1'
				]);
			session_start();
            $_SESSION['username'] = $username;
            $sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
			while ($user = $sql->fetch()) {
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['since'] = $user['since'];
			}
			header('location: '.url().'/u/?welcome');
			//el primer usuario es admin por defecto
			$sql = $conn->query("SELECT COUNT(*) FROM users");
			if ($sql->fetchColumn() == 1) {
				$sql = $conn->query("UPDATE users SET role = '1' WHERE id = '1'");
			}
		}
	}
	//actualizar cuenta
		//perfil
		if (isset($_POST['profile_update'])) {
			//nombre público
			if (!empty($_POST['name'])) {
				$name = $_POST['name'];
			} else {
				$errors[] = "No puedes usar un nombre en blanco.";
			}
			//avatar
			if ($_FILES['avatar']['size'] != 0 && $_FILES['avatar']['size'] > mroMxFlSz()) {
				$errors[] = "Intenta subir una imagen que pese menos.";
			}
			$bio = $_POST['bio'];
			if ($_FILES['avatar']['size'] != 0) {
				$newfilename = $_SESSION['username'].'.jpg';
				$resize = mroResize($_FILES['avatar']['tmp_name'], 250, 250);
				if(move_uploaded_file($resize, 'imgs/'.$newfilename)) {
					$avatar = 'imgs/'.$newfilename;
				} else {
					$errors[] = "Ha ocurrido un error inesperado subiendo tu avatar.";
				}
			} else {
				$avatar = 'imgs/default_user.png';
			}
			if (empty($errors)) {
				$sql = $conn->prepare("UPDATE users SET name = ?, bio = ?, avatar = ? WHERE id = ?");
				$sql->execute([
						$name, 
						$bio, 
						$avatar, 
						$_SESSION['user_id']
					]);
				if ((time()-$_SESSION['since']) < 86400) {
					header('location: '.url());
				}
				$successes[] = "Se ha actualizado tu perfil.";
			}
		}
		//modo oscuro
		if (isset($_POST['darkmodeon'])) {
			$sql = $conn->prepare("UPDATE users SET darkmode = '1' WHERE username = ?");
			$sql->execute([$_SESSION['username']]);
			$successes[] = "Se ha habilitado el modo oscuro.";
		}
		if (isset($_POST['darkmodeoff'])) {
			$sql = $conn->prepare("UPDATE users SET darkmode = '0' WHERE username = ?");
			$sql->execute([$_SESSION['username']]);
			$successes[] = "Se ha deshabilitado el modo oscuro.";
		}
		//email
		if (isset($_POST['email_update'])) {
			if (!empty($_POST['email'])) {
				$email = trim($_POST['email']);
			} else {
				$errors[] = "Si no indicas una dirección de correo no podrás recuperar tu contraseña.";
			}
			if (empty($errors)) {
				$sql = $conn->prepare("UPDATE users SET email = ? WHERE username = ?");
				$sql->execute([
						$email, 
						$_SESSION['username']
					]);
				$successes[] = "Se ha actualizado tu correo.";
			}
		}
		//contraseña
		if (isset($_POST['password_update'])) {
			if (!empty($_POST['password'])) {
				if (strlen($_POST['password']) >= 8) {
					$plain = $_POST['password'];
					$password = password_hash($plain, PASSWORD_DEFAULT);
				} else {
					$errors[] = "Tu contraseña debe tener al menos ocho caracteres de largo.";
				}
			} else {
				$errors[] = "Tu contraseña no puede estar vacía.";
			}
			if (empty($errors)) {
				$sql = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
				$sql->execute([
						$email, 
						$_SESSION['username']
					]);
				$successes[] = "Se ha actualizado tu contraseña.";
			}
		}
		//recuperar contraseña
		if (isset($_POST['request_password_token'])) {
			if (!empty($_POST['username'])) {
				$username = $_POST['username'];
				//comprobar usuario
				$sql = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
				$sql->execute([$username]);
				if ($sql->fetchColumn() == 0) {
					$errors[] = "No existe ese nombre de usuario.";
				}
			} else {
				$errors[] = "Debes introducir tu nombre de usuario.";
			}
			if (!empty($_POST['email'])) {
				$email = $_POST['email'];
				//comprobar email
				$sql = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
				$sql->execute([$email]);
				if ($sql->fetchColumn() == 0) {
					$errors[] = "No hay ningún usuario con esa dirección de correo.";
				}
			} else {
				$errors[] = "Debes indicar tu dirección de correo.";
			}
			if (empty($errors)) {
				$check = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND username = ?");
				$check->execute([$email, $username]);
				if ($check->fetchColumn() == 0) {
					$errors[] = "El usuario introducido no coincide con ese correo.";
				} else {
					$sql->execute([time(), $_POST['username']]);
					$site = url();
					$token = md5($username).uniqid();
					$sql = $conn->query("UPDATE users SET access = '0', token = '$token' WHERE username = '$username'");
					$message = "Hola, $username.\n Has recibido este mensaje porque se ha solicitado reestablecer tu contraseña en $site\n Debido a esto tu cuenta se ha bloqueado como medida preventiva. Si no has sido tú quien ha solicitado este correo, tu cuenta puede estar comprometida.\n Usa el siguiente enlace para recuperar tu cuenta y reestablecer tu contraseña.\n $site/u/index.php?password&passwordtoken=$token";
					$cabeceras = 'From: '.mroWebmail()."\r\n".
	    			'X-Mailer: PHP/'.phpversion();
					if (mail($email, 'Reestablecer contraseña', $message)) {
						header('location:'.curPageURL().'&sent');
					} else {
						$errors[] = "No se pudo enviar el correo.";
					}
				}
			}
		}
	//borrar cuenta
	if (isset($_POST['delete_account'])) {
		if (empty($_POST['understand'])) {
			$errors[] = "Debes confirmar que entiendes las consecuencias de borrar tu cuenta.";
		}
		if (empty($errors)) {
			$username = $_SESSION['username'];
			$sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
			$path = $sql->fetch()['avatar'];
			if (!empty($path)) {
				unlink($path);
			}
			$user = $conn->query("DELETE FROM users WHERE username = '$username'");
			$posts = $conn->query("DELETE FROM posts WHERE author = '$username'");
			$subposts = $conn->query("DELETE FROM subposts WHERE author = '$username'");
			$drafts = $conn->query("DELETE FROM drafts WHERE author = '$username'");
			$comments = $conn->query("DELETE FROM comments WHERE author = '$username'");
		}
	}
//funciones sociales y de feed
	//mensajes
		//enviar mensaje
		if (isset($_POST['message'])) {
			if (empty($_POST['message_body'])) {
				$errors[] = "No puedes enviar un mensaje vacío.";
			}
			if (isset($_GET['u'])) {
				$receiver = $_GET['u'];
			} else {
				$receiver = $_GET['with'];
			}
			if (empty($errors)) {
				$stamp = date('d/m/Y h:i');
				$plain = $_POST['message_body'];
				$mentions = preg_replace('/@(\w+)/', '<a class="arroba" href="../?u=$1">@$1</a>', $plain);
				$message = mroEncryption($mentions);
				//insertar en la base de datos
					$sql = $conn->prepare("INSERT INTO messages (sender, receiver, body, stamp) VALUES (?, ?, ?, ?)");
					$sql->execute([
							$_SESSION['username'], 
							$receiver, 
							$message, 
							$stamp
						]);
					$successes[] = "Tu mensaje ha sido enviado.";
				//notificar
					$sender = $_SESSION['username'];
					$queryID = $conn->prepare("SELECT * FROM messages WHERE sender = ? ORDER BY id DESC LIMIT 1");
					$queryID->execute([$_SESSION['username']]);
					$target = url()."/u/m/?with=".$_SESSION['username']."#message".$queryID->fetch()['id'];
					$notificate = $conn->query("INSERT INTO notifications (user, target, content) VALUES ('$receiver', '$target', 'Tienes un nuevo mensaje de @$sender.')");
			}
		}
	//comentarios
		//publicar comentario
		if (isset($_POST['comment'])) {
			if (!empty($_POST['comment_body'])) {
				$comment_body = $_POST['comment_body'];
				$pattern = array('/#([0-9]+)/', '/@(\w+)/');
				$replace = array('<a class="hash" href="#comment$1">#$1</a>', '<a class="arroba" href="../u/?u=$1">@$1</a>');
				$body = preg_replace($pattern, $replace, $comment_body);
			} else {
				$errors[] = "No puedes publicar un comentario vacío.";
			}
			$stamp = time();
			$post = $_POST['post_id'];
			$sql = $conn->prepare("INSERT INTO comments (author, name, body, stamp, post) VALUES (?, ?, ?, ?, ?)");
			if ($sql->execute([
					$_SESSION['user_id'], 
					$_SESSION['username'],
					$body, 
					$stamp, 
					$post
				])) {
				$successes[] = "Tu comentario se ha publicado.";
				//popularidad (+5)
					$getpop = $conn->query("SELECT pop FROM posts WHERE id = '$post'");
					$i = $getpop->fetch()['pop'] + 5;
					$conn->query("UPDATE posts SET pop = '$i' WHERE id = '$post'");
				//notificar
					$post_author = $_POST['post_author'];
					$getslug = $conn->query("SELECT slug FROM posts WHERE id = '$post'");
					$slug = $getslug->fetch()['slug'];
					$getcomment = $conn->prepare("SELECT * FROM comments WHERE post = ? AND author = ? ORDER BY id DESC LIMIT 1");
					$getcomment->execute([
						$post, 
						$_SESSION['user_id']
					]);
					$commentid = $getcomment->fetch()['id'];
					$target = url()."/post/?read=$slug#comment".$commentid;
					if ($post_author != $_SESSION['username']) {
						$sql = $conn->query("INSERT INTO notifications (user, target, content) VALUES ('$post_author', '$target', 'Tienes un nuevo comentario.')");
					}
				//menciones
				if (preg_match('/@(\w+)/', $body, $pings)) {
					$receiver = preg_replace('/@(\w+)/', '$1', $pings[0]);
					$sql = $conn->query("INSERT INTO notifications (user, target, content) VALUES ('$receiver', '$target', 'Alguien te ha mencionado en un comentario.')");
				}
			} else {
				$errors[] = "No se ha podido publicar tu comentario debido a un error inesperado.";
			}
		}
		//borrar comentario
		if (isset($_POST['delete_comment'])) {
			$sql = $conn->prepare("DELETE FROM comments WHERE id = ?");
			$sql->execute([
				$_POST['id'],
			]);
			$successes[] = "El comentario ha sido borrado.";
			//popularidad (-4)
				$getpop = $conn->query("SELECT pop FROM posts WHERE id = '$_POST[id]'");
				$i = $getpop->fetch()['pop'] - 4;
				$conn->query("UPDATE posts SET pop = '$i' WHERE id = '$_POST[id]'");
		}
	//corazones y badges
	function uBadges(){
		global $conn;
		$query = $conn->prepare("SELECT * FROM badges WHERE receiver = ?");
		$query->execute([$_GET['u']]);
		while ($badge = $query->fetch()) {
			echo "<i title='$badge[attr]' class='im im-heart' style='color: #$badge[color]'></i>";
		}
		$admin = $conn->prepare("SELECT role FROM users WHERE username = ?");
		$admin->execute([$_GET['u']]);
		if ($admin->fetch()['role'] == '1') {
			echo "<i title='Este usuario es un administrador' class='im im-id-card'></i>";
		}
	}
	//feed
		//añadir al feed
		if (isset($_GET['addfeed'])) {
			if (uUser()) {
				$feed_from = $_SESSION['user_id'];
				$to = $conn->query("SELECT id FROM users WHERE username = '$_GET[u]'");
				$feed_to = $to->fetch()['id'];
				$sql = $conn->query("INSERT INTO feed (feed_from, feed_to) VALUES ('$feed_from', '$feed_to')");
				$successes[] = "Se ha añadido a tu feed.";
			} else {
				$errors[] = "Debes estar registrado para añadir a alguien a tu feed.";
			}
		}
		//quitar del feed
		if (isset($_GET['quitfeed'])) {
			if (uUser()) {
				$feed_from = $_SESSION['user_id'];
				$to = $conn->query("SELECT id FROM users WHERE username = '$_GET[u]'");
				$feed_to = $to->fetch()['id'];
				$sql = $conn->query("DELETE FROM feed WHERE feed_from = '$feed_from' AND feed_to = '$feed_to'");
				$successes[] = "Se ha eliminado de tu feed.";
			} else {
				$errors[] = "Debes estar registrado para usar el feed.";
			}
		}
	//estrellas
		//añadir estrella
		if (isset($_GET['addstar'])) {
			if (uUser()) {
				$star_from = $_SESSION['username'];
				$slug = $_GET['read'];
				$sql = $conn->query("SELECT id FROM posts WHERE slug = '$slug'");
				$post = $sql->fetch()['id'];
				$check = $conn->query("SELECT id FROM stars WHERE star_from = '$star_from' AND post = '$post'");
				if ($check->rowCount() == 0) {
					$sql = $conn->query("INSERT INTO stars (star_from, post) VALUES ('$star_from', '$post')");
					$successes[] = "Se ha añadido tu estrella.";
					//popularidad (+3)
						$getpop = $conn->query("SELECT pop FROM posts WHERE id = '$post'");
						$i = $getpop->fetch()['pop'] + 3;
						$conn->query("UPDATE posts SET pop = '$i' WHERE id = '$post'");
				} else {
					$errors[] = "Ya le has dado una estrella a esta historia.";
				}
			} else {
				$errors[] = "Debes estar registrado para otorgar estrellas.";
			}
		}
		//retirar estrella
		if (isset($_GET['quitstar'])) {
			if (uUser()) {
				$star_from = $_SESSION['username'];
				$slug = $_GET['read'];
				$sql = $conn->query("SELECT id FROM posts WHERE slug = '$slug'");
				$post = $sql->fetch()['id'];
				$sql = $conn->query("DELETE FROM stars WHERE star_from = '$star_from' AND post = '$post'");
				$successes[] = "Se ha retirado tu estrella.";
				//popularidad (-3)
					$getpop = $conn->query("SELECT pop FROM posts WHERE id = '$post'");
					$i = $getpop->fetch()['pop'] - 3;
					$conn->query("UPDATE posts SET pop = '$i' WHERE id = '$post'");
			} else {
				$errors[] = "Debes estar registrado para otorgar y retirar estrellas.";
			}
		}
//funciones de publicación
	//historias
		//publicar nueva historia
		if (isset($_POST['post_publish'])) {
			if (!empty($_POST['title'])) {
				$title = $_POST['title'];
			} else {
				$errors[] = "No puedes publicar sin título.";
			}
			if (!empty($_POST['text_body'])) {
				require_once '../inc/purifier/library/HTMLPurifier.auto.php';
    			$purifier = new HTMLPurifier();
				$body = $purifier->purify($_POST['text_body']);
			} else {
				$errors[] = "No puedes publicar una historia vacía.";
			}
			if (!empty($_POST['section'])) {
				$section = $_POST['section'];
			} else {
				$errors[] = "No puedes publicar sin sección.";
			}
			//subir portada
			if (!empty($_FILES['cover']['name'])) {
				if ($_FILES['cover']['size'] < mroMxFlSz()) {
					$temp = explode(".", $_FILES["cover"]["name"]);
					$newfilename = substr(md5($title), 0, 7).substr(md5($_SESSION['username']), 0, 7).'.'.end($temp);
					$newheight = cover($_FILES["cover"]["tmp_name"]);
					$resize = mroResize($_FILES["cover"]["tmp_name"], 780, $newheight);
					if (move_uploaded_file($resize, "cover/".$newfilename)) {
						$cover = 'cover/'.$newfilename;
					} else {
						$errors[] = "Lo sentimos, no se ha podido subir la imagen.";
					}
				} else {
					$errors[] = "Intenta subir una imagen que pese menos.";
				}
			} else {
				$cover = false;
			}
			if (empty($errors)) {
				$slug = mroSlug($title);
				$sql = $conn->prepare("INSERT INTO posts (title, body, author, stamp, cover, section, slug, updated, updated_m) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->execute([
					$title,
					$body,
					$_SESSION['user_id'],
					time(),
					$cover,
					$section,
					$slug,
					time(),
					"posted"
				]);
				//eliminar borrador automático
				$draft = $conn->prepare("DELETE FROM drafts WHERE id = ?");
				$draft->execute([$_POST['draft_id']]);
				$successes[] = "Tu historia se ha publicado con éxito.";
				//redirigir
				header('location:'.url()."/post/?read=".$slug);		
			}
		}
		//importar
		if (isset($_POST['post_import'])) {
			if (!empty($_FILES['import']['name'])) {
				$filename = $_FILES['import']['tmp_name'];
				$read = read_word($filename);
				require_once '../inc/purifier/library/HTMLPurifier.auto.php';
    			$purifier = new HTMLPurifier();
				$body = $purifier->purify($read);
			} else {
				$errors[] = "No has seleccionado ningún archivo";
			}
			if (!empty($_POST['title'])) {
				$title = $_POST['title'];
			} else {
				$title = preg_replace('/\.[^.]+$/','',$_FILES['import']['name']);
			}
			if (!empty($_POST['section'])) {
				$section = $_POST['section'];
			} else {
				$errors[] = "No puedes publicar sin sección";
			}
			//subir portada
			if (!empty($_FILES['cover']['name'])) {
				if ($_FILES['cover']['size'] < mroMxFlSz()) {
					$temp = explode('.', $_FILES['cover']['name']);
					$newfilename = substr(md5($title), 0, 7).substr(md5($_SESSION['username']), 0, 7).'.'.end($temp);
					$newheight = cover($_FILES['cover']['tmp_name']);
					$resize = mroResize($_FILES['cover']['tmp_name'], 780, $newheight);
					if (move_uploaded_file($resize, 'cover/'.$newfilename)) {
						$cover = 'cover/'.$newfilename;
					} else {
						$errors[] = "Lo sentimos, no se ha podido subir la imagen.";
					}
				} else {
					$errors[] = "Intenta subir una imagen que pese menos.";
				}
			} else {
				$cover = false;
			}
			if (empty($errors)) {
				$slug = mroSlug($title);
				$sql = $conn->prepare("INSERT INTO posts (title, body, author, slug, stamp, section, cover, updated, updated_m) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->execute([
					$title,
						$body,
						$_SESSION['user_id'], 
						$slug,
						time(), 
						$section, 
						$cover,
						time(),
						"posted"
					]);
				//redirigir
				header('location: '.url().'/post/?read='.$slug);
			}
		}
		//actualizar historia
		if (isset($_POST['post_update'])) {
			if (!empty($_POST['title'])) {
				$title = $_POST['title'];
			} else {
				$errors[] = "No puedes publicar sin título.";
			}
			if (!empty($_POST['text_body'])) {
				require_once '../inc/purifier/library/HTMLPurifier.auto.php';
    			$purifier = new HTMLPurifier();
				$body = $purifier->purify($_POST['text_body']);
			} else {
				$errors[] = "No puedes publicar una historia vacía.";
			}
			//subir portada
			if (!empty($_FILES['cover']['name'])) {
				if ($_FILES['cover']['size'] < mroMxFlSz()) {
					$temp = explode(".", $_FILES["cover"]["name"]);
					$newfilename = substr(md5($title), 0, 7).substr(md5($_SESSION['username']), 0, 7).'.'.end($temp);
					$newheight = cover($_FILES["cover"]["tmp_name"]);
					$resize = mroResize($_FILES["cover"]["tmp_name"], 780, $newheight);
					if (move_uploaded_file($resize, "cover/".$newfilename)) {
						$cover = 'cover/'.$newfilename;
					} else {
						$errors[] = "Lo sentimos, no se ha podido subir la imagen.";
					}
				} else {
					$errors[] = "Intenta subir una imagen que pese menos.";
				}
			} else {
				$cover = $_POST['excover'];
			}
			if (isset($_GET['edit'])) {
				$id = $_GET['edit'];
			} elseif (isset($_GET['draft'])) {
				$id = $_POST['post'];
			}
			if (empty($errors)) {
				//evaluar cambios
				$check = $conn->query("SELECT * FROM posts WHERE id = '$id'");
				while ($upd = $check->fetch()) {
					$current = md5($upd['body']);
					$currenttitle = md5($upd['body']);
					$currentsection = $upd['section'];
					if ($current == $body && $currentsection == $_POST['section'] && $currenttitle == $title) {
						$changes = 'nochange';
					} else {
						$changes = 'changes';
					}
				}
				$sql = $conn->prepare("UPDATE posts SET title = ?, body = ?, cover = ?, section = ?, updated = ?, updated_m = ? WHERE id = ?");
				$sql->execute([
					$title,
					$body,
					$cover,
					$_POST['section'],
					time(),
					$changes,
					$id
				]);
				//eliminar borrador automático
				$draft = $conn->prepare("DELETE FROM drafts WHERE id = ?");
				$draft->execute([$_POST['draft_id']]);
				//redirigir
				$slug = $conn->prepare("SELECT slug FROM posts WHERE id = ?");
				$slug->execute([$_GET['edit']]);
				header('location:'.url()."/post/?read=".$slug->fetch()['slug']);		
			}
		}
		//eliminar historia
		if (isset($_POST['post_delete'])) {
			$cover = $conn->prepare("SELECT * FROM posts WHERE id = ?");
			$cover->execute([$_GET['edit']]);
			$path = $cover->fetch()['cover'];
			if (!empty($path)) {
				unlink($path);
			}
			$sql = $conn->prepare("DELETE FROM posts WHERE id = ?");
			$sql->execute([$_GET['edit']]);
			$comments = $conn->prepare("DELETE FROM comments WHERE post = ?");
			$comments->execute([$_GET['edit']]);
			header('location:'.url()."/post/");
		}
	//borradores
		//guardar borrador
		if (isset($_POST['post_save'])) {
			if (!empty($_POST['title'])) {
				$title = $_POST['title'];
			} else {
				$title = date('l d F Y');
			}
			if (!empty($_POST['text_body'])) {
				require_once '../inc/purifier/library/HTMLPurifier.auto.php';
    			$purifier = new HTMLPurifier();
				$body = $purifier->purify($_POST['text_body']);
			} else {
				$errors[] = "No puedes guardar un borrador vacío.";
			}
			//subir portada
			if (!empty($_FILES['cover']['name'])) {
				if ($_FILES['cover']['size'] < mroMxFlSz()) {
					$temp = explode(".", $_FILES["cover"]["name"]);
					$newfilename = substr(md5($title), 0, 7).substr(md5($_SESSION['username']), 0, 7).'.'.end($temp);
					$newheight = cover($_FILES["cover"]["tmp_name"]);
					$resize = mroResize($_FILES["cover"]["tmp_name"], 780, $newheight);
					if (move_uploaded_file($resize, "cover/".$newfilename)) {
						$cover = 'cover/'.$newfilename;
					} else {
						$errors[] = "Lo sentimos, no se ha podido subir la imagen.";
					}
				} else {
					$errors[] = "Intenta subir una imagen que pese menos.";
				}
			} else {
				$cover = false;
			} if (!empty($_POST['section'])) {
				$section = $_POST['section'];
			} else {
				$section = false;
			} if (isset($_GET['add'])) {
				$class = 'subpost';
				$post = $_GET['add'];
			} else {
				$class = 'post';
				$post = false;
			}
			//comprobar id
			$check = $conn->prepare("SELECT COUNT(*) FROM drafts WHERE id = ?");
			$check->execute([$_POST['draft_id']]);
			//borrador nuevo
			if ($check->fetchColumn() == 0) {
				$sql = $conn->prepare("INSERT INTO drafts (id, title, body, author, stamp, section, class, post) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->execute([
					$_POST['draft_id'],
					$_POST['title'], 
					$body, 
					$_SESSION['user_id'], 
					time(), 
					$section,
					$class,
					$post
				]);
				header('location:'.url().'/post/');
			// actualizar borrador del autoguardado
			} else {
				$sql = $conn->prepare("UPDATE drafts SET title = ?, body = ?, author = ?, stamp = ?, cover = ?, section = ? WHERE id = ?");
				$sql->execute([
					$title,
					$body,
					$_SESSION['user_id'],
					time(),
					$cover,
					$section,
					$_POST['draft_id']
				]);
				header('location:'.url().'/post/');
			}
		}
		//eliminar borrador
		if (isset($_POST['draft_delete'])) {
			$sql = $conn->prepare("DELETE FROM drafts WHERE id = ?");
			$sql->execute([$_GET['draft']]);
		}
	//capítulos
		//añadir capítulo
		if (isset($_POST['subpost_post'])) {
			if (!empty($_POST['title'])) {
				$title = $_POST['title'];
			} else {
				$errors[] = "No puedes publicar sin título.";
			}
			if (!empty($_POST['text_body'])) {
				require_once '../inc/purifier/library/HTMLPurifier.auto.php';
    			$purifier = new HTMLPurifier();
				$body = $purifier->purify($_POST['text_body']);
			}
			if (isset($_POST['subpost'])) {
				$add = $_POST['subpost'];
			} else {
				$add = $_GET['add'];
			}
			if (empty($errors)) {
				$sql = $conn->prepare("INSERT INTO subposts (title, body, post, author, stamp) VALUES (?, ?, ?, ?, ?)");
				$sql->execute([
					$title,
					$body,
					$add,
					$_SESSION['user_id'],
					time()
				]);
				//eliminar borrador automático
				$draft = $conn->prepare("DELETE FROM drafts WHERE id = ?");
				$draft->execute([$_POST['draft_id']]);
				//actualizar el microtime del post original
				$subtime = $conn->prepare("UPDATE posts SET updated = ?, updated_m = ? WHERE id = ?");
				$subtime->execute([
					time(),
					"subpost",
					$add
				]);
				//redirigir
				$slug = $conn->prepare("SELECT slug FROM posts WHERE id = ?");
				$slug->execute([$_GET['add']]);
				$and = $conn->prepare("SELECT id FROM subposts WHERE post = ? ORDER BY id DESC");
				$and->execute([$_GET['add']]);
				header('location:'.url()."/post/?read=".$slug->fetch()['slug']."&and=".$and->fetch()['id']);
			}
		}
		//actualizar capítulo
		if (isset($_POST['subpost_update'])) {
			if (!empty($_POST['title'])) {
				$title = $_POST['title'];
			} else {
				$errors[] = "No puedes publicar sin título.";
			}
			if (!empty($_POST['text_body'])) {
				require_once '../inc/purifier/library/HTMLPurifier.auto.php';
    			$purifier = new HTMLPurifier();
				$body = $purifier->purify($_POST['text_body']);
			} else {
				$errors[] = "No puedes publicar un capítulo vacío.";
			}
			if (empty($errors)) {
				$sql = $conn->prepare("UPDATE subposts SET title = ?, body = ?, author = ?, stamp = ? WHERE id = ?");
				$sql->execute([
					$title,
					$body,
					$_SESSION['author'],
					time(),
					$_GET['and']
				]);
				//redirigir
				$slug = $conn->prepare("SELECT slug FROM posts WHERE id = ?");
				$slug->execute([$_GET['edit']]);
				header('location:'.url()."/post/?read=".$slug->fetch()['slug']."&and=".$_GET['and']);
			}
		}
		//eliminar capítulo
		if (isset($_POST['subpost_delete'])) {
			$sql = $conn->prepare("DELETE FROM subposts WHERE id = ?");
			$sql->execute([$_GET['and']]);
		}
//funciones de administración
	//configuración general
		if (isset($_POST['site_update'])) {
			if (!empty($_POST['name'])) {
				$name = $_POST['name'];
			} else {
				$errors[] = "El nombre del sitio no puede quedar vacío.";
			}
			if ($_FILES['logo']['size'] != 0) {
				$newfilename = 'inc/img/'.$name.'.jpg';
				$resize = mroResize($_FILES['logo']['tmp_name'], 320, 320);
				if(move_uploaded_file($resize, $newfilename)) {
					$logo = $newfilename;
				} else {
					$errors[] = "Lo sentimos no se ha podido cargar la imagen.";
				}
			} else {
				$logo = false;
			}
			$headerbg = $_POST['headerbg'];
			$action = $_POST['actioncolor'];
			if (!empty($_POST['webmail'])) {
				$webmail = $_POST['webmail'];
			} else {
				$webmail = false;
			}
			if (!empty($_POST['rules'])) {
				$rules = $_POST['rules'];
			} else {
				$rules = false;
			}
			if (!empty($_POST['maxfilesize'])) {
				$maxfilesize = $_POST['maxfilesize'];
			} else {
				$maxfilesize = '1';
			}
			if (empty($errors)) {
				$sql = $conn->prepare("UPDATE install SET name = ?, logo = ?, headerbg = ?, action = ?, webmail = ?, rules = ?, maxfilesize = ? WHERE fingerprint = ?");
				$sql->execute([
						$name, 
						$logo, 
						$headerbg,
						$action,
						$webmail,
						$rules,
						$maxfilesize, 
						mroFingerPrint()
					]);
				$successes[] = "La configuración se ha guardado.";
			}
		}
	//secciones
		//añadir sección
		if (isset($_POST['section_create'])) {
			if (!empty($_POST['name'])) {
				$name = $_POST['name'];
			} else {
				$errors[] = "El título de la sección no puede quedar vacío.";
			}
			if (!empty($_POST['color'])) {
				$color = $_POST['color'];
			} else {
				$color = '333333';
			}
			if (!empty($_POST['adminonly'])) {
				$adminonly = '1';
			} else {
				$adminonly = '0';
			}
			if (!empty($_POST['slug'])) {
				if (preg_match('/[^0-9A-Za-z-]/', $_POST['slug'])) {
					$errors[] = "El slug no puede contener espacios.";
				} else {
					$slug = $_POST['slug'];
				}
			} else {
				$slug = trim(strtolower(preg_replace('/[^0-9A-Za-z-]/', '', $_POST['slug'])));	
			}
			if (empty($errors)) {
				$sql = $conn->prepare("INSERT INTO sections (name, adminonly, color, description, slug) VALUES (?, ?, ?, ?, ?)");
				$sql->execute([
					$name, 
					$adminonly, 
					$color, 
					$_POST['description'],
					$slug
				]);
				$successes[] = "La sección ha sido añadida.";
			}
		}
		//actualiza sección
		if (isset($_POST['section_update'])) {
			if (!empty($_POST['name'])) {
				$name = $_POST['name'];
			} else {
				$errors[] = "El título de la sección no puede quedar vacío.";
			}
			if (!empty($_POST['color'])) {
				$color = $_POST['color'];
			} else {
				$color = '333333';
			}
			if (!empty($_POST['adminonly'])) {
				$adminonly = '1';
			} else {
				$adminonly = '0';
			}
			if (!empty($_POST['slug'])) {
				if (preg_match('/[^0-9A-Za-z-]/', $_POST['slug'])) {
					$errors[] = "El slug no puede contener espacios.";
				} else {
					$slug = $_POST['slug'];
				}
			} else {
				$slug = $_POST['slug'];	
			}
			if (empty($errors)) {
				$sql = $conn->prepare("UPDATE sections SET name = ?, adminonly = ?, color = ?, description = ?, slug = ? WHERE id = ?");
				$sql->execute([
					$name, 
					$adminonly, 
					$color, 
					$_POST['description'],
					$slug,
					$_POST['section_id']
				]);
				$successes[] = "La sección ha sido actualizada.";
			}
		}
		//borrar sección
		if (isset($_POST['section_delete'])) {
			if (empty($_POST['understand'])) {
				$errors[] = "Debes confirmar que deseas borrar esta sección.";
			}
			if (empty($errors)) {
				$sql = $conn->prepare("DELETE FROM sections WHERE id = ?");
				$sql->execute([$_POST['section_id']]);
			}
		}
	//usuarios
		//corazones y badges
			//otorgar corazón
			if (isset($_POST['badge_user'])) {
				$sql = $conn->prepare("INSERT INTO badges (receiver, color, attr) VALUES (?, ?, ?)");
				$sql->execute([
					$_GET['user'],
					$_POST['color'],
					$_POST['attr']
				]);
				$successes[] = "El corazón ha sido añadido.";
			}
			//eliminar corazón
			if (isset($_POST['unbadge'])) {
				$sql = $conn->prepare("DELETE FROM badges WHERE id = ?");
				$sql->execute([$_POST['badge_id']]);
				$successes[] = "El corazón ha sido eliminado.";
			}
		//hacer admin
		if (isset($_POST['admin_user'])) {
			$sql = $conn->prepare("UPDATE users SET role = ? WHERE username = ?");
			$sql->execute([
				'1',
				$_GET['user']
			]);
			$successes[] = "Este usuario es ahora un administrador.";
		}
		//revocar admin
		if (isset($_POST['unadmin_user'])) {
			$sql = $conn->prepare("UPDATE users SET role = ? WHERE username = ?");
			$sql->execute([
				'',
				$_GET['user']
			]);
			$successes[] = "Este usuario ya no es un administrador.";
		}
		//bloquear usuario
		if (isset($_POST['block_user'])) {
			$sql = $conn->prepare("UPDATE users SET access = ? WHERE username = ?");
			$sql->execute([
				time(), 
				$_GET['user']
			]);
			$successes[] = "El usuario ha sido bloqueado.";
		}
		//desbloquear usuario
		if (isset($_POST['unblock_user'])) {
			$sql = $conn->prepare("UPDATE users SET access = ? WHERE username = ?");
			$sql->execute([
				'1',
				$_GET['user']
			]);
		}
		//borrar usuario
		if (isset($_POST['delete_user'])) {
			if (empty($_POST['understand'])) {
				$errors[] = "Debes confirmar que entiendes las consecuencias de borrar a este usuario.";
			}
			if (empty($errors)) {
				$username = $_GET['user'];
				$user = $conn->query("DELETE FROM users WHERE username = '$username'");
				$comments =  $conn->query("DELETE FROM comments WHERE author = '$username'");
				$posts = $conn->query("DELETE FROM posts WHERE author = '$username'");
				$drafts = $conn->query("DELETE FROM drafts WHERE author = '$username'");
				$successes[] = "El usuario ha sido borrado.";
			}
		}
	//portada
		//imagen
		if (isset($_POST['front'])) {
			if (!empty($_FILES['front']['name'])) {
				if ($_FILES['front']['size'] < mroMxFlSz()) {
					$front = $_FILES['front']['name'];
					$file = $_FILES['front']['tmp_name'];
					$exploded = explode('.', $front);
				    $ext = $exploded[count($exploded) - 1]; 
				    if (preg_match('/jpg|jpeg/i', $ext)) {
				        $tmp=imagecreatefromjpeg($file);
				    } elseif (preg_match('/png/i', $ext)) {
				        $tmp=imagecreatefrompng($file);
				    } elseif (preg_match('/gif/i', $ext)) {
				        $tmp=imagecreatefromgif($file);
				    } elseif (preg_match('/bmp/i', $ext)) {
				        $tmp=imagecreatefrombmp($file);
				    } else {
				        $errors[] = "No se pudo procesar la imagen seleccionada.";
				    }
				    imagejpeg($tmp, '../inc/img/front.jpg', 85);
				    imagedestroy($tmp);
				    $successes[] = "La imagen de portada ha sido actualizada.";
				} else {
					$errors[] = "Intenta subir una imagen que pese menos.";
				}
			} else {
				$cover = false;
			}
		}
		//sección
		if (isset($_POST['section_front'])) {
			$id = $_POST['section_id'];
			$time = time();
			$sql = $conn->query("UPDATE sections SET front = '$time' WHERE id = '$id'");
			$successes[] = "La sección ha subido al primer puesto.";
		}
		if (isset($_POST['section_unfront'])) {
			$id = $_POST['section_id'];
			$time = time();
			$sql = $conn->query("UPDATE sections SET front = '0' WHERE id = '$id'");
			$successes[] = "La sección ha vuelto a posicionar orgánicamente.";
		}
		
		//enviar post
		if (isset($_POST['post_front'])) {
			$id = $_GET['post'];
			$time = time();
			$sql = $conn->query("UPDATE posts SET front = '$time', updated = '$time' WHERE id  = '$id'");
			$successes[] = "La historia se ha añadido a la portada.";
		}
		//retirar post
		if (isset($_POST['post_unfront'])) {
			$id = $_GET['post'];
			$sql = $conn->query("UPDATE posts SET front = '0' WHERE id  = '$id'");
			$successes[] = "La historia se ha retirado de la portada.";
		}