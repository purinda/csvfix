<?php

use \PHPExcel_IOFactory;

class FieldMapper{

    protected $php_excel         = null;
    protected $content           = null;
    protected $processed_content = null;

    protected $output_fields     = array();
    protected $columns           = array();
    protected $column_separators = array();

    public function __construct(UserFile $file) {
        // Check for file path and throw an exception if no file found

        //  Create the PHPExcel object
        $worksheet_reader = PHPExcel_IOFactory::createReader($file->getPHPExcelFileType());
        $this->php_excel  = $worksheet_reader->load($file->getFilePath());

        // Free up some memory
        unset($worksheet_reader);
    }

    /**
     * Lazy load data
     *
     * @return false
     */
    public function loadContent() {
        $this->content = $this->php_excel->getActiveSheet()->toArray();

        // Free up some memory
        unset($this->php_excel);
    }

    public function setColumns($output_fields, $columns, $column_separators = null) {
        $this->output_fields     = $output_fields;

        if (empty($columns)) {
            $columns = array();
        }

        if (empty($column_separators)) {
            $column_separators = array();
        }

        foreach ($output_fields as $field_index => $field) {
            $this->columns[$field]           = array_values(isset($columns[$field_index]) ? $columns[$field_index] : array());
            $this->column_separators[$field] = array_values(isset($column_separators[$field_index]) ? $column_separators[$field_index] : array());
        }
    }

    protected function process($limit = null) {

        // Load content if empty
        if (empty($this->content)) {
            $this->loadContent();
        }

        $source_fields = reset($this->content);
        foreach ($this->content as $row_index => $row) {

            // Ignore the first row
            if ($row_index == 0) {
                continue;
            }

            $output_row = array();

            $row = array_combine($source_fields, $row);
            foreach ($this->columns as $destination_column => $source_columns) {

                // Limit csv data to what is required for the processing destination column
                $filtered_row  = array_intersect_key($row, array_flip($source_columns));

                // Get a sublist of column separators for the filtered row
                $column_separators = $this->column_separators[$destination_column];
                $array_reduce = function($filtered_row, $column_separators) {
                    $delimited_array = array();
                    foreach (array_values($filtered_row) as $index => $field_value) {
                        $delimited_array[] = $field_value . $column_separators[$index];
                    }
                    return implode($delimited_array);
                };

                $output_row[$destination_column] = $array_reduce($filtered_row, $column_separators);
            }

            $this->processed_content[] = $output_row;

            if (count($this->processed_content) >= $limit) {
                // Limit the processing result set
                return;
            }
        }

    }

    public function getProcessedContent($limit = null) {
        $this->process($limit);
        return $this->processed_content;
    }

}