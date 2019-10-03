<?php
function take_user_to_codechef_permissions_page ($config)
{

    $params = array('response_type'=>'code', 'client_id'=> $config['client_id'], 'redirect_uri'=> $config['redirect_uri'], 'state'=> 'xyz');
    header('Location: ' . $config['authorization_code_endpoint'] . '?' . http_build_query($params));
    die();
}
function main(){

    $config = array('client_id'=> 'b9727d9e1336f6b5436864b1793b3351',
        'client_secret' => 'd774977e673b2b18e7bfd1675a472c58',
        'api_endpoint'=> 'https://api.codechef.com/',
        'authorization_code_endpoint'=> 'https://api.codechef.com/oauth/authorize',
        'access_token_endpoint'=> 'https://api.codechef.com/oauth/token',
        'redirect_uri'=> 'http://localhost:/i2n.php',
        'website_base_url' => 'http://localhost');

    take_user_to_codechef_permissions_page($config);
    }
main();
?>