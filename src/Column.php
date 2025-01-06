<?php 
namespace Hermawan\DataTables;

class Column{

	public $key;
	public $alias;
	public $type = 'column';
	public $callback;
	public $searchable = TRUE;
	public $orderable = TRUE;
	public $escape = TRUE;
}

