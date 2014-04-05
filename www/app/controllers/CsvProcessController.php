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
        $column_strippers  = Input::get('column_stripper');

        // TODO: May not required?
        if ($limit == null || $limit == 'null') {
            $limit = null;
        }

        $file = UserFile::find($file_id);

        $field_mapper = new FieldMapper($file);
        $field_mapper->loadContent();
        if (!$field_mapper->setColumns($merge_fields, $columns, $column_separators, $column_strippers)) {
            return false;
        }

        return $field_mapper->getProcessedContent($limit);
    }

    public function processMergeFields($file_id, $limit = null) {
        return Response::json($this->export($file_id, $limit));
    }

    public function processExportFile($file_id, $type) {

        // Create new PHPExcel object
        $php_excel = new PHPExcel();
        $file = UserFile::find($file_id);

        // Set properties
        $php_excel->getProperties()
            ->setCreator("CSVFix.com")
            ->setLastModifiedBy("CSVFix.com")
            ->setTitle($file->file_name)
            ->setSubject('')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');

        $response = array(
            'status'  => true,
            'message' => 'Successfully processed.'
        );

        $excel_writer = PHPExcel_IOFactory::createWriter($php_excel, $file->getClassType($type));
        $rows         = $this->export($file_id, 100000, TRUE);
        if (!empty($rows)) {
            $headers = array_keys(reset($rows));

            // NOTE: Excel sheets gotta start with 1 not 0 (ex A1)
            $php_excel->setActiveSheetIndex(0)->fromArray($headers, NULL, 'A1');
            foreach ($rows as $index => $row) {
                $php_excel->setActiveSheetIndex(0)->fromArray($row, NULL, 'A' . (int)($index + 2));
            }

            $excel_writer->save($file->getFilePathOutput($type));
            $response['filetype']  = $type;
        } else {
            $response['status']  = false;
            $response['message'] = "Sorryyy... \n* You may have left an output column name blank. \n* haven't mapped columns from source file to one of the output columns you introduced.";
        }

        return Response::json($response);
    }

    public function downloadFile($file_id, $type) {
        $file = UserFile::find($file_id);

        return Response::download($file->getFilePathOutput($type));
    }

}
