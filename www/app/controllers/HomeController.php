<?php

class HomeController extends BaseController {
    protected $layout = 'layouts.master';

    public function __construct() {
        // Do nothing
    }

	public function showWelcome()
	{
        $view_data = array(
            'page_title' => 'Home',
        );

        return View::make('home/index')->with($view_data);
	}

    public function upload() {
        $file = null;

        if (Input::hasFile('file')) {

            // Move uploaded file
            $file   = new UserFile;
            $status = $file->setUploadedFileProperties(Input::file('file'));

            // Save file details
            $file->save();
        }

        // flash data redirect
        return Redirect::to('/view/' . $file->id);
    }

}
