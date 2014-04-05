<?php

class SavedSession extends Eloquent{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'saved_sessions';

    // Mappings
    protected $unserialised_mappings = array();

    public function __construct() {
        $this->loadMappings();
    }

    /**
     * Lookup method to get all SavedSessions by user_id
     *
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    public static function getSavedSessionsByUserId($user_id) {
        return DB::table('saved_sessions')->where('user_id', $user_id)->get();
    }

    public function loadMappings() {
        $this->unserialised_mappings = unserialize($this->mappings);
    }

    public function getMergeFields() {
        return $this->unserialised_mappings['merge_fields'];
    }

    public function getColumns($index) {
        return $this->unserialised_mappings['columns'][$index];
    }

    public function getColumnSeparators($index) {
        return $this->unserialised_mappings['column_separators'][$index];
    }

    public function getColumnStrippers($index) {
        return $this->unserialised_mappings['column_stripper'][$index];
    }
}
