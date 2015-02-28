<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DGee2\SlimPlus\Model;

/**
 * Description of TableBase
 *
 * @author Dan
 */
abstract class TableBase extends ReadonlyBase {

	private $savedData;

	protected function __construct($data = [], $retrieved = false) {
		parent::__construct($data);
		if ($retrieved) {
			$this->savedData = $data;
		}
	}

	protected function set($key, $value) {
		$this->data[$key] = $value;
		return $this;
	}

	protected function insert($table) {
		return static::fluent()->insertInto($table, $this->getDBData())->execute();
	}

	protected function update($table, $id) {
		$dbData = $this->getDBData();
		if (count($dbData) > 0) {
			if (is_array($id)) {
				$update = static::fluent()->update($table, $dbData);
				foreach ($id as $idKey => $idValue) {
					$update->where($idKey, $idValue);
				}
			} else {
				$update = static::fluent()->update($table, $dbData, $id);
			}
			return $update->execute();
		} else {
			return FALSE;
		}
	}

	public function save() {
		return $this->store($this->getTable());
	}

	protected function getStoreTable() {
		return $this->getTable();
	}

	protected final function store() {
		if (is_null($this->getId())) {
			$id = $this->insert($this->getStoreTable());
			$this->setID($id);
			$result = $id;
		} else {
			$result = $this->update($this->getStoreTable(), $this->getId());
		}
		if (is_numeric($result) && $result > 0) {
			$this->savedData = $this->data;
		}
		return $result;
	}

	protected function setID($value) {
		return $this->set(self::ID, $value);
	}

	public function __set($name, $value) {
		$function = "set" . ucfirst($name);
		return $this->$function($value);
	}

	public function saved() {
		$id = $this->getId();
		return !is_null($id) && is_numeric($id) && $id > 0 && count(array_diff($this->data, $this->savedData)) !== 0 && count(array_diff($this->savedData, $this->data)) !== 0;
	}

	public function reset() {
		$this->data = $this->savedData;
	}

	protected function getIdArray() {
		$id = $this->getId();
		if (is_array($id)) {
			return $id;
		} else {
			return [$this->getIdField() => $id];
		}
	}

	/**
	 * Delete the record from the store table that has this records id as its primary key
	 * @return PDOStatement
	 */
	protected function delete(){
		return $this->fluent()->delete($this->getStoreTable())->where($this->getIdArray())->execute();
	}
}
