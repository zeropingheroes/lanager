<?php namespace Zeropingheroes\Lanager\Domain;

use Zeropingheroes\Lanager\Domain\Users\EloquentServiceUserAdapter;
use Auth;
use Validator;
use DomainException;

abstract class ResourceService {

	/**
	 * The resource's model that the service will use
	 * @var string
	 */
	protected $model;

	/**
	 * User of the service
	 * @var object ServiceUserContract
	 */
	protected $user;

	/**
	 * Field to order results by
	 * @var array
	 */
	protected $orderBy = [ 'created_at' ];

	/**
	 * Filters to apply to read queries
	 * @var array
	 */
	protected $filters = [ ];

	/**
	 * Related resources to eager load
	 * @var array
	 */
	protected $eagerLoad = [ ];

	/**
	 * Set the user of the resource to no user initially
	 */
	public function __construct() {
		$this->user = new EloquentServiceUserAdapter( null );
	}

	/**
	 * Set the user of the resource
	 */
	protected function setUser() {
		$this->user = new EloquentServiceUserAdapter( Auth::user() );
	}

	private function newModelInstance()
	{
		return ( new $this->model );
	}

	/**
	 * Get all items of this resource
	 * @return Collection       Collection of items
	 */
	public function all()
	{
		$this->setUser();

		$this->runChecks( 'read' );

		return $this->get( $this->newModelInstance() );
	}

	/**
	 * Get a single resource item by its ID
	 * @param  integer   $id   Item ID
	 * @return BaseModel       Model of the item
	 */
	public function single( $id )
	{
		$this->setUser();
		$this->runChecks( 'read' );

		return $this->get( $this->newModelInstance(), $id );
	}

	/**
	 * Run query including eager load
	 * @param  integer                 $id   Item ID
	 * @return BaseModel|Collection    Query results
	 */
	protected function get( $model, $id = null )
	{
		if ( $this->filters )
			$model = $this->filter( $model );

		if ( $this->eagerLoad )
			$model = $model->with( $this->eagerLoad );

		if ( $id )
			return $model->where( 'id', $id )->firstOrFail();
		
		return $this->order( $model )->get();
	}

	/**
	 * Apply order by property to model
	 */
	protected function order( $model )
	{
		foreach ( $this->orderBy as $orderBy )
		{
			// only field name specified
			if ( count( $orderBy ) == 1 )
				$model = $model->orderBy( $orderBy );
			
			// field name and direction specified
			if ( count( $orderBy ) == 2 )
				$model = $model->orderBy( $orderBy[0], $orderBy[1] );
		}
		return $model;
	}

	public function addFilter()
	{
		$filter = func_get_args();
		$this->filters[] = $filter;
	}

	/**
	 * Apply filters to model
	 */
	protected function filter( $model )
	{
		foreach ( $this->filters as $filter )
		{
			if ( $filter[0] == 'where' AND count( $filter ) == 3 )
				$model = $model->where( $filter[1], $filter[2] );

			if ( $filter[0] == 'where' AND count( $filter ) == 4 )
				$model = $model->where( $filter[1], $filter[2], $filter[3] );

			if ( $filter[0] == 'whereIn' AND is_array( $filter[2] ) )
				$model = $model->whereIn( $filter[1], $filter[2] );

			if ( $filter[0] == 'whereBetween' AND is_array( $filter[2] ) )
				$model = $model->whereBetween( $filter[1], $filter[2] );
		}
		return $model;
	}

	/**
	 * Store a new resource item
	 * @param  array $input Raw user input
	 * @return boolean
	 */
	public function store( $input )
	{
		$this->setUser();

		$model = $this->newModelInstance();

		$model = $model->fill( $input );

		$this->runChecks( 'store', $model->toArray() );

		$model->save();

		return $model->toArray();
	}

	/**
	 * Update an existing resource item by ID
	 * @param  integer $id    Item's ID
	 * @param  array $input   Raw user input
	 * @return boolean
	 */
	public function update( $id, $input )
	{
		$this->setUser();

		$model = $this->get( $this->newModelInstance(), $id );
		
		$model = $model->fill( $input );

		$this->runChecks( 'update', $model->toArray(), $model->getOriginal() );

		$model->save();

		return $model->toArray();
	}

	/**
	 * Destroy an existing resource item by ID
	 * @param  integer $id    Item's ID
	 * @return boolean
	 */
	public function destroy( $id )
	{
		$this->setUser();

		$model = $this->get( $this->newModelInstance(), $id );

		$this->runChecks( 'destroy', $model->toArray() );

		$model->delete();

		return $model->toArray();
	}

	/**
	 * Check if the service permits a given action by calling
	 * authorisation, validation and domain rule checking methods
	 * @param  string  $action  Requested action to check
	 * @param  array   $input   Input data relevant to checks
	 * @return boolean          True for operation permitted, otherwise false
	 */
	public function permits( $action, $input = [] )
	{
		$this->setUser();

		try
		{
			$this->runChecks( $action, $input );

			// If no exceptions are thrown, the action is permitted
			return true;
		}
		// If an exception is thrown, the action is not permitted
		catch ( AuthorisationException $e )
		{
			return false;
		}
		catch ( DomainException $e )
		{
			return false;
		}
		catch ( ValidationException $e )
		{
			return false;
		}
	}

	/**
	 * Run required checks for given action
	 * @param  string  $action  Requested action to check
	 * @param  array   $input   Input data relevant to checks
	 */
	protected function runChecks( $action, $input = [], $original = [] )
	{
		// Perform a basic check to see if the current action is authorised
		// to be performed on this resource
		$this->checkAuthorisation( $action );

		// Run input validation on the input data
		$this->applyValidationRules( $action, $input );

		// Run domain rule validation on the input data
		$this->applyDomainRules( $action, $input, $original );

	}

	/**
	 * Check if given action is authorised on resource 
	 * @param  string  $action   Requested action to check
	 * @throws DomainException   If action is unathorised
	 */
	protected function checkAuthorisation( $action )
	{
		$authorisationCheckMethod = $action . 'Authorised'; // e.g. readAuthorised

		if ( ! $this->{ $authorisationCheckMethod }() )
			throw new AuthorisationException( 'You are not authorised to perform this action' );
	}

	/**
	 * Apply validation rules
	 * @param  string  $action      Requested action to check
	 * @param  array   $input       Input data
	 * @throws ValidationException  If input validation fails
	 */
	protected function applyValidationRules( $action, $input = [] )
	{
		if ( $action == 'read' OR $action == 'destroy' ) return;

		$validationRulesMethod = 'validationRulesOn' . $action;

		$rules = $this->{ $validationRulesMethod }( $input );
		
		$validation = Validator::make( $input, $rules );

		if ( $validation->fails() )
			throw new ValidationException( 'Validation failed', $validation->errors()->all() );
	}

	/**
	 * Apply domain rules
	 * @param  string  $action      Requested action to check
	 * @param  array   $input       Input data
	 * @param  array   $original    Original item before modification
	 */
	protected function applyDomainRules( $action, $input = [], $original = [] )
	{
		$domainRulesMethod = 'domainRulesOn' . ucfirst($action);

		// Pass in original model if we are updating
		if ( $action = 'update' )
		{
			$this->{ $domainRulesMethod }( $input, $original );
		}
		else
		{
			$this->{ $domainRulesMethod }( $input );
		}
	}

	/**
	 * Check if read operations are authorised on the service
	 * Typically redifined in subclasses
	 * @return boolean
	 */
	protected function readAuthorised()
	{
		return false;
	}

	/**
	 * Check if store operations are authorised on the service
	 * Typically redifined in subclasses
	 * @return boolean
	 */
	protected function storeAuthorised()
	{
		return false;
	}

	/**
	 * Check if update operations are authorised on the service
	 * Typically redifined in subclasses
	 * @return boolean
	 */
	protected function updateAuthorised()
	{
		return false;
	}

	/**
	 * Check if destroy operations are authorised on the service
	 * Typically redifined in subclasses
	 * @return boolean
	 */
	protected function destroyAuthorised()
	{
		return false;
	}

	/**
	 * Run rule checks that apply during resource read
	 * Typically redifined in subclasses
	 * @param  array $input     Input data to be used when evaluating rules
	 * @throws DomainException	when a rule is broken
	 */
	protected function domainRulesOnRead( $input ) { }

	/**
	 * Run rule checks that apply during resource read
	 * Typically redifined in subclasses
	 * @param  array $input     Input data to be used when evaluating rules
	 * @throws DomainException	when a rule is broken
	 */
	protected function domainRulesOnStore( $input ) { }

	/**
	 * Run rule checks that apply during resource read
	 * Typically redifined in subclasses
	 * @param  array $input     Input data to be used when evaluating rules
	 * @param  array $original  Original item data to be used when evaluating rules
	 * @throws DomainException	when a rule is broken
	 */
	protected function domainRulesOnUpdate( $input, $original ) { }

	/**
	 * Run rule checks that apply during resource read
	 * Typically redifined in subclasses
	 * @param  array $input     Input data to be used when evaluating rules
	 * @throws DomainException	when a rule is broken
	 */
	protected function domainRulesOnDestroy( $input ) { }

}