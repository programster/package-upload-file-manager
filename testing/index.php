<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Programster\CoreLibs\Core as Core;


$uploadManager = new Programster\UploadFileManager\UploadFileManager();
$files = $uploadManager->getUploadFiles();

if (count($files) > 0) 
{    
    foreach ($files as $file)
    {
        /* @var $file Programster\UploadFileManager\UploadFile */
        if ($file->hasError())
        {
            throw $file->getException();
        }
        else
        {
            die(print_r($file, true));
        }
    }
}

$hiddenMaxFileSizeInputField = $uploadManager->getMaxFileSizeHiddenInputField();
?>

<!-- The data encoding type, enctype, MUST be specified as below -->
<html>
    
    <body>
        <form enctype="multipart/form-data" action="/" method="POST">
            <?= $hiddenMaxFileSizeInputField; ?>
            <input name="file_input_name" type="file" /><br /><br />
            <input type="submit" value="Send File" />
        </form>
    </body>
</html>
