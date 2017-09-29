<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Codeception\Lib\Driver;

use Codeception\Configuration;
use Codeception\Exception\ModuleException;
use PDO;

class Sqlite extends Db
{

    protected $hasSnapshot = false;

    protected $filename = '';

    protected $con = null;

    public function __construct($dsn, $user, $password)
    {
        $filename = substr($dsn, 7);
        if ($filename === ':memory:') {
            throw new ModuleException(__CLASS__, ':memory: database is not supported');
        }

        $this->filename = Configuration::projectDir() . $filename;
        $this->dsn = 'sqlite:' . $this->filename;
        parent::__construct($this->dsn, $user, $password);
    }

    /**
     * @return void
     */
    public function cleanup()
    {
        $this->dbh = null;
        file_put_contents($this->filename, '');
        $this->dbh = self::connect($this->dsn, $this->user, $this->password);
    }

    /**
     * @return void
     */
    public function load($sql)
    {
        if ($this->hasSnapshot) {
            $this->dbh = null;
            file_put_contents($this->filename, file_get_contents($this->filename . '_snapshot'));
            $this->dbh = new PDO($this->dsn, $this->user, $this->password);
        } else {
            if (file_exists($this->filename . '_snapshot')) {
                unlink($this->filename . '_snapshot');
            }
            parent::load($sql);
            copy($this->filename, $this->filename . '_snapshot');
            $this->hasSnapshot = true;
        }
    }

    /**
     * @param string $tableName
     *
     * @return array[string]
     */
    public function getPrimaryKey($tableName)
    {
        if (!isset($this->primaryKeys[$tableName])) {
            if ($this->hasRowId($tableName)) {
                return $this->primaryKeys[$tableName] = ['_ROWID_'];
            }

            $primaryKey = [];
            $query = 'PRAGMA table_info(' . $this->getQuotedName($tableName) . ')';
            $stmt = $this->executeQuery($query, []);
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($columns as $column) {
                if ($column['pk'] !== '0') {
                    $primaryKey[] = $column['name'];
                }
            }

            $this->primaryKeys[$tableName] = $primaryKey;
        }

        return $this->primaryKeys[$tableName];
    }

    /**
     * @param $tableName
     *
     * @return bool
     */
    private function hasRowId($tableName)
    {
        $params = ['type' => 'table', 'name' => $tableName];
        $select = $this->select('sql', 'sqlite_master', $params);
        $result = $this->executeQuery($select, $params);
        $sql = $result->fetchColumn(0);
        return strpos($sql, ') WITHOUT ROWID') === false;
    }

}
