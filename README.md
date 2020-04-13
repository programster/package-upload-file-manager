# package-upload-file-manager
A package to make it easy to deal with upload files.

## Example Usages

```php
$uploadManager = new Programster\UploadFileManager\UploadFileManager();
$files = $uploadManager->getUploadFiles();

if (count($files) > 0) 
{
    /* @var $file Programster\UploadFileManager\UploadFile */
    $uploadFile = $files['my_file_input_field_name'];

    if ($uploadFile->hasError())
    {
        throw $file->getException();
    }
    else
    {
        // Upload was successful, do something with the file here.
        $uploadFile->getFilepath();
        $uploadFile->getSize();
        $uploadFile->getName();
        $uploadFile->getMimeType();
    }
}
```

If your form has multiple input fields for files:
```php
$uploadManager = new Programster\UploadFileManager\UploadFileManager();
$files = $uploadManager->getUploadFiles();

if (count($files) > 0) 
{
    // some files were uploaded, loop thorugh them.
    foreach ($files as $inputFieldName => $file)
    {
        /* @var $file Programster\UploadFileManager\UploadFile */
        if ($file->hasError())
        {
            throw $file->getException();
        }
        else
        {
            // Upload was successful, do something with the file here.
            $uploadFile->getFilepath();
            $uploadFile->getSize();
            $uploadFile->getName();
            $uploadFile->getMimeType();
        }
    }
}
```