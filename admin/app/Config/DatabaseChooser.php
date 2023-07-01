<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;
use Exception;
use InvalidArgumentException;

class DatabaseChooser extends BaseConfig
{
    protected $dbName;

    public function __construct(string $dbName = '')
    {
        $this->dbName = $dbName;
        parent::__construct();

        $config = $this->buildArr();
        $this->defaultGroup = $config;
    }

    private function buildArr()
    {
        if (empty($this->dbName)) {
            throw new InvalidArgumentException('dbName cannot be empty');
        }

        $default = $this->defaultGroup;

        if (isset($this->$default) && is_array($this->$default)) {
            return array_replace($this->$default, ['database' => $this->dbName]);
        } else {
            throw new Exception('Invalid configuration.');
        }
    }
}
