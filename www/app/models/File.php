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

    public static $acceptable_mimes = array(

        // CSV
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'text/anytext',
        'application/octet-stream',
        'application/txt',

        // Excel
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
    );
    /**
     * Uploded file should be passed in
     * @return [type] [description]
     */
    public function setUploadedFileProperties($file) {

        // Validate mimetype
        if (!in_array($file->getMimeType(), self::$acceptable_mimes)) {
            return false;
        }

        $destination_path = public_path() . '/storage/uploads/' . Session::getId();
        $this->directory  = $destination_path;
        $this->file_name  = $file->getClientOriginalName();
        $this->file_size  = $file->getSize();
        $status           = $file->move($destination_path, $this->file_name);

        return $status;
    }

    public function getFields() {
        $file_path       = $this->directory . '/' . $this->file_name;
        $csv_reader      = PHPExcel_IOFactory::createReader($this->getClassType());
        $this->php_excel = $csv_reader->load($file_path);
        $columns         = $this->php_excel->getActiveSheet()->toArray();
        $columns         = reset($columns);

        // Sanitize columns
        return self::sanitizeColumns($columns);
    }

    public static function sanitizeColumns($columns) {
        $result_columns = array();

        // Sort columns by names so duplicates can be identified by looking at
        // the closest array element. Then append a suffix to the duplicates.
        natsort($columns);
        $unique_columns = array();
        $j = 1;

        // Append suffixes to duplicate names
        for($i = 0; $i < count($columns); $i++) {
            if (current($columns) === next($columns)) {
                $next_item_key = key($columns);
                $unique_columns[$next_item_key] = $columns[$next_item_key] . ' - Duplicate ' . ++$j;

                // Append - Duplicate 0 to the very first duplicate column
                if ($j === 2) {
                    prev($columns);
                    $prev_item_key = key($columns);
                    $unique_columns[$prev_item_key] .= ' - Duplicate 1';

                    // Return to the next iterator so the algorithm can continue doing what
                    // it was doing before get into this IF
                    next($columns);
                }

                continue;
            } else {
                $next_item_key = key($columns);

                // Very first item is a special case where the value of the 0th element
                // should be inserted in to the resulting array not next($columns) pointed element.
                if ($i === 0) {
                    prev($columns);
                    $next_item_key = key($columns);
                }

                if (isset($columns[$next_item_key])) {
                    $unique_columns[$next_item_key] = $columns[$next_item_key];
                }
            }

            $j = 1;
        }

        unset($columns);

        // Restore the array based on the key sequence
        ksort($unique_columns);
        foreach ($unique_columns as $index => $column_name) {
            $sanitized_index = preg_replace('/[^\d_a-z]/i', '', $column_name);
            $result_columns[$sanitized_index] = $column_name;
        }

        return $result_columns;
    }

    public function getContent() {
        $file_path       = $this->directory . '/' . $this->file_name;
        $csv_reader      = PHPExcel_IOFactory::createReader($this->getClassType());
        $this->php_excel = $csv_reader->load($file_path);
        $columns         = $this->php_excel->getActiveSheet()->toArray();

        return $columns;
    }

    public function getFilePath() {
        return $this->directory . '/' . $this->file_name;
    }

    public function getFilePathOutput($type = 'CSV') {
        $info             = new SplFileInfo($this->getFilePath());
        $name_without_ext = $info->getBaseName('.' . $info->getExtension());

        return $this->directory . '/' . $name_without_ext . ' ' . date("Ymd His") . '.' . strtolower($type);
    }

    /**
     * Return PHPExcel class type required to read/write
     * the file.
     *
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function getClassType($type = null) {
        if ($type == null) {
            $file_path  = $this->directory . '/' . $this->file_name;
            $info       = new SplFileInfo($file_path);
            $type       = $info->getExtension();
        }

        switch (strtoupper($type)) {
            case 'XLS':
                $type = 'Excel5';
                break;

            case 'XLSX':
                $type = 'Excel2007';
                break;

            case 'CSV':
            default:
                $type = 'CSV';
                break;
        }

        return $type;
    }
}
