<?php

namespace App\Exceptions;

class ValidationException extends \Exception
{

	private $_errors;
	
	private $_input;
	
	private $_invalid;

	protected $code = 400;

	public function __construct($errors, $code = 400, $input = null, $invalid = null)
	{
		parent::__construct($errors, $code);

		$this->_errors = $errors;
		$this->_input = $input;
		$this->_invalid = $invalid;
	}

	public function getErrors()
	{
		return $this->_errors;
	}

	public function getInput()
	{
		return $this->_input;
	}

	public function getInvalid()
	{
		return $this->_invalid;
	}

}