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
			$message = "hai juga";
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
            $message .= "4. Bali \n";
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
				  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("GOA BELANDA", "Lokasi: Dago Pakar","https://i.misteraladin.com/blog/2019/11/14152903/goa-belanda-2jpg-sTvP.jpg",[
				  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://blog.misteraladin.com/jangan-berani-coba-coba-ucapkan-kata-ini-ketika-masuk-gua-belanda-di-dago/"),
				  ]),
				  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("KAWAH PUTIH", "Lokasi: Sugihmukti, Pasirjambu, Bandung","https://www.kepogaul.com/wp-content/uploads/2018/06/000157-00_wisata-kawah-putih-bandung_kawah-putih_800x450_ccpdm-min.jpg",[
				  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://www.kepogaul.com/wisata/wisata-kawah-putih-bandung/"),
				  ]),
				  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('bandung',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
			}

			$userMessage = $event['message']['text'];
			if(strtolower($userMessage) == "yogyakarta"){
				$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
				  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("MALIOBORO", "Lokasi: Jl. Malioboro","https://cdn2.tstatic.net/jogja/foto/bank/images/sepanjang-jalan-malioboro.jpg",[
				  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://jogja.tribunnews.com/2019/09/15/wisata-ke-jantung-yogyakarta-di-sepanjang-jalan-malioboro-syahdu-dan-menyenangkan"),
				  ]),
				  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("CANDI BOROBUDUR", "Lokasi: Magelang","https://lh3.googleusercontent.com/proxy/vEdJhBmX66zuvcRD1JvSknreRYDKcpNXT7dGf8stsEdFs_3cpzuwHO7h5pnamSr1h4tWTAGNdHEfnJJiM5vS7WAgz7uow-OSNbPfdUlCqFM4Wmx-3dQg_lmfci6XiAHCoN0TteeMQ7pn",[
				  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"http://bob.kemenpar.go.id/1896-candi-borobudur/"),
				  ]),
				  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("PANTAI PARANGTRITIS", "Lokasi: Yogya","https://cdn2.tstatic.net/jogja/foto/bank/images/tempat-wisata-yang-wajib-di-kunjungi-ketika-ke-parangtritis.jpg",[
				  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://jogja.tribunnews.com/2019/08/29/tempat-wisata-alternatif-yang-lokasinya-dekat-dengan-pantai-parangtritis"),
				  ]),
				  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('yogyakarta',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
			}


			$userMessage = $event['message']['text'];
			if(strtolower($userMessage) == "bali"){
				$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
				  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("PANTAI KUTA BALI", "Lokasi: kuta","https://kintamaniid-a903.kxcdn.com/wp-content/uploads/Keindahan-Pantai-Kuta-Bali-yang-sanggup-menarik-ribuan-wisatawan-setiap-tahunnya.jpg",[
				  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://www.kintamani.id/keindahan-pantai-kuta-bali-00244.html"),
				  ]),
				  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("KINTAMANI", "Lokasi: Kintamani","https://cdn.rentalmobilbali.net/wp-content/uploads/2018/10/Daya-Tarik-Pariwisata-Di-Kintamani.jpg",[
				  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Detail',"https://www.rentalmobilbali.net/kintamani/"),
				  ]),
				  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('bali',$carouselTemplateBuilder);
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

$app->run();