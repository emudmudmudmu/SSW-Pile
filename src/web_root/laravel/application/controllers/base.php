<?php

/**
 * コントローラー基底クラス
 *
 * $Author: mizoguchi $
 * $Rev: 16 $
 * $Date: 2013-09-16 16:22:19 +0900 (2013/09/16 (月)) $
 */
class Base_Controller extends Controller {

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters) {
		return Response::error('404');
	}

}
