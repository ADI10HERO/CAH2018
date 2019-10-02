
<!DOCTYPE html>
<html>
<head>
<style>
.dropbutton {
    background-color: coral;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown {
    position: static;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position:absolute;
    background-color: #f9f9f9;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.3);
    padding: 16px 16px;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration:none;
    display: block;
    min-width: 160px;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbutton{
    background-color:#2bef79;
}
</style>
<title>TESTAPP_CENTER</title>
</head>

<body>

<h2>What do you want to do today?</h2>


<div class="dropdown">
  <button class="dropbutton"> CONTESTS</button>
  <div class="dropdown-content">
    <a href='i2.php?run=true'>
    <a href="">Link 2</a>
    <a href="">Link 3</a>
  </div>
</div>

<div class="dropdown">
  <button class="dropbutton">UPCOMING</button>
  <div class="dropdown-content">
    <a href="">Link 1</a>
    <a href="">Link 2</a>
    <a href="">Link 3</a>
  </div>
</div>

<div class="dropdown">
  <button class="dropbutton">SUBMIT</button>
  <div class="dropdown-content">
    <a href="">Link 1</a>
    <a href="">Link 2</a>
    <a href="">Link 3</a>
  </div>
</div>
</body>
</html>


<?php

function take_user_to_codechef_permissions_page($config){

    $params = array('response_type'=>'code', 'client_id'=> $config['client_id'], 'redirect_uri'=> $config['redirect_uri'], 'state'=> 'xyz');
    header('Location: ' . $config['authorization_code_endpoint'] . '?' . http_build_query($params));
    die();
}

function generate_access_token_first_time($config, $oauth_details){

    $oauth_config = array('grant_type' => 'authorization_code', 'code'=> $oauth_details['authorization_code'], 'client_id' => $config['client_id'],
                          'client_secret' => $config['client_secret'], 'redirect_uri'=> $config['redirect_uri']);
    $response = json_decode(make_curl_request($config['access_token_endpoint'], $oauth_config), true);
    $result = $response['result']['data'];

    $oauth_details['access_token'] = $result['access_token'];
    $oauth_details['refresh_token'] = $result['refresh_token'];
    $oauth_details['scope'] = $result['scope'];

    return $oauth_details;
}

function generate_access_token_from_refresh_token($config, $oauth_details){
    $oauth_config = array('grant_type' => 'refresh_token', 'refresh_token'=> $oauth_details['refresh_token'], 'client_id' => $config['client_id'],
        'client_secret' => $config['client_secret']);
    $response = json_decode(make_curl_request($config['access_token_endpoint'], $oauth_config), true);
    $result = $response['result']['data'];

    $oauth_details['access_token'] = $result['access_token'];
    $oauth_details['refresh_token'] = $result['refresh_token'];
    $oauth_details['scope'] = $result['scope'];

    return $oauth_details;

}

function make_api_request($oauth_config, $path){
    $headers[] = 'Authorization: Bearer ' . $oauth_config['access_token'];
    return make_curl_request($path, false, $headers);
}


function make_curl_request($url, $post = FALSE, $headers = array())
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    if ($post) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    }

    $headers[] = 'content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    return $response;
}


function make_contest_problem_api_request($config,$oauth_details){
    $problem_code = "SALARY";
    $contest_code = "PRACTICE";
    $path = $config['api_endpoint']."contests/".$contest_code."/problems/".$problem_code;
    $response = make_api_request($oauth_details, $path);
    return $response;
}

function main(){

    $config = array('client_id'=> 'b9727d9e1336f6b5436864b1793b3351',
        'client_secret' => 'd774977e673b2b18e7bfd1675a472c58',
        'api_endpoint'=> 'https://api.codechef.com/',
        'authorization_code_endpoint'=> 'https://api.codechef.com/oauth/authorize',
        'access_token_endpoint'=> 'https://api.codechef.com/oauth/token',
        'redirect_uri'=> 'http://localhost:/i2.php',
        'website_base_url' => 'http://localhost');

    $oauth_details = array('authorization_code' => '',
        'access_token' => '',
        'refresh_token' => '');
	//$output;
    if(isset($_GET['code'])){
        $oauth_details['authorization_code'] = $_GET['code'];
        $oauth_details = generate_access_token_first_time($config, $oauth_details);
        $response = make_contest_problem_api_request($config, $oauth_details);
        $oauth_details = generate_access_token_from_refresh_token($config, $oauth_details);         //use this if you want to generate access_token from refresh_token
        //$output = json_decode($response["body"],true);
		//echo strip_tags($response);
		return $response;
    } else{
        take_user_to_codechef_permissions_page($config);
    }
}
function dispp($a){
	//$response = make_contest_problem_api_request($config, $oauth_details);
    echo strip_tags($a);
}
$a = main();
if(isset($_GET['run'])){
dispp($a);
}
?>
