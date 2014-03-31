<?php

class SavedSession extends Eloquent{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'saved_sessions';

    /**
     * Lookup method to get all SavedSessions by user_id
     *
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    public static function getSavedSessionsByUserId($user_id) {
        return DB::table('saved_sessions')->where('user_id', $user_id)->get();
    }

}
