<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\ViewCache;

use ICanBoogie\View\View;

/**
 * Hashes a view.
 */
class HashView
{
	/**
	 * Hashes a view.
	 *
	 * @param View $view
	 *
	 * @return string
	 */
	public function __invoke(View $view)
	{
		return strtr(base64_encode(hash('sha384', json_encode($view), true)), [

			'-' => '+',
			'/' => '-'

		]);
	}
}
