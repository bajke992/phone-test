<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});


$app->post('/event-post', function (\Illuminate\Http\Request $request) use ($app) {
    $api_dev_key = '07f1f608ac24ee277e77214160bf4c67';

    $api_user_key = pastebin($api_dev_key);

    $api_paste_code = "Request Input: \r\n" . print_r($request->input(), true) . "\r\nRequest Content:\r\n" . print_r($request->getContent(), true);
    $api_paste_private = 2;
    $api_paste_name = date('Y-m-d H:i:s') . ' - Event post';
    $api_paste_expire_date = '1W';
    $api_paste_format = 'text';

    $http = new \GuzzleHttp\Client();
    $paste_url = 'http://pastebin.com/api/api_post.php';

    $response = $http->post($paste_url, [
        'form_params' => [
            'api_option' => 'paste',
            'api_user_key' => $api_user_key,
            'api_paste_private' => $api_paste_private,
            'api_paste_name' => $api_paste_name,
            'api_paste_expire_date' => $api_paste_expire_date,
            'api_paste_format' => $api_paste_format,
            'api_dev_key' => $api_dev_key,
            'api_paste_code' => $api_paste_code
        ]
    ]);
});
$app->get('/place-call', function () use ($app) { return view('form'); });
$app->post('/place-call', function (\Illuminate\Http\Request $request) use ($app) {
    $phone_ip = $request->input('phone_ip');
    $push_url = 'http://' . $phone_ip . '/push';
    $number = $request->input('number');
$xml = <<<XML
<PolycomIPPhone>
    <Data priority="Critical">\r\n
        Tel:$number;1;\r\n
    </Data>
</PolycomIPPhone>
XML;

    $payload = new \SimpleXMLElement($xml);
//    return $payload->asXML('/Users/bajke/Documents/test.xml');
//    $payload->addChild("Data", "Tel:" . $request->input('number') . ";1;")->addAttribute("priority", "Critical");

//    $payload->asXML('/home/vagrant/Code/' . date('Y-m-d H:i:s') . ' - Event post.xml');
    $http = new \GuzzleHttp\Client();

    $response = $http->post($push_url, [
        'headers' => [
            'Content-Type' => 'application/x-com-polycom-spipx.'
        ],
        'body' => $payload->asXML()
    ]);

    $api_dev_key = '07f1f608ac24ee277e77214160bf4c67';

    $api_user_key = pastebin($api_dev_key);

    $api_paste_code = "Response: \r\n" . print_r($response, true);
    $api_paste_private = 2;
    $api_paste_name = date('Y-m-d H:i:s') . ' - Data push response';
    $api_paste_expire_date = '1W';
    $api_paste_format = 'text';

    $paste_url = 'http://pastebin.com/api/api_post.php';

    $response = $http->post($paste_url, [
        'form_params' => [
            'api_option' => 'paste',
            'api_user_key' => $api_user_key,
            'api_paste_private' => $api_paste_private,
            'api_paste_name' => $api_paste_name,
            'api_paste_expire_date' => $api_paste_expire_date,
            'api_paste_format' => $api_paste_format,
            'api_dev_key' => $api_dev_key,
            'api_paste_code' => $api_paste_code
        ]
    ]);

    return redirect('/place-call');
});

function pastebin ($api_dev_key) {
    $api_user_name = urlencode('bajke');
    $api_user_password = urlencode('a1111111');
    $login_url = 'http://pastebin.com/api/api_login.php';

    $ch = curl_init($login_url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_dev_key='.$api_dev_key.'&api_user_name='.$api_user_name.'&api_user_password='.$api_user_password.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 0);

    return curl_exec($ch);
}