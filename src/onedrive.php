<?php 


//error_reporting(E_ALL); 
require_once  ('../vendor/autoload.php');
require_once ('../src/Config/constants.php');

require_once("./includes/mgFunctions.php");


use Microsoft\Graph\GraphServiceClient;
use Microsoft\Graph\Generated\Models\Drive;
use Microsoft\Graph\Generated\Models\DriveItem;
use Microsoft\Graph\Generated\Models\DriveItemCollectionResponse;

$tenantId = MSTENANTID;
$clientId = MSCLIENTID;
$clientSecret = MSSECRETID;
$redirectUri = 'http://localhost/src/index.php';



$imenu1=2;

session_start();



$scopes = ['User.Read', 'Mail.ReadWrite','offline_access'];
$sessiontoken=$_SESSION["refreshToken"];


$tokenRequestContext = new OnBehalfOfContextUsingRefreshToken(
    tenantId: $tenantId,
    clientId: $clientId,
    clientSecret: $clientSecret,
    assertion: $sessiontoken,
);
$graphServiceClient =new  GraphServiceClient($tokenRequestContext,$scopes);                


try{
    
    $drive=$graphServiceClient->me()->drive()->get()->wait();        
    /*
    echo "<hr>";
    echo "driveid: ".$drive->getId()."<br>";
    echo "url: ".$drive->getWebUrl()."<br>";
    echo "type: ".$drive->getDriveType()."<br>";
    echo "name: ".$drive->getName()."<br>";
    echo "<hr>";
    */      
    //Obtnemos colección de elementos de drive
    if(strlen($_GET["itemid"])>0) $itemid=$_GET["itemid"];
    else $itemid='root';

    $driveItems = $graphServiceClient->drives()->byDriveId($drive->getId())->items()->byDriveItemId($itemid)->children()->get()->wait();        

    /*
    $element=$graphServiceClient->drives()->byDriveId($drive->getId())->items()->byDriveItemId($itemid)->content()->get()->wait();
    var_export($element);

    header("Content-Type: application/octet-stream"); 
    header("Content-Disposition: attachment; filename=\"archivo_descargado.ext\""); 
    header("Content-Length: " . strlen($element));

    echo $element;
    exit;
    die;
    */

    //echo "<h4>Microsoft\Graph\Generated\Models\DriveItemCollectionResponse</h4>";
    //var_export($driveItems);
    //echo "<hr>";
    $driveItems = $driveItems->getValue(); // Obtiene la lista de DriveItem.
    //var_export($driveItems);
        

} catch (Exception $e) {
    echo "Error al realizar la solicitud: " . $e->getMessage();

}     
?>




<?php 
include_once('./includes/inc_header.php');
?>
<div class="container" style="padding:20px;">
        <?php 
        if (is_iterable($driveItems)) {
            foreach ($driveItems as $driveItem) {
                // Acceder a las propiedades de cada DriveItem
                $name = $driveItem->getName(); // Nombre del archivo o carpeta
                $id = $driveItem->getId(); // ID único
                $type = $driveItem->getOdataType(); // Tipo del elemento (archivo o carpeta)
                $size = $driveItem->getSize(); // Tamaño en bytes (solo para archivos)
        
                // Mostrar información del elemento
                echo "Nombre: $name";
                if ($driveItem->getFolder() !== null) {
                    echo '<a href="onedrive.php?itemid='.$id.'">Entrar</a><br>';
                } elseif ($driveItem->getFile() !== null) {
                    echo '<a href="onedrive.php?itemid='.$id.'">Ver</a><br>';
                } else {
                    echo "Este elemento no es ni un archivo ni una carpeta conocida.\n";
                }
                
                //echo "Tipo: $type<br>";
                echo "Tamaño: " . ($size ?? 'N/A (Carpeta)') . "<br>";
                echo "<hr>";
            }
        } else {
            echo "No hay elementos en la colección.<br>";
        }
        ?>
</div>
<?php 
include_once('./includes/inc_footer.php');
?>



