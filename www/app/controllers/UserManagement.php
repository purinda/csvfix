<?php

class UserManagement extends BaseController {
    protected $layout = 'layouts.master';

    public function __construct() {
        // Do nothing
    }

    public function register() {
        $user = new User;
        $user->name     = Input::get('name');
        $user->email    = Input::get('email');
        $user->password = Hash::make(Input::get('password'));

        // TODO:
        // validate email, check already exists? if so change message, etc.

        // Save user
        $user->save();

        $json_result = array(
            'id'      => $user->id,
            'message' => 'Yay! Registration successful... Please wait a second, while we log you in!',
        );
        return Response::json($json_result);
    }

    public function signin($id = null) {

        // Automated login via user_id
        if (!empty($id)) {
            $user = User::find($id);
            Auth::login($user);

            return Redirect::to(URL::previous());
        }

        if (Auth::attempt(
            array(
                'email'    => Input::get('email'),
                'password' => Input::get('password')
            )
        )) {
            return Response::json(true);
        } else {
            return Response::json(false);
        }
    }

    public function logout() {
        Auth::logout();
        return Response::json(true);
    }

    public function saveMappings() {
        if (!Auth::check()) {
            return Response::json('AUTH_FAIL');
        }

        // If user is logged in, continue
        $saved_session              = new SavedSession();
        $saved_session->name        = Input::get('mapping_name');
        $saved_session->description = Input::get('description');
        $saved_session->user_id     = Auth::User()->id;

        // Manipulate fields before saving
        $mappings = array(
            'merge_fields'      => Input::get('merge_field'),
            'columns'           => Input::get('column'),
            'column_separators' => Input::get('column_separator'),
            'column_strippers'  => Input::get('column_stripper'),
        );

        $serialized_mappings     = serialize($mappings);
        $saved_session->mappings = $serialized_mappings;
        $saved_session->checksum = md5($serialized_mappings);
        $saved_session->save();

        return $saved_session->id;
    }

    public function showMappings() {
        if (!Auth::check()) {
            return Response::json('AUTH_FAIL');
        }

        // If user is logged in, continue
        $view_data = array(
            'sessions' => SavedSession::getSavedSessionsByUserId(Auth::User()->id)
        );
        return View::make('ajax/mappings_list')->with($view_data);
    }

    public function getMapping($mapping_id) {
        if (!Auth::check()) {
            return Response::json('AUTH_FAIL');
        }

        $saved_session = SavedSession::find($mapping_id);
        $saved_session->loadMappings();
        return View::make('ajax/individual_mapping')->with(array('session' => $saved_session));
    }
}
