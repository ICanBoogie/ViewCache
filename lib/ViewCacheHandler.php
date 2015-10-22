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

use ICanBoogie\Storage\Storage;
use ICanBoogie\View\View;

class ViewCacheHandler
{
	private $storage;

	/**
	 * @var callable
	 */
	private $view_hasher;

	/**
	 * @param Storage $storage
	 * @param callable $view_hasher
	 */
	public function __construct(Storage $storage, callable $view_hasher)
	{
		$this->storage = $storage;
		$this->view_hasher = $view_hasher;
	}

	/**
	 * Handles a `ICanBoogie\View\View::render:before` event.
	 *
	 * @param View\BeforeRenderEvent $event
	 * @param View $view
	 */
	public function __invoke(View\BeforeRenderEvent $event, View $view)
	{
		$key = $this->hash($view);
		$storage = $this->storage;
		$result = $storage->retrieve($key);

		if ($result !== null)
		{
			$event->result = $result;

			return;
		}

		$event->result = $result = $view->render();

		$storage->store($key, $result);
		$event->stop();
	}

	/**
	 * Hashes a view.
	 *
	 * @param View $view
	 *
	 * @return string
	 */
	protected function hash(View $view)
	{
		$view_hasher = $this->view_hasher;

		return $view_hasher($view);
	}
}
