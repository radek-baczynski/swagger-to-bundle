<?php

namespace AppBundle\Entity;

/**
 * Post
 */
class Post
{
	protected $id, $string, $title, $email;

	public function __get($name)
	{
		return $this->$name;
	}

	public function __set($name, $value)
	{
		$this->$name = $value;
	}
}

