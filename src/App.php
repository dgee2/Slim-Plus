<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DGee2\SlimPlus;

use Exception;

/**
 * Description of Application
 *
 * @author Dan
 */
final class App {

	/**
	 *
	 * @var \Slim\Slim 
	 */
	protected $slim;

	/**
	 *
	 * @var \PDO 
	 */
	public static $pdo;

	/**
	 *
	 * @var string 
	 */
	public static $secret;

	/**
	 * 
	 * @var string
	 */
	public static $templatePath = "../views";
	
	/**
	 * Singleton access
	 * @return \App
	 */
	public static function Instance() {
		static $inst = null;
		if (is_null($inst)) {
			if (!isset(self::$secret)) {
				throw new Exception("secret has not been set");
			}
			if (!isset(self::$pdo)) {
				throw new Exception("PDO has not been set");
			}
			$inst = new App();
			// Load routes
			require_once '../routes.php';
		}
		return $inst;
	}

	private function __construct() {
		// Prepare app
		$this->slim = new \Slim\Slim(array(
			'templates.path' => self::$templatePath,
		));

		// Prepare view
		$this->slim->view(new \Slim\Views\Twig());
		$this->slim->view->parserOptions = array(
			'charset' => 'utf-8',
			'auto_reload' => true,
			'strict_variables' => false,
			'autoescape' => true
		);
		$this->slim->view->parserExtensions = array(new \Slim\Views\TwigExtension());
		$this->slim->add(new \Slim\Middleware\SessionCookie([
			'expires' => '20 minutes',
			'secure' => TRUE,
			'httponly' => TRUE,
			'secret' => self::$secret,
			'cipher' => MCRYPT_RIJNDAEL_256,
			'cipher_mode' => MCRYPT_MODE_CBC
		]));
	}

	/**
	 * Get the slim instance for the application
	 * @return \Slim\Slim
	 */
	public static function slim() {
		return self::Instance()->slim;
	}

	public static function run() {
		return self::slim()->run();
	}

}
