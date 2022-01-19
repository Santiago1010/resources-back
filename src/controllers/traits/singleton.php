<?php

namespace Src\Controllers\Traits;

trait Singleton {

	private array $data;
	private static $singleton = false;

	final private function __construct(array $data) {
		$this->data = $data;
		$this->init();
	}

	final public static function getInstance(array $data = []) {
		if (self::$singleton === false) {
			self::$singleton = new self($data);
		}

		return self::$singleton;
	}

	protected function init() {}

}