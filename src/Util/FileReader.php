<?php


namespace OsImportTags\Util;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FileReader
{
    /**
     * @var Spreadsheet
     */
    private $spreadSheet;

    /**
     * FileReader constructor.
     * @param string $file
     * @throws Exception
     */
    public function __construct(string $file)
    {
        $this->openFile($file);
    }

    /**
     * @param string $file
     * @throws Exception
     */
    private function openFile(string $file)
    {
        $inputFileType = IOFactory::identify($file);
        /**  Create a new Reader of the type that has been identified  **/
        $reader = IOFactory::createReader($inputFileType);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $this->spreadSheet = $reader->load($file);
    }

    /**
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function getDataArray(): array
    {
        return $this->spreadSheet->getActiveSheet()->toArray();
    }

}