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
	    // получить данные из формы через $_POST
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
			echo '<pre>';
			echo print_r($array);
			echo '</pre>';
			
			echo '<div class="out">'; 
			echo '<h2 class="out__login">Login: ' . $array['login'] . '</h2>' . '<br>';
			echo '<h2 class="out__login">Name: ' . $array['name'] . '</h2>' . '<br>';
		    echo '<img class="out__img" src=' . $array['avatar_url'] . ' width="200"/>';
            echo'</div>';
			
			// запись файла в директорию cache/
			file_put_contents('cache/'. $name . '.json', $response);
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
					
					file_put_contents( __DIR__ . '\image\\' . $name . '.png',  $file);
					 
				
				
			} catch(Exception $e){
				echo $e->getMessage();
			}
			
	        
		}
    ?>
	
	
	</div>
      
  
</main>

</body>
</html>

