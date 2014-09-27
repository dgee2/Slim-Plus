<?php

/*
 * Contains the BaseController class which provides common functionality to controllers
 */

namespace DGee2\SlimPlus\Controller;

use DGee2\SlimPlus\App;

/**
 * Abstract class to provide common functionality to controllers
 *
 * @author Dan
 */
abstract class BaseController {

	/**
	 * Render a template
	 *
	 * Call this method within a GET, POST, PUT, PATCH, DELETE, NOT FOUND, or ERROR
	 * callable to render a template whose output is appended to the
	 * current HTTP response body. How the template is rendered is
	 * delegated to the current View.
	 *
	 * @param  string $template The name of the template passed into the view's render() method
	 * @param  array  $data     Associative array of data made available to the view (optional)
	 * @param  int    $status   The HTTP response status code to use (optional)
	 */
	protected static function renderPage($template, $data = array(), $status = null) {
		$data['slim'] = App::slim();
		App::slim()->render($template, $data, $status);
	}

}
