<?php
echo"
<html>
<head>
  <style>
  body { width: 1350px;
	height: 500px; }
	.logo{
	width:50px;
	padding:5px;	
	}
	
	p{
	color:black;
	}
	
	ul {
    list-style-type: none;
    margin: 0;
	width:35%;
    padding: 0;
    overflow: hidden;
    background-color: #333;
	}
	
	.todo{
	 color: black;
	 height:30px;
	 text-decoration:bold;
	}
	li {
    float: left;	
	}
	.first{
	width:35%;
	background-color:#F1F1F1;
	
	}
	li a {
    display: block;
	width:100%;
    color: white;
    text-align: center;
	padding-left:8px;
    padding: 10px 14px ;
    text-decoration: none;
	}
	.name{
	 float:left;
	 font-size:20px;
	 padding-left:30px;
	 
	}
	.logo{
	
		 float:left;
	}
	.top{
		 clear:both;	 
	}
	.block{
		 background-color:#F1F1F1;
		 padding:5px;
		 width:35%;
		 
	}
 
	/* Change the link color00 to #111 (black) on hover */
	li a:hover {
    background-color: #111;
	}
	</style>
</head>
<body>";
   



//MAIN FUNCTION CALLING
main();

//MAIN DEFINATION
function main(){
			
		 $config = array('client_id'=> '95551f185d0d03111febb64269143405',
        'client_secret' => 'ffc81d6e5341f30ce5dc5f57aac8ffdf',
        'api_endpoint'=> 'https://api.codechef.com/',
        'authorization_code_endpoint'=> 'https://api.codechef.com/oauth/authorize',
        'access_token_endpoint'=> 'https://api.codechef.com/oauth/token',
        'redirect_uri'=> 'http://localhost/cc/re.php',
        'website_base_url' => 'http://localhost/cc/');
		$oauth_details = array('authorization_code' => '',
        'access_token' => '',
        'refresh_token' => '');
		
		
		//defalut page called after oauth / authorization
    if(isset($_GET['code'])){
		
		//getting the ouath code to make api calls
        $oauth_details['authorization_code'] = $_GET['code'];
        $oauth_details = generate_access_token_first_time($config, $oauth_details);
		
		//all the api calls
		 $name = make_contest_problem_api_request_name($config, $oauth_details);
		 $on_events = make_contest_problem_api_request_on_events($config, $oauth_details);
		 $up_events = make_contest_problem_api_request_up_events($config, $oauth_details);
		 $todo = make_contest_problem_api_request_todo($config, $oauth_details);
	
		//SAVING THE ARRAYS/VARIABLES
		session_start();
		$_SESSION['config']=$config;
		$_SESSION['oauth_details']=$oauth_details;
		$_SESSION['name']=$name;
		$_SESSION['on']=$on_events;
		$_SESSION['up']=$up_events;
		$_SESSION['todo']=$todo;
		
		
		$name=strip_tags($name);
		$name=json_decode($name,true);
		
		//HEADER PART OF THE SITE 
		include 'header.php';

		echo "
			<div class ='block'>		 <a class ='first' >
			<h3>
		 CodeChef is a competitive programming website. It is a non
		 -profit educational initiative of Directi aimed 
		 at providing a platform for students, young software professionals 
		 to practice, hone their programming skills through online contests Apart from this, the 'CodeChef For Schools' 
		 program aims to reach out to young students and inculcate
		 a culture of programming in Indian schools.
		 </h3>
		 </a>
		 </div>
		 ";
		}//if ends
			
			
			
		//executes if user clicks on the on going events tab
		if (isset($_GET['on'])) {
			
			//getting the details and api array for on going events 
			session_start();
			$config=$_SESSION['config'];
			$oauth_details=$_SESSION['oauth_details'];
			$on_events=$_SESSION['on'];
			$name=$_SESSION['name'];
			 
			 
			 $name=strip_tags($name);
			 $name=json_decode($name,true); 
			 
			include 'header.php';
			echo "<p class='todo'><b>THIS IS THE LIST OF ONGOING EVENTS</b></p>";
			
			 $on_events=strip_tags($on_events);
			 $on_events=json_decode($on_events,true);
			 for ($x = 0; $x <count($on_events["result"]["data"]["content"]["contestList"]); $x++) 
			{
			echo "<div class='block'>";
			echo '<a href =https://codechef.com/'.$on_events["result"]["data"]['content']['contestList'][$x]['code'].'>';
			echo $on_events["result"]["data"]['content']['contestList'][$x]['name'];
			echo '</a>';
			echo "<p padding-left:5px></p>";
			echo "START DATA = ".$on_events["result"]["data"]['content']['contestList'][$x]['startDate'];
			echo "<p padding-left:5px></p>";
			echo "END DATE = ".$on_events["result"]["data"]['content']['contestList'][$x]['endDate'];
			echo "</div>";	
			echo "<p></p>";
			}	 
		}
		
		
		//executes if user clicks on the up coming events tab
		if (isset($_GET['up'])) {
			session_start();
			$config=$_SESSION['config'];
			$oauth_details=$_SESSION['oauth_details'];
			$up_events=$_SESSION['up'];
			$name=$_SESSION['name'];
			
			 
		
			$name=strip_tags($name);
			$name=json_decode($name,true); 
		
			include 'header.php';
			
			$up_events=strip_tags($up_events);
			echo "<p class='todo'><b>THIS IS THE LIST OF UPCOMING EVENTS</b></p>";
			$up_events=json_decode($up_events,true);
			 for ($x = 0; $x <count($up_events["result"]["data"]["content"]["contestList"]); $x++) 
			{
			echo "<div class='block'>";
			echo '<a href =https://codechef.com/'.$up_events["result"]["data"]['content']['contestList'][$x]['code'].'>';
			echo $up_events["result"]["data"]['content']['contestList'][$x]['name'];
			echo '</a>';
			echo "<p padding-left:5px></p>";
			echo "START DATA = ".$up_events["result"]["data"]['content']['contestList'][$x]['startDate'];
			echo "<p padding-left:5px></p>";
			echo "END DATE = ".$up_events["result"]["data"]['content']['contestList'][$x]['endDate'];
			echo "</div>";	
			echo "<p></p>";
			}
	
		}
				 
				 
		//executes if user clicks on todo tab
		if (isset($_GET['todo'])) {
			
			session_start();
			$config=$_SESSION['config'];
			$oauth_details=$_SESSION['oauth_details'];
			$name=$_SESSION['name'];
			$todo=$_SESSION['todo'];
			 
		
			$name=strip_tags($name);
			$name=json_decode($name,true); 
		
			include 'header.php';
		
			echo "<p class='todo'><b>THIS IS YOUR TODO LIST</b></p>";
				
			$todo=json_decode($todo,true);
			$temp;
			$i=0;
			
			if(isset($todo['result']['data']['content'])){
			foreach( $todo['result']['data']['content'] as $value)
			{
				$temp[$i] = $value;
				$i=$i+1;
			}
			for($x=0;$x<$i;$x++)
			{	
				echo "<div class='block'>";
				echo ($x+1).")";
				echo "   ";
				echo "<a href =https://codechef.com".$temp[$x]['contestUrl']." >";
				echo $temp[$x]['problemName'];
				echo "</a>";
				echo "<p></p>";
			
			}
			}else{
				 echo "<p class='todo'><b>NO EVENTS HAS BEEN ADDED</b></p>";
			}
		}
				 	


		
		
}//main ends

//main function call

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
function make_contest_problem_api_request_on_events($config,$oauth_details){
    $path = $config['api_endpoint']."/contests?status=present";
    $response = make_api_request($oauth_details, $path);
    return $response;
}
function make_contest_problem_api_request_name($config,$oauth_details){
    $path = $config['api_endpoint']."/users/me";
    $response = make_api_request($oauth_details, $path);
    return $response;
}
	
function make_contest_problem_api_request_up_events($config,$oauth_details){
    $path = $config['api_endpoint']."/contests?status=future";
    $response = make_api_request($oauth_details, $path);
    return $response;
}

function make_contest_problem_api_request_todo($config,$oauth_details){
    $path = $config['api_endpoint']."/todo/problems";
    $response = make_api_request($oauth_details, $path);
    return $response;
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

echo "

</body>
</html>
";
?>