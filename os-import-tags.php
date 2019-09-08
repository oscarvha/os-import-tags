<?php
/*
 * Plugin Name: os import tags
 * Plugin URI: bibliotecadeterminus.xyz
 * Description: Importador XLS POST TAGS
 * Version: 1.0.0
 * Author: Oscar Sanchez
 * Author URI: bibliotecadeterminus.xyz
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 * Text Domain: wpos-additional
 * Domain Path: /languages/
 */

add_action('admin_menu', 'import_xls_menu');

require 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use OsImportTags\Exception\ProcessorException;
use OsImportTags\Processor\RowTagProcessor;
use OsImportTags\Util\FileReader;
use OsImportTags\Util\UploadFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * @param string $inputFileName
 * @return mixed
 * @throws \PhpOffice\PhpSpreadsheet\Exception
 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
 */
function import_xls(string $inputFileName) {

    /**  Load $inputFileName to a Spreadsheet Object  **/
    $fileReader = new FileReader($inputFileName);

    $data = $fileReader->getDataArray();

    $countPostUpdate = 0;

    $errors = [];

    foreach($data as $row ) {

        try {
            $processor = RowTagProcessor::createFromArray($row);
            $processor->process();
            $countPostUpdate ++;
        }catch(ProcessorException $e) {
            $errors[] = $e->getMessage();
            continue;
        }
    }

    $result['success'] = 'Update '.$countPostUpdate.' Post';
    $result['error'] = $errors;

    return $result;

}

add_action('admin_menu', 'add_to_menu_import');

function add_to_menu_import(){
    add_menu_page('OS Import XLS', 'OS Import XLS ', 'manage_options', 'import-xls-scrapping', 'admin_page_import');

}

/**
 * @throws \PhpOffice\PhpSpreadsheet\Exception
 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
 */
function admin_page_import()
{

    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient pilchards to access this page.')    );
    }


    echo '<div class="wrap">';

    echo '<h2>OS IMPORT XLS</h2>';


    if (isset($_POST['import_xls_tags']) && check_admin_referer('import_xls_tags_clicked')) {

        importXLSAction();
    }

    echo '<form  enctype="multipart/form-data"  method="post" action="options-general.php?page=import-xls-scrapping">';

    // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
    wp_nonce_field('import_xls_tags_clicked');
    echo '<input type="file"
       id="file" name="file" accept=".xls, .xlsx">> ';


    echo '<input type="hidden" value="true" name="import_xls_tags" />';
    submit_button('Importar tag desde XLS');
    echo '</form>';

    echo '</div>';

}

/**
 * @throws \PhpOffice\PhpSpreadsheet\Exception
 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
 */
function importXLSAction()
{

    try {
        $file = UploadFile::upload('dsg');

        $result = import_xls($file);
        echo '<div id="message" class="updated fade"><p>'
            .$result['success'].'</p></div>';

        if(count($result['error'])) {
            echo '<div id="message" class="error fade">';

            foreach($result['error'] as $error) {
                echo '<p>'.$error.'</p>';
            }

            echo '</div>';
        }

    }catch(ProcessorException $e) {

        echo '<div id="message" class="error fade"><p>Error FIle Upload</p>';
    }

}
