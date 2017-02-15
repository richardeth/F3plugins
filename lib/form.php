<?php

/**
 * @package F3 form
 * @version 1.0
 * @author Jessica González <suki@missallsunday.com>
 * @copyright Copyright (c) 2017, Jessica González
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

class Form extends \Prefab
{
	public $options = [
		'group' => 'data',
		'prefix' => '',
		'type' => 'horizontal',
		'url' => '',
	];
	public $items = [];
	public $buttons = [];
	protected $_counter = 0;

	function __construct()
	{
		$this->f3 = \Base::instance();

		// Allow overwriting the default values.
		$this->options = $f3->exists('FORM') ? array_merge($this->options, $f3->get('FORM');
	}

	function setOptions($options = array())
	{
		$this->options = array_merge($this->_options, $options);
	}

	function button($button)
	{
		// No text? use the name as a text key then!
		if (empty($item['text']))
			$item['text'] = $this->f3->get($this->options['prefix'] . $item['name']);

		return $this->buttons[] = $button;
	}

	function element($item)
	{
		// No text? use the name as a text key then!
		if (empty($item['text']))
			$item['text'] = $this->f3->get($this->options['prefix'] . $item['name']);

		// Give it a chance to use a full text string.
		$item['desc']  = !empty($item['desc']) ? $item['desc'] : '';

		$item['id'] = 'form_'. $item['name'];

		// Normalize element.
		$item['html'] = str_replace([
			'{name}',
			'{id}',
			'{class}',
			'{extra}',
		], [
			'name="'. ($this->options['group'] ? $this->options['name'] .'['. $item['name'] .']' : $item['name']) .'"',
			'id="'. $item['id'] .'"',
			(!empty($item['class']) ? $item['class'] : ''),
			(!empty($item['extra']) ? $item['extra'] : ''),
		], $item['html']);

		return $this->items[++$this->_counter] = $item;
	}

	function addTextArea($item = array())
	{
		// Kinda needs this...
		if (empty($item) || empty($item['name']))
			return;

		$item['type'] = 'textarea';
		$item['value'] = empty($item['value']) ? '' : $item['value'];
		$rows = 'rows="'. (!empty($item['rows']) ? $item['size']['rows'] : 5) .'"';

		$item['html'] = '<'. $item['type'] .'  '. $rows .' class="form-control {class}" {name} {id} {extra}>'. $item['value'] .'</'. $item['type'] .'>';

		return $this->element($item);
	}

	function addHTML($item = array())
	{
		// Kinda needs this...
		if (empty($item) || empty($item['name']))
			return;

		$this->setParamValues($item);
		$item['type'] = 'html';

		return $this->element($item);
	}

	function addHiddenField($name, $value)
	{
		$item['type'] = 'hidden';
		$item['name'] = $name;
		$item['html'] = '<input type="'. $item['type'] .'" {name} {id} value="'. $value .'" />';
		return $this->element($item);
	}

	function addText($item = array())
	{
		// Kinda needs this...
		if (empty($item) || empty($item['name']))
			return;

		$item['type'] = 'text';

		$item['html'] = '<input type="'. $item['type'] .'" {name} {id} class="form-control {class}" {extra}>';

		return $this->element($item);
	}

	function addCheck($item = array())
	{
		// Kinda needs this...
		if (empty($item) || empty($item['name']))
			return;

		$item['type'] = 'checkbox';
		$item['checked'] = empty($item['checked']) ? '' : 'checked="checked"';

		$item['html'] = '<input type="'. $item['type'] .'" {name} {id} value="1" '. $item['checked'] .' class="{class}" {extra}>';

		return $this->element($item);
	}

	function addRadio($item = array())
	{
		// Kinda needs this...
		if (empty($item) || empty($item['name']))
			return;

		$item['type'] = 'radio';
		$item['checked'] = empty($item['checked']) ? '' : 'checked="checked"';
		$item['disabled'] = !empty($item['disabled']) ? 'disabled' : '';
		$item['value'] = !empty($item['value']) ? $item['value'] : '';
		$item['inline'] = !empty($item['inline']);

		$item['html'] = '<input type="'. $item['type'] .'" {name} {id} value="'. $item['value'] .'" '. $item['checked'] .' class="{class}" {extra}>';

		return $this->element($item);
	}

	function addButton($item = [])
	{
		$button = [
			'type' => 'input',
			'class' => 'btn-default',
			'value' => '',
			'extra' => '',
		];

		return $this->button(array_merge($button, $item));
	}

	function build($customVar = '')
	{
		$this->f3->set(($customVar ?: '_form'), [
			'options' => $this->options,
			'items' => $this->items,
			'buttons' => $this->buttons,
		]);
	}

	function getCounter()
	{
		return $this->_counter;
	}

	function getitems($id = 0)
	{
		return !empty($id) ? $this->items[$id] : $this->items;
	}

	function modifyElement($id = 0, $data = array())
	{
		if (empty($id) || empty($data) || empty($this->items[$id]))
			return false;

		$this->items[$id] = $data;
	}
}
