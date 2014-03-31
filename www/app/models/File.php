<?php

use Illuminate\Auth\Reminders\RemindableInterface;
use \PHPExcel_IOFactory;

class UserFile extends Eloquent{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'files';
    protected $php_excel = null;

    /**
     * Uploded file should be passed in
     * @return [type] [description]
     */
    public function setUploadedFileProperties($file) {
        $destination_path = public_path() . '/storage/uploads/' . Session::getId();
        $this->directory  = $destination_path;
        $this->file_name  = $file->getClientOriginalName();
        $this->file_size  = $file->getSize();
        $status           = $file->move($destination_path, $this->file_name);

        return $status;
    }

    public function getFields() {
        $file_path = $this->directory . '/' . $this->file_name;

        // $csv_reader = PHPExcel_IOFactory::createReader('Excel2007');
        $csv_reader = PHPExcel_IOFactory::createReader('CSV');

        $this->php_excel = $csv_reader->load($file_path);
        $columns         = $this->php_excel->getActiveSheet()->toArray();
        return reset($columns);
    }

    public function getContent() {
        $file_path = $this->directory . '/' . $this->file_name;

        // $csv_reader = PHPExcel_IOFactory::createReader('Excel2007');
        $csv_reader = PHPExcel_IOFactory::createReader('CSV');

        $this->php_excel = $csv_reader->load($file_path);
        $columns         = $this->php_excel->getActiveSheet()->toArray();
        return $columns;
    }

    public function getPHPExcelFileType() {
        //
        // Return one of the following (read the doc)
        //

        // $filetype = 'Excel5';
        // $filetype = 'Excel2007';
        // $filetype = 'Excel2003XML';
        // $filetype = 'OOCalc';
        // $filetype = 'SYLK';
        // $filetype = 'Gnumeric';

        return 'CSV';
    }

    public function getFilePath() {
        return $this->directory . '/' . $this->file_name;
    }
}
