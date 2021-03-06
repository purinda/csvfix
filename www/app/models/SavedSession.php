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
        if (isset($this->unserialised_mappings['columns'][$index])) {
            return $this->unserialised_mappings['columns'][$index];
        }

        return array();
    }

    public function getColumnSeparators($index) {
        if ($this->unserialised_mappings['column_separators'][$index]) {
            return $this->unserialised_mappings['column_separators'][$index];
        }

        return array();
    }

    public function getColumnStrippers($index) {
        if ($this->unserialised_mappings['column_strippers'][$index]) {
            return $this->unserialised_mappings['column_strippers'][$index];
        }

        return array();
    }
}
