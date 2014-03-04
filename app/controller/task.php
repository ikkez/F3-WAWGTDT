<?php

namespace Controller;


class Task {

	protected
		$content = array();

	/** @var \View\HTML */
	protected $view;

	/** @var  \Model\Task */
	protected $task;


	public function __construct()
	{
		// init task model
		$this->task = new \Model\Task();
	}


	function get( \Base $f3 )
	{
		$this->content['content'] = $this->task->find(null,array('order'=>'id desc'));
	}


	function post( \Base $f3 )
	{
		if($f3->exists('POST.update')) {
			// update task list (not ajax method)
			$tasks = $this->task->find();
			foreach ($tasks as $task)
				if ($f3->exists('POST.'.$task->id, $tid)) {
					// check if task state is different
					if ($task->finished != ($tid == 'on')) {
						$task->finished = !$task->finished;
						$task->save();
					}
				} elseif ($task->finished) {
					// not in checked list
					$task->finished = false;
					$task->save();
				}
			$this->view->setMessage('Tasks updated');
		} else {
			// save new task
			if (!$f3->exists('POST.text', $text) || empty($text)) {
				$this->view->setError('Please enter a description to your task.');
				$this->get($f3);
				return;
			}
			$this->task->text = $text;
			$this->task->save();
			$this->view->setMessage('Your new task was saved!');
			if ($f3->get('AJAX')) {
				// render partial template
				$f3->set('item',$this->task);
				$itemView = \Template::instance()->render('templates/task.html');
				$this->content['item'] = $itemView;
				return;
			}
		}
		$f3->reroute('/list');
	}


	function safeLoad($id)
	{
		if (!isset($id) || !is_numeric($id))
			$this->view->setError('No valid Task ID.');
		else {
			$this->task->load(array('id = ?',$id));
			if ($this->task->dry())
				$this->view->setError('Task not found.');
			else
				return true;
		}
		return false;
	}


	function delete( \Base $f3, $args )
	{
		if($args['id'] <= 5)
			$this->view->setError('You are not allowed to delete the demo tasks.');
		elseif($this->safeLoad($args['id'])) {
			$this->task->erase();
			$this->view->setMessage('Task successfully deleted!');
		}
		$f3->reroute('/list');
	}


	function check( \Base $f3, $args )
	{
		if ($this->safeLoad($args['id'])) {
			$this->task->finished = true;
			$this->task->save();
		}
	}


	function uncheck( \Base $f3, $args )
	{
		if ($this->safeLoad($args['id'])) {
			$this->task->finished = false;
			$this->task->save();
		}
	}

	/**
	 * init the view
	 * @param \Base $f3
	 */
	function beforeroute( \Base $f3 )
	{
		if ($f3->get('AJAX'))
			$this->view = new \View\JSON();
		else
			$this->view = new \View\HTML();
	}


	/**
	 * feed the view and squeeze it out
	 * @param \Base $f3
	 */
	function afterroute( \Base $f3 )
	{
		$this->view->setData($this->content);
		echo $this->view->render();
	}

} 