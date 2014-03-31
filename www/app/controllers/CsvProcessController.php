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

        $view_data = array(
            'file_id'    => $file_id,
            'page_title' => 'Viewing ' . $file->file_name,
            'file_name'  => $file->file_name,
            'columns'    => $columns,
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

    public function export($file_id, $limit = null) {
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

}
