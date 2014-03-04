<?php

namespace View;

class JSON {

	protected $data;
	protected $msg;

	public function setData($data)
	{
		$this->data = $data;
	}

	function setError($msg)
	{
		\Base::instance()->error('400',$msg);
	}

	function setMessage($msg)
	{
		$this->msg = $msg;
	}

	public function render()
	{
		return json_encode(array_merge($this->data,array('msg'=>$this->msg)));
	}

} 