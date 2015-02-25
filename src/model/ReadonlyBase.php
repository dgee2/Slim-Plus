<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DGee2\SlimPlus\Model;

use DGee2\SlimPlus\App;

/**
 * Description of ReadonlyViewBase
 *
 * @author Dan
 */
abstract class ReadonlyBase {

	const ID = 'id';

	protected $data;

	/**
	 *
	 * @var \FluentPDO 
	 */
	private static $fluent;

	/**
	 * 
	 * @return \FluentPDO
	 */
	final protected static function fluent() {
		if (null !== self::$fluent) {
			return self::$fluent;
		}
		self::$fluent = new \FluentPDO(App::$pdo);
		return self::$fluent;
	}

	protected function __construct($data = []) {
		$this->data = $data;
	}

	protected function getData() {
		return $this->data;
	}

	protected function get($key) {
		if (is_array($key)) {
			return array_combine($key, array_map(array($this, "get"), $key));
		} else if (!isset($this->data[$key])) {
			return NULL;
		}
		return $this->data[$key];
	}

	protected function getIdField(){
		return self::ID;
	}

	public function getId() {
		return $this->get($this->getIdField());
	}

	public static function fromData($data) {
		if (!is_array($data)) {
			return new static($data);
		}
		$temp = [];
		foreach ($data as $row) {
			$temp[] = new static($row);
		}
		return $temp;
	}

	protected abstract function getTable();

	public function __get($name) {
		$function = "get" . ucfirst($name);
		return $this->$function();
	}

	public function getDBData() {
		return $this->get($this->getSaveColumns());
	}

	/**
	 * Return a list of all columns that are to be saved
	 * @return string[] List of all columns to be saved
	 */
	protected abstract function getSaveColumns();
}
