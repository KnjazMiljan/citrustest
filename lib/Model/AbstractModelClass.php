<?php

namespace Model;

use Exception;
use PDO;
use ReflectionClass;
use ReflectionException;

class AbstractModelClass
{
    /** @var string */
    protected $tableName;

    protected $fillableFields;

    private $pdo;

    /**
     * AbstractModelClass constructor.
     * @param PDO $pdo
     * @throws ReflectionException
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->tableName = $this->setTableName();
        $this->fillableFields = $this->setFillableFields();
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    private function setTableName() {
        return strtolower((new ReflectionClass($this))->getShortName());
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function setFillableFields() {
        $q = $this->pdo->prepare("DESCRIBE " . $this->tableName);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getFillableFields() {
        return $this->fillableFields;
    }

    /**
     * @param string $whereClause
     * @return array
     */
    public function getAll($whereClause = '') {

        $stmt = $this->pdo->query('SELECT * FROM ' . $this->tableName . ' ' . $whereClause);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param array $data
     * @param array $fieldsWithDefaults
     * @throws Exception
     */
    public function create(array $data, array $fieldsWithDefaults = []) {
        $fillableForCreation = $this->getFillableFieldsForCreation();
        $data['created_at'] = 'NOW()';
        $data['updated_at'] = 'NOW()';

        if($fieldsWithDefaults) {
            foreach ($fieldsWithDefaults as $fieldsWithDefault) {
                $arrSearchResult = array_search($fieldsWithDefault, $fillableForCreation);

                if(!!$arrSearchResult !== false) {
                    unset($fillableForCreation[$arrSearchResult]);
                }
            }
        }

        if (count($data) === count($fillableForCreation) && !array_diff($fillableForCreation, array_keys($data))) {
            foreach($fillableForCreation as $key)
            {
                if($key !== 'created_at' && $key !== 'updated_at') {
                    $data[$key] = "\"" . $data[$key]. "\"";
                }

                if($key === 'created_at' || $key === 'updated_at') {
                    $data[$key] = 'NOW()';
                }

            }

            $stmt = $this->pdo->prepare(
                'INSERT INTO `' . $this->tableName . '` (' . implode(', ', $fillableForCreation) .
                ') VALUES (' . implode(', ', array_values($data)) . ')');

            $stmt->execute();
        } else {
            throw new Exception('Invalid number of arguments');
        }
        return;
    }

    /**
     * @param $fieldName
     * @param $value
     * @param string $whereClause
     * @return void;
     */
    public function updateSingleField($fieldName, $value, $whereClause = '') {
        $q = $this->pdo->prepare("UPDATE `" . $this->tableName . "` SET `" . $fieldName . "` = " . $value . " " . $whereClause);
        $q->execute();
    }

    /**
     * @param int $id
     */
    public function delete($id) {
        $q = $this->pdo->prepare("DELETE FROM " . $this->tableName . " WHERE `id` = :id");
        $q->execute([
            ':id' =>  $id
        ]);
    }

    /**
     * @return array
     */
    private function getFillableFieldsForCreation() {
        $fillableForCreation = $this->getFillableFields();
        $arrSearchResult = array_search('id', $fillableForCreation);

        if($arrSearchResult !== false) {
            unset($fillableForCreation[$arrSearchResult]);
        }

        return $fillableForCreation;
    }

    private function getFillableFieldsForUpdate() {
        $fillableForCreation = $this->getFillableFields();
        unset($fillableForCreation['id']);
        unset($fillableForCreation['created_at']);
        return $fillableForCreation;
    }
}