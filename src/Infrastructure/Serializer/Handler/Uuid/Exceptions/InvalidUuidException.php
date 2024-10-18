<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Handler\Uuid\Exceptions;

class InvalidUuidException
	extends \Exception
	implements \Mhujer\JmsSerializer\Uuid\Exception
{

	private string $invalidUuid;

	public function __construct(string $invalidUuid, ?\Throwable $exception = null)
	{
		parent::__construct(
			sprintf('"%s" is not a valid UUID', $invalidUuid),
			0,
			$exception
		);
		$this->invalidUuid = $invalidUuid;
	}

	public function getInvalidUuid(): string
	{
		return $this->invalidUuid;
	}

}
