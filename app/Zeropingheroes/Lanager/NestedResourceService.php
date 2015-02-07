<?php namespace Zeropingheroes\Lanager;

use InvalidArgumentException, ReflectionClass;

abstract class NestedResourceService extends BaseResourceService {

	protected $models;

	public function __construct( $listener, $models )
	{
		foreach( $models as $model )
		{
			if( ! ($model instanceof BaseModel) ) throw new InvalidArgumentException('Given model hierachy instances must extend BaseModel');
		}

		$this->models = $models;
		parent::__construct($listener);
	}

	private function nestedFindOrFail(array $ids)
	{
		$idCount = count($ids);
		$expectedIdCount = (count($this->models)-1);
		if( $idCount != $expectedIdCount ) throw new InvalidArgumentException('Expected ' . $expectedIdCount.' but '. $idCount .' given' );

		$i = 0;
		foreach( $ids as $id )
		{
			if( $i == 0 )
			{
				// find first model in the hierarchy
				$model = $this->models[$i]->findOrFail($id);

				// get method name of second model in the hierachy
				$children = str_plural((new ReflectionClass($this->models[$i+1]))->getShortName());

				// fetch its children
				$model = $model->{$children}();
			}
			else
			{
				// find subsequent models in the hierarchy
				$model = $model->findOrFail($id);
			}
			$i++;
		}
		return $model;
	}

	public function all()
	{
		$ids = func_get_args();

		return $this->nestedFindOrFail( $ids )->get();
	}

	public function single()
	{
		$args = func_get_args();
		$itemId = array_pop($args); // last argument is item id
		$parentIds = $args; // remaining arguments are item's parent(s)

		return $this->nestedFindOrFail( $parentIds )->findOrFail($itemId);
	}

}