<?php


namespace OsImportTags\Util;


use OsImportTags\Exception\UploadFileException;

class UploadFile
{
    /**
     * @param string $name
     * @return string
     * @throws UploadFileException
     */
    static function upload(string $name) : string
    {
        if(!isset($_FILES[$name]['name'])){
            throw new UploadFileException('Upload File Error');
        }


        $uploadedfile = $_FILES[$name];
        $upload_overrides = array( 'test_form' => false );

        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

        if(!$movefile && is_string($movefile['error'])) {
            throw new UploadFileException('Upload File Error');
        }

        return $movefile['file'];

    }

}