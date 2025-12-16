<?php

namespace App\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Http\Request;
use Nette\Http\Response;

class AgeControl extends Control
{
	/**
	 * @var string
	 */
	private $cookieName;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * @param Presenter $presenter
	 * @param string $cookieName
	 * @param Request $request
	 * @param Response $response
	 */
	public function __construct(Presenter $presenter, $cookieName, Request $request, Response $response)
	{
		$this->cookieName = $cookieName;
		$this->request = $request;
		$this->response = $response;
//		$this->setParent($presenter);
	}


	public function render()
	{
		$this->template->showAgeControl = !$this->request->getCookie($this->cookieName);
		$this->template->setFile(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ageControl.latte');
		$this->template->render();
	}

	public function handleAgree()
	{
		$this->response->setCookie($this->cookieName, time(), 0);
		$this->getPresenter()->sendJson(['ageControl' => true]);
	}
}
