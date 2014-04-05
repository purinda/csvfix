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
            'copyright'  => '<a href="http://theredblacktree.wordpress.com" target="_blank">Copyright (C) 2014 Purinda Gunasekara</a>',
        );

        return View::make('home/index')->with($view_data);
	}

    public function upload() {
        $file      = null;
        $view_data = array('success' => false);

        if (Input::hasFile('file')) {

            // Move uploaded file
            $file   = new UserFile;
            $status = $file->setUploadedFileProperties(Input::file('file'));

            if (empty($status)) {
                return Redirect::to('/')->with($view_data);
            }

            // Save file details
            $file->save();

            return Redirect::to('/view/' . $file->id);
        }

        if (!Input::hasFile('file')) {
            // flash data redirect
            return Redirect::to('/')->with($view_data);
        }

    }

    public function resources() {
        $view_data = array(
            'page_title' => 'Resources',
            'copyright'  => '<a href="http://theredblacktree.wordpress.com" target="_blank">Copyright (C) 2014 Purinda Gunasekara</a>',
        );

        return View::make('home/resources')->with($view_data);
    }

}
