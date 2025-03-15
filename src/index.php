<?php 

//error_reporting(E_ALL); 
require_once  ('../vendor/autoload.php');
require_once ('../src/Config/constants.php');
require_once("./includes/mgFunctions.php");



use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\AuthorizationCodeContext;
use Microsoft\Graph\Core\NationalCloud;
use Microsoft\Graph\Core\Authentication\GraphPhpLeagueAccessTokenProvider;



$tenantId = MSTENANTID;
$clientId = MSCLIENTID;
$clientSecret = MSSECRETID;
$redirectUri = 'http://localhost/src/index.php';

$scopes = ['User.Read', 'Mail.ReadWrite', 'offline_access'];




session_start();

if(intval($_GET["logout"])==1){
    session_destroy();        
    $logout_url = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/logout?" . http_build_query([
        "post_logout_redirect_uri" => $redirect_uri
    ]);

    header("Location: $logout_url");
    exit;    
}


if (!isset($_GET['code']) &&  !isset($_SESSION["refreshToken"])) {
    $scope = urlencode("User.Read Mail.ReadWrite  offline_access"); // Permisos requeridos
    $authUrl = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/authorize?"
        . "client_id=$clientId"
        . "&response_type=code"
        . "&redirect_uri=$redirectUri"
        . "&response_mode=query"
        . "&scope=$scope";
        header("Location: $authUrl");

    exit;
}




if(isset($_GET['code'])){

    $authorizationCodeContext = new AuthorizationCodeContext(
        tenantId: $tenantId,
        clientId: $clientId,
        clientSecret: $clientSecret,
        authCode: $_GET["code"],
        redirectUri: $redirectUri
    );


    $tokenProvider = new GraphPhpLeagueAccessTokenProvider(
        tokenRequestContext: $authorizationCodeContext,
        scopes: $scopes,
    );

    $tokenProvider->getAuthorizationTokenAsync(NationalCloud::GLOBAL)->wait();
    $accessToken = $tokenProvider->getAccessTokenCache()->getAccessToken($authorizationCodeContext->getCacheKey());


    $tokenRequestContext = new OnBehalfOfContextUsingRefreshToken(
        tenantId: $tenantId,
        clientId: $clientId,
        clientSecret: $clientSecret,
        assertion: $accessToken->getRefreshToken(),
      );
      
      $_SESSION["accessToken"]=$accessToken->getToken();
      $_SESSION["refreshToken"]=$accessToken->getRefreshToken();

      
      header("location:index.php");die;


    $graphServiceClient =new  GraphServiceClient($tokenRequestContext,$scopes);

        try{
            $user = $graphServiceClient->me()->get()->wait();
            echo "<hr>";

            $methods = get_class_methods($graphServiceClient->me());
            print_r($methods);

            echo "<hr>";


            var_export($user);
            echo "Hello, I am {$user->getGivenName()}";            

        } catch (Exception $e) {
            echo "Error al realizar la solicitud: " . $e->getMessage();

        } 
    

}else{

    $imenu1=0;
    include_once('./includes/inc_header.php');
    ?>
    <div class="container">
        <?php 
        
        $sessiontoken=$_SESSION["refreshToken"];
        try{
            $tokenRequestContext = new OnBehalfOfContextUsingRefreshToken(
                tenantId: $tenantId,
                clientId: $clientId,
                clientSecret: $clientSecret,
                assertion: $sessiontoken,
            );
            $graphServiceClient =new  GraphServiceClient($tokenRequestContext,$scopes);
            $user = $graphServiceClient->me()->get()->wait();    
        
            echo "getGivenName-> {$user->getGivenName()}";
            echo "<br>getUserPrincipalName-> {$user->getUserPrincipalName()}";    
            echo "<br>getMail-> {$user->getMail()}";
            echo "<br>getJobTitle-> {$user->getJobTitle()}";
            echo "<br>getDepartment-> {$user->getDepartment()}";
            echo "<br>getCompanyName-> {$user->getCompanyName()}";
            echo "<hr>";
            //var_export($user);
        
        
        } catch (Exception $e) {
            echo "Error al realizar la solicitud: " . $e->getMessage();
        
        } 
        ?>
    </div>
    <?php

    include_once('./includes/inc_footer.php');


    }
    
    
    


    

