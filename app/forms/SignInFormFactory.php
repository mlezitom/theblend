<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->addText('username', 'E-mail:')
			->setRequired('Please enter your username.')
            ->getControlPrototype()->class[] = 'form-control'
        ;

		$form->addPassword('password', 'Heslo:')
			->setRequired('Please enter your password.')
            ->getControlPrototype()->class[] = 'form-control'
        ;

		$form->addCheckbox('remember', 'Pamatovat');

		$form->addSubmit('send', 'Přihásit')
            ->getControlPrototype()->class[] = 'btn btn-success'
        ;

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$this->user->login($values->username, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('The username or password you entered is incorrect.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
