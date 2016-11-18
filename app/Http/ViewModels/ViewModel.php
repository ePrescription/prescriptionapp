<?php namespace App\Http\ViewModels;


abstract class ViewModel extends BaseViewModel {

	/**
	 * @var  string  active menu item
	 */
	protected $activeMenuItem = 'home';

	/**
	 * Breadcrumbs.
	 *
	 * @return  array
	 */
	public function breadcrumbs()
	{
		return [
			[
				'title' => 'Sample Application',
				'url'   => url('/'),
			],
		];
	}

	/**
	 * Main menu.
	 *
	 * @return  array
	 */
	public function mainMenu()
	{
		return [
			[
				'title'  => 'Home',
				'url'    => url('/'),
				'active' => ($this->activeMenuItem === 'home'),
			],
			[
				'title'  => 'Contact',
				'url'    => url('/contact'),
				'active' => ($this->activeMenuItem === 'contact'),
			],
		];
	}

}
