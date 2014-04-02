<?php

class CSVProcessController extends BaseController {
    protected $layout = 'layouts.master';

    public function __construct() {
        // Do nothing
    }

    public function view($file_id) {
        if (empty($file_id)) {

            // Go home, you are empty!
            return Redirect::action('HomeController@showWelcome');
        }

        $file      = UserFile::find($file_id);
        $columns   = $file->getFields();

        // Split columns based on first letter
        $columns_view = array();
        if (count($columns) > 30) {
            $group = 0;
            $group_max = 10;
            $i=0;
            foreach ($columns as $column_name => $column_display_name) {

                if ($i % $group_max == 0) {
                    $group = $i . ' - ' . $i + $group_max;
                    $group_label = 'Columns Group .. ' . $group;
                    $group = $i;
                }

                $columns_view[$group_label][$column_name] = $column_display_name;
                $i++;
            }
        } else {
            $columns_view = $columns;
        }

        $view_data = array(
            'file_id'      => $file_id,
            'page_title'   => 'Viewing ' . $file->file_name,
            'file_name'    => $file->file_name,
            'columns'      => $columns,
            'columns_menu' => $columns_view,
        );

        return View::make('home/view')->with($view_data);
    }

    public function preview($file_id, $limit = 10) {
        $view_data = array(
            'columns' => Input::get('merge_field'),
            'rows'    => $this->export($file_id, $limit)
        );

        return View::make('ajax/preview_table')->with($view_data);
    }

    public function getFileData($file_id) {
        if (empty($file_id)) {

            // Go home, you are empty!
            return Redirect::to('/');
        }

        $file = UserFile::find($file_id);
        $data = $file->getContent();

        return Response::json(array('aaData' => $data));
    }

    public function export($file_id, $limit = null, $include_headers = FALSE) {
        $merge_fields      = Input::get('merge_field');
        $columns           = Input::get('column');
        $column_separators = Input::get('column_separator');

        // TODO: May not required?
        if ($limit == null || $limit == 'null') {
            $limit = null;
        }

        $file = UserFile::find($file_id);

        $field_mapper = new FieldMapper($file);
        $field_mapper->loadContent();
        $field_mapper->setColumns($merge_fields, $columns, $column_separators);

        return $field_mapper->getProcessedContent($limit);
    }

    public function processMergeFields($file_id, $limit = null) {
        return Response::json($this->export($file_id, $limit));
    }

    public function processExportFile($file_id) {

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $file = UserFile::find($file_id);

        // Set properties
        $objPHPExcel->getProperties()
            ->setCreator("CSVFix.com")
            ->setLastModifiedBy("CSVFix.com")
            ->setTitle($file->file_name)
            ->setSubject('')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');

        $php_excel = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $rows      = $this->export($file_id, 100000, TRUE);
        $headers   = array_keys(reset($rows));

        // NOTE: Excel sheets gotta start with 1 not 0 (ex A1)
        $objPHPExcel->setActiveSheetIndex(0)->fromArray($headers, NULL, 'A1');
        foreach ($rows as $index => $row) {
            $objPHPExcel->setActiveSheetIndex(0)->fromArray($row, NULL, 'A' . (int)($index + 2));
        }

        $php_excel->save($file->getFilePathOutput());
        return Response::json(true);
    }

    public function downloadFile($file_id) {
        $file = UserFile::find($file_id);

        return Response::download($file->getFilePathOutput(), $file->file_name);
    }

}
