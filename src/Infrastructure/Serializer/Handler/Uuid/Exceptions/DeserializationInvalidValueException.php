<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Handler\Uuid\Exceptions;

class DeserializationInvalidValueException
	extends \Exception
	implements \Mhujer\JmsSerializer\Uuid\Exception
{

	private string $fieldPath;

	public function __construct(string $fieldPath, \Throwable $exception)
	{
		parent::__construct(
			sprintf('Invalid value in field %s: %s', $fieldPath, $exception->getMessage()),
			0,
			$exception
		);
		$this->fieldPath = $fieldPath;
	}

	public function getFieldPath(): string
	{
		return $this->fieldPath;
	}

}
