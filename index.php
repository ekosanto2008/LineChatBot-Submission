<?php

require __DIR__ . '/vendor/autoload.php';


use \LINE\LINEBot\SignatureValidator as SignatureValidator;

// load config
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// initiate app
$configs =  [
	'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);

/* ROUTES */
$app->get('/', function ($request, $response) {
	return "Line Bot Success";
});

$app->post('/', function ($request, $response)
{
	// get request body and line signature header
	$body 	   = file_get_contents('php://input');
	$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

	// log body and signature
	file_put_contents('php://stderr', 'Body: '.$body);

	// is LINE_SIGNATURE exists in request header?
	if (empty($signature)){
		return $response->withStatus(400, 'Signature not set');
	}

	// is this request comes from LINE?
	if($_ENV['PASS_SIGNATURE'] == false && ! SignatureValidator::validateSignature($body, $_ENV['CHANNEL_SECRET'], $signature)){
		return $response->withStatus(400, 'Invalid signature');
	}

	// init bot
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
	$data = json_decode($body, true);
	foreach ($data['events'] as $event)
	{
		$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == 'hai')
		{
			$message = "Halo kamu";
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}
		

		$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == 'nav')
		{
			$message = "Navigasi \n"; 
			$message .= "nav : menampilkan navigasi user \n"; 
			$message .= "Silakan pilih \"KOTA\" untuk memulai perjalanan. \n";
            $message .= "Aku akan menunjukan tempat wisata yang bagus. \n";
            $message .= "1. Jakarta \n";
            $message .= "2. Bandung \n";
            $message .= "3. Yogyakarta \n";
            $message .= "4. Malang \n";
            $message .= "5. Bali \n"; 
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}

			
		$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == "jakarta"){
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("MONAS", "Lokasi: Jakarta Pusat","https://kbr.id/media/?size=730x406&filename=monas.jpg",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://travelspromo.com/htm-wisata/monas-monumen-nasional/"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("KOTA TUA", "Lokasi: Jakarta Barat","https://dolanyok.com/wp-content/uploads/2019/09/kota-tua-1.jpg",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://dolanyok.com/kota-tua-jakarta/"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("SETU BABAKAN", "Lokasi: Jakarta Selatan","https://www.nativeindonesia.com/wp-content/uploads/2019/04/Setu-babakan.jpg",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://www.nativeindonesia.com/setu-babakan/"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("HUTAN MANGROVE", "Lokasi: Jakarta Utara","https://www.jejakpiknik.com/wp-content/uploads/2017/09/5-raun2nomaden-630x380.jpg",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://www.jejakpiknik.com/hutan-mangrove-pik/"),
			  ]),
			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('jakarta',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
			}

			$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == "bandung"){
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Memulai Pemrograman Dengan Kotlin", "Disusun oleh: Dicoding Indonesia","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/memulai_pemrograman_dengan_kotlin_logo_071119141033.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/80"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Aplikasi Android untuk Pemula", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/belajar_membuat_aplikasi_android_untuk_pemula_logo_071119140631.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://www.dicoding.com/academies/51"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Fundamental Aplikasi Android", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/menjadi_android_developer_expert_logo_071119140536.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/14"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Android Jetpack Pro", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/menjadi_android_developer_expert_logo_071119140536.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/129"),
			  ]),
			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('learning path android',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
			}

			$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == "yogyakarta"){
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Memulai Pemrograman Dengan Kotlin", "Disusun oleh: Dicoding Indonesia","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/memulai_pemrograman_dengan_kotlin_logo_071119141033.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/80"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Aplikasi Android untuk Pemula", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/belajar_membuat_aplikasi_android_untuk_pemula_logo_071119140631.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://www.dicoding.com/academies/51"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Fundamental Aplikasi Android", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/menjadi_android_developer_expert_logo_071119140536.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/14"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Android Jetpack Pro", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/menjadi_android_developer_expert_logo_071119140536.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/129"),
			  ]),
			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('learning path android',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
			}
			
			$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == "malang"){
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Memulai Pemrograman Dengan Kotlin", "Disusun oleh: Dicoding Indonesia","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/memulai_pemrograman_dengan_kotlin_logo_071119141033.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/80"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Aplikasi Android untuk Pemula", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/belajar_membuat_aplikasi_android_untuk_pemula_logo_071119140631.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://www.dicoding.com/academies/51"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Fundamental Aplikasi Android", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/menjadi_android_developer_expert_logo_071119140536.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/14"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Android Jetpack Pro", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/menjadi_android_developer_expert_logo_071119140536.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/129"),
			  ]),
			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('learning path android',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
			}

			$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == "bali"){
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Memulai Pemrograman Dengan Kotlin", "Disusun oleh: Dicoding Indonesia","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/memulai_pemrograman_dengan_kotlin_logo_071119141033.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/80"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Aplikasi Android untuk Pemula", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/belajar_membuat_aplikasi_android_untuk_pemula_logo_071119140631.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://www.dicoding.com/academies/51"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Fundamental Aplikasi Android", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/menjadi_android_developer_expert_logo_071119140536.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/14"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Belajar Android Jetpack Pro", "Disusun oleh: Google ATP","https://d2zvxgfo5lha7k.cloudfront.net/original/academy/menjadi_android_developer_expert_logo_071119140536.png",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"https://www.dicoding.com/academies/129"),
			  ]),
			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('learning path android',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
			}

		$userMessage = $event['message']['text'];
		if(strtolower($userMessage) != '' )
		{
			$message = "maaf anda belum memilih kota";
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}
			
	}
	

});

// $app->get('/push/{to}/{message}', function ($request, $response, $args)
// {
// 	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
// 	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);

// 	$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($args['message']);
// 	$result = $bot->pushMessage($args['to'], $textMessageBuilder);

// 	return $result->getHTTPStatus() . ' ' . $result->getRawBody();
// });

/* JUST RUN IT */
$app->run();