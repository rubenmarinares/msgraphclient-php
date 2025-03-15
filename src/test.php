<?php 


//error_reporting(E_ALL); 
require_once  ('../vendor/autoload.php');
require_once ('../src/Config/constants.php');

require_once("./includes/mgFunctions.php");


use Microsoft\Graph\GraphServiceClient;

$tenantId = MSTENANTID;
$clientId = MSCLIENTID;
$clientSecret = MSSECRETID;
$redirectUri = 'http://localhost/src/index.php';



$imenu1=1;

session_start();



$scopes = ['User.Read', 'Mail.ReadWrite','offline_access'];
$sessiontoken=$_SESSION["refreshToken"];


?>



<?php 
include_once('./includes/inc_header.php');
?>
<div class="container">
    <?php 
    
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
?>



