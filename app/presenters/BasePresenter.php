<?php

namespace App\Presenters;

use App\Components\AgeControl;
use App\Model\Events;
use Nette;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var Events
     * @inject
     */
    public $eventsModel;

	/**
	 * @var Nette\DI\Container
	 * @inject
	 */
    public $diContainer;

	protected function beforeRender()
	{
		parent::beforeRender();
	}

	/**
	 * @return AgeControl
	 */
	public function createComponentAgeControl()
	{
		return new AgeControl(
			$this,
			$this->diContainer->parameters['ageControl']['cookieName'],
			$this->getHttpRequest(),
			$this->getHttpResponse()
		);
	}
}
