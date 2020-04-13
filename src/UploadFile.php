<?php

declare(strict_types = 1);

namespace Programster\UploadFileManager;


final class UploadFile
{
    private int $m_size;
    private string $m_name;
    private string $m_type;
    private string $m_filepath;
    private int $m_errorCode;


    public function __construct(array $inputArray)
    {
        $expectedKeys = array(
            'size',
            'name',
            'type',
            'tmp_name',
            'error',
        );

        foreach ($expectedKeys as $key)
        {
            if (!array_key_exists($key, $inputArray))
            {
                throw new \Exception("Missing expected key: {$key}");
            }
        }

        $this->m_size = $inputArray['size'];
        $this->m_name = $inputArray['name'];
        $this->m_type = $inputArray['type'];
        $this->m_filepath = $inputArray['tmp_name'];
        $this->m_errorCode = $inputArray['error'];
    }


    /**
     * Get the mimetype of the file by using finfo().
     * This is more secure than relying on getType() which just uses the browser
     * reported type for the upload.
     * @return string - the mimetype of the uploaded file.
     * @throws ExceptionUploadError - if there is no file to get mimetype of.
     */
    public function getMimeType() : string
    {
        if (!file_exists($this->getFilepath()))
        {
            throw new ExceptionUploadError($this->getErrorCode());
        }
        
        $finfo = new \finfo(FILEINFO_MIME);

        if (!$finfo) 
        {
            throw new Exception("Failed to open fileinfo database.");
        }

        /* get mime-type for a specific file */
        $filepath = $this->getFilepath();
        return $finfo->file($filepath);
    }
    
    
    /**
     * Returns whether there was an error with the upload.
     * @return bool - true if was an error, false not.
     */
    public function hasError() : bool 
    {
        return ($this->getErrorCode() !== 0);
    }
    
    
    /**
     * An alternative way of finding out if there was an error to
     * hasError(). 
     * @return bool - true if was successful, false if there was an error.
     */
    public function wasSuccessful() : bool
    {
        return ($this->getErrorCode() === 0);
    }
    
    
    /**
     * Fetches the relevant exception for the upload failing.
     * @return \Programster\UploadFileManager\ExceptionUploadError
     */
    public function getException() : ExceptionUploadError
    {
        return new ExceptionUploadError($this->getErrorCode());
    }
    
    
    /**
     * Get a string message for what went wfont with the upload.
     * @throws Exception if there is was no error to create a message for.
     * @return string - an error message.
     */
    public function getErrorMessage() : string
    {
        switch ($this->m_code)
        {
            case 0: throw new Exception("There was no error."); break;
            case UPLOAD_ERR_INI_SIZE: $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; break;
            case UPLOAD_ERR_FORM_SIZE: $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; break;
            case UPLOAD_ERR_PARTIAL: $message = "The uploaded file was only partially uploaded"; break;
            case UPLOAD_ERR_NO_FILE: $message = "No file was uploaded"; break;
            case UPLOAD_ERR_NO_TMP_DIR: $message = "Missing a temporary folder"; break;
            case UPLOAD_ERR_CANT_WRITE: $message = "Failed to write file to disk"; break;
            case UPLOAD_ERR_EXTENSION: $message = "File upload stopped by extension"; break;
            default: $message = "Unknown upload error"; break;
        }

        return $message;
    }


   # Accessors
    public function getSize() : int { return $this->m_size; }
    public function getName() : string { return $this->m_name; }
    public function getType() : string { return $this->m_type; }
    public function getFilepath() : string { return $this->m_filepath; }
    public function getErrorCode() : int { return $this->m_errorCode; }
}