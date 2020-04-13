<?php

declare(strict_types = 1);

namespace Programster\UploadFileManager;


final class UploadFileManager
{
    public function __construct()
    {
        
    }
    
    
    /**
     * Fetches the upload file objects. These will have keys matching the
     * name of the input field for the file, just like $_FILES
     * @return array - collection of UploadFile objects.
     */
    public function getUploadFiles() : array
    {
        $fileObjects = array();
    
        foreach ($_FILES as $inputFieldName => $fileArray)
        {
            $fileObjects[$inputFieldName] = new UploadFile($fileArray);
        }
        
        return $fileObjects;
    }
    
    
    /**
     * Return the html for the hidden input field for limiting file uploads
     * This is a "soft limit" and won't stop a user from a security point of view
     * but it will prevent a legitimate user from wasting time waiting for the
     * entire file to upload before getting the error message that it is too large.
     * @return string - the html for the hidden input field.
     */
    public function getMaxFileSizeHiddenInputField() : string
    {
        $maxFileSizeInBytes = $this->getMaxUploadFileSize();
        return '<input type="hidden" name="MAX_FILE_SIZE" value="' . $maxFileSizeInBytes . '" />';
    }
    
    
    /**
     * Get the maximum number of bytes your server will allow for an 
     * upload. This is useful for the MAX_FILE_SIZE hidden input field
     * you should put in your forms (before the actual file inputs).
     * @return int - the number of bytes.
     */
    public function getMaxUploadFileSize() : int
    {
        $sizes = [
            $this->convertToBytes(ini_get('post_max_size')),
            $this->convertToBytes(ini_get('upload_max_filesize')),
        ];

        return min($sizes); 
    }
    
    
    /**
     * Helper method to getMaxUploadFileSize which will convert ini setting
     * string values to bytes.
     * @param $val
     * @return int - the number of bytes.
     */
    private function convertToBytes(string $input) : int
    {
        $input = trim($input);
        $last = strtolower($input[strlen($input)-1]);
        $val = intval($input);

        switch ($last) 
        {
            case 'g': $val *= (1024 * 1024 * 1024); break;
            case 'm': $val *= (1024 * 1024); break;
            case 'k': $val *= 1024; break;
        }

        return $val;
    }
}