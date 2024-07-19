<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8" />
<title>Get Data From GitHub</title>
<link rel="stylesheet" href="main.css">
</head>
<body>
<main class="main">
  <div class="container">
    <div class="wrapper">
      <form class="form" action='index.php' method="POST" >
        <div class="form__header">
          <h1 class="form__h1">Data from GitHub</h1>
        </div>
         <div class=form__div>
            <label class="form__label">Enter Name: </label>
            <input class="form__input" type="text" name="name" />
            <button class="form__button" (click)="showData()">Search</button>
        </div>
      </form>
	 </div>
	
	<?php
		  echo '<div class="out">'; 
					   // вывести таблицу с данными из файлов, если файлы загружены в директории
					   echo '<table class="table">';
					   echo '<tr class="table__tr">';
					   echo '<th class="table__th">login</th>';
					   echo '<th class="table__th">name</th>';
					   echo '<th class="table__th">avatar</th>';
					   echo '<th class="table__th">gist url</th>';
					   echo '</tr>';
					
						   $array_from_json = [];
						   $dir = __DIR__.'\cache\\'; // путь к каталогу c загруженными файлами
					    // Открыть известный каталог и начать считывать его содержимое
						if (is_dir($dir)) {
							if ($dh = opendir($dir)) {
								while (($file = readdir($dh)) !== false) {
									// если очередной объект - это файл, то выводим на печать
									if(filetype($dir . $file) == 'file'){
										// в file_get_contents() передать полный путь с файлу
										$array_from_json = json_decode(file_get_contents($dir. $file), true);
										  echo '<tr class="table__tr">';
										  echo '<td class="table__td">' . $array_from_json['login'] . '</td>';
										  echo '<td class="table__td">' . $array_from_json['name'] . '</td>';	
										   // найти картинку в другой папке
										    $dir_img = __DIR__.'\image\\'; // путь к каталогу c загруженными картинками 
											 if($dhi = opendir($dir_img)){
												 while (($file_img = readdir($dhi)) !== false) {
													 // если название картники (name.png) соответствует текущему логину, то выводим в теге img
													if(filetype($dir_img . $file_img) == 'file'){
														 if($file_img == strtolower($array_from_json['login']. '.png'))  echo '<td class="table__td"><img src="image/' . $file_img . '" width="200"/></td>';	
													 }	
												 }
												 
											 }
										 
										 // echo '<td class="table__td">' . $array_from_json['avatar_url'] . '</td>';	
										  echo '<td class="table__td">' . $array_from_json['gists_url'] . '</td>';	
					                      echo '</tr>';
									}
									  
								}
								closedir($dh);
							}
						}
						
						
						echo '</table>';
						echo '</div>';
	
	
	    // получить данные из формы через $_POST
		// получить json с данными по ссылке и его в указанную директорию
		// получить фото по ссылке и загрузить его в указанную директорию
		// получить из директорий json'ы и вывести в таблицу
		// предаврительно преобразовать json'ы в массив
		// вывести массив данных и фото в таблице
		if($_POST['name']){
		$name = $_POST['name'];
		$url = "https://api.github.com/users/" . $name;
		$headers = array( 'User-Agent: x-treme-dev' ); // заголовок с логином для получения данных из GitHub
				
				function getRequest($url, $headers) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					$response = curl_exec($ch);
					curl_close($ch);
					return $response;
				}
					$response = getRequest($url, $headers);
					// преобразовать json в массив
					$array = json_decode($response, true);
					
					// запись файла json в директорию cache/
					file_put_contents('cache/'. $name , $response);
					// запись файла-изображения в директорию
					try{
						if( ! ( $ch = curl_init() ) ) 
								throw new Exception('Curl init failed');
								$options = [
									CURLOPT_URL            => $array['avatar_url'],
									CURLOPT_RETURNTRANSFER => true,
									CURLOPT_HTTPHEADER     => [
										'User-Agent' => 'x-treme-dev',
									]
								];
										
								curl_setopt_array($ch, $options);
								$file = curl_exec( $ch );
								// поместить $file в указанную директорию на сервере
								file_put_contents( __DIR__ . '\image\\' . $name . '.png',  $file);
					} catch(Exception $e){
						 echo $e->getMessage();
					  }
			           
				}else{
			echo 'name is not searched...';
		}
    ?>
	
	
	</div>
      
  
</main>

</body>
</html>

