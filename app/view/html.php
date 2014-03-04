<?php

namespace View;

class HTML {

	protected
		$f3;

	public function __construct()
	{
		$this->f3 = \Base::instance();
	}

	public function setData($data)
	{
		$this->f3->mset($data);
	}

	function setError($msg)
	{
		$this->f3->set('SESSION.error',$msg);
	}

	function setMessage($msg)
	{
		$this->f3->set('SESSION.msg',$msg);
	}

	public function render()
	{
		$this->f3->copy('SESSION.msg','FLASH.msg');
		$this->f3->clear('SESSION.msg');

		$this->f3->copy('SESSION.error','FLASH.error');
		$this->f3->clear('SESSION.error');
		return \Template::instance()->render('templates/index.html');
	}
}