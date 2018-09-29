
<?php

echo "<html>
<head>
<style>
.everything{
	  margin: auto;
    width: 50%;
}
h2{
color:#395084 ;	
text-align: center;
	}
body{
	background-color:#a3ecf7;
}
.dropbutton {
    background-color: coral;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
	border-radius: 25px;
	z-index: 1;
}

.dropdown {
    position: static;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position:absolute;
    background-color:#f9f9f9;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.3);
    padding: 16px 16px;
}

.dropdown-content a {
    color: black;
    padding: 13px 16px;
    text-decoration:none;
    display: block;
    min-width: 50px;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
    display: inline-block;
	max-width: 150px;
	z-index: 0;
}

.dropdown:hover .dropbutton{
    background-color:#395084;
}
table.steelBlueCols {
  font-family: \"Comic Sans MS\", cursive, sans-serif;
  border: 4px solid #555555;
  background-color: #555555;
  width: 400px;
  text-align: center;
  border-collapse: collapse;
}
table.steelBlueCols td, table.steelBlueCols th {
  border: 0px solid #555555;
  padding: 5px 10px;
}
table.steelBlueCols tbody td {
  font-size: 12px;
  font-weight: bold;
  color: #FFFFFF;
}
table.steelBlueCols td:nth-child(even) {
  background: #4F94A4;
}
table.steelBlueCols thead {
  background: #192A41;
  background: -moz-linear-gradient(top, #525f70 0%, #303f54 66%, #192A41 100%);
  background: -webkit-linear-gradient(top, #525f70 0%, #303f54 66%, #192A41 100%);
  background: linear-gradient(to bottom, #525f70 0%, #303f54 66%, #192A41 100%);
}
table.steelBlueCols thead th {
  font-size: 15px;
  font-weight: bold;
  color: #FFFFFF;
  text-align: center;
  border-left: 5px solid #398AA4;
}
table.steelBlueCols thead th:first-child {
  border-left: none;
}

table.steelBlueCols tfoot td {
  font-size: 13px;
}
table.steelBlueCols tfoot .links {
  text-align: right;
}
table.steelBlueCols tfoot .links a{
  display: inline-block;
  background: #FFFFFF;
  color: #398AA4;
  padding: 2px 8px;
  border-radius: 5px;
}
</style>
<title>CODECHEF-PLUGIN</title>
</head>

<body>

<h2>What's Up With CodeChef!?</h2>


";


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
   // $path = $config['api_endpoint']."contests/".$contest_code."/problems/".$problem_code;
     $path = $config['api_endpoint']."/contests";
	$response = make_api_request($oauth_details, $path);
    return $response;
}
function on_gng($config,$oauth_details){
     $path = $config['api_endpoint']."/contests?status=present";
	$response = make_api_request($oauth_details, $path);
    return $response;
}
function up_cmg($config,$oauth_details){
     $path = $config['api_endpoint']."/contests?status=future";
	$response = make_api_request($oauth_details, $path);
    return $response;
}
function me($config,$oauth_details){
     $path = $config['api_endpoint']."/users/me";
	$response = make_api_request($oauth_details, $path);
    return $response;
}
function comp($config,$oauth_details,$usrn){
     $path = $config['api_endpoint']."/users/".$usrn;
	$response = make_api_request($oauth_details, $path);
    return $response;
}

function main(){

   $config = array('client_id'=> 'b9727d9e1336f6b5436864b1793b3351',
        'client_secret' => 'd774977e673b2b18e7bfd1675a472c58',
        'api_endpoint'=> 'https://api.codechef.com/',
        'authorization_code_endpoint'=> 'https://api.codechef.com/oauth/authorize',
        'access_token_endpoint'=> 'https://api.codechef.com/oauth/token',
        'redirect_uri'=> 'http://localhost:/i2n.php',
        'website_base_url' => 'http://localhost');

    $oauth_details = array('authorization_code' => '',
        'access_token' => '',
        'refresh_token' => '');
    if(isset($_GET['code'])){
        $oauth_details['authorization_code'] = $_GET['code'];
        $oauth_details = generate_access_token_first_time($config, $oauth_details);
		$oauth_details = generate_access_token_from_refresh_token($config, $oauth_details);
        $response1 = on_gng($config, $oauth_details);
		$response1 = strip_tags($response1);
		echo "<br>";
		$response1=json_decode($response1,true);
		//echo "<br>";
		$response2 = up_cmg($config, $oauth_details);
		$response2 = strip_tags($response2);
		$response2=json_decode($response2,true);
		$response3 = me($config, $oauth_details);
		$response3 = strip_tags($response3);
		$response3=json_decode($response3,true);
		echo "<div class=\"everything\">";
		
		echo "<div class=\"dropdown\">";
		$myArray1=$response1['result']['data']['content']['contestList'];
		$size1=sizeof($myArray1);
		echo "<button class=\"dropbutton\">ONGOING CONTESTS<button>";
	     $i=0;$disp1="";
		echo" <div class=\"dropdown-content\">";
		for($i=0;$i<$size1;$i++)
		{
		$disp1.='<a href="https://www.codechef.com/'.$myArray1[$i]['code'].'">'.$myArray1[$i]['name']."</a>"."<br>";	
		}
		echo $disp1;
		echo "</div>";
        echo "</div>";
		for($i=0;$i<30;$i++)
		 echo "&nbsp;";
		
		
		echo "<div class=\"dropdown\">";
		$myArray2=$response2['result']['data']['content']['contestList'];
		$size2=sizeof($myArray2);
		echo "<button  class=\"dropbutton\">UPCOMING CONTESTS<button>";
	     $i=0;$disp2="";
		echo" <div class=\"dropdown-content\">";
		for($i=0;$i<$size2;$i++)
		{
			$disp2.='<a href="https://www.codechef.com/'.$myArray2[$i]['code'].'">'.$myArray2[$i]['name']."</a>"."<br>";
		}
		echo $disp2;
		echo "</div>";
		echo "</div>";
		
		for($i=0;$i<30;$i++)
		 echo "&nbsp;";
		
		echo "<br>";
		echo "<div>";
		/* 
		$myArray3=$response3['result']['data']['content'];
		$i=0;$disp="";$usrn='';
		
		echo "<form method='post'>
		<input type='text' name='usrn'><br><input type='submit'>";
		
		$response4 = comp($config,$oauth_details,$_POST["usrn"]);
		$response4 = strip_tags($response4);
		$response4=json_decode($response4,true);
		$myArray4=$response4['result']['data']['content'];
if(isset($_POST['usrn']))		
{echo '<table class="steelBlueCols">
<thead>
<tr>
<th>YOU</th>
<th>FRIEND</th>
</tr>
</thead>
<tbody>
<tr>
<td>'.$myArray3['username'].'</td><td>'.$myArray4['username'].'</td></tr>
<tr>
<td>cell1_2</td><td>cell2_2</td></tr>
<tr>
<td>cell1_3</td><td>cell2_3</td></tr>
<tr>
<td>cell1_4</td><td>cell2_4</td></tr>
<tr>
<td>cell1_5</td><td>cell2_5</td></tr>
<tr>
<td>cell1_6</td><td>cell2_6</td></tr>
</tbody>
</tr>
</table>';}
echo "</div>"; */
echo "</div>";
   }
}
/* function dispp($a){
	//$response = make_contest_problem_api_request($config, $oauth_details);
    echo strip_tags($a);
}
 */
 main();

//if(isset($_GET['run'])){
//dispp($a);
//main();}
//
?>
