<?php namespace BB\Repo;

class PolicyRepository {

    public function getByName($name) {
        return file_get_contents(storage_path().'/polices/'.$name.'.md');
    }
} 