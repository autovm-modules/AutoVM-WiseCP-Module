<?php

class PlusAutoVM extends AddonModule
{
    public $version = 'dev';

    public function __construct()
    {
        $this->name = __CLASS__;

        parent::__construct();
    }

    public function activate()
    {
        $path = dirname(__FILE__);

        // User table
        $query = file_get_contents($path . '/user.sql');

        WDB::query($query);

        // Order table
        $query = file_get_contents($path . '/order.sql');

        WDB::query($query);

        return true;
    }

    public function deactivate()
    {
        return true;
    }

    public function fields()
    {
        return [];
    }

    public function save_fields($fields=[])
    {
        return true;
    }

    public function adminArea()
    {
        return null;
    }

    public function clientArea()
    {
        return null;
    }

    public function main()
    {
        return [];
    }
}
