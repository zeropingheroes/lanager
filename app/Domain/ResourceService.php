<?php namespace Zeropingheroes\Lanager\Domain;

use Zeropingheroes\Lanager\Domain\Users\EloquentServiceUserAdapter;
use Auth;
use DomainException;

abstract class ResourceService {

	/**
	 * The resource's model object that the service will use
	 * @var object BaseModel
	 */
	protected $model;

	/**
	 * Validator class for the resource
	 * @var object ValidatorContract
	 */
	protected $inputValidator;

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
	 * Related resources to eager load
	 * @var array
	 */
	protected $eagerLoad = [ ];

	/**
	 * Set the resource's model and validator
	 * @param BaseModel                         $model           Resource's model
	 * @param InputValidatorContract            $inputValidator  Resource's input validator
	 */
	public function __construct(
		BaseModel $model,
		InputValidatorContract $inputValidator = null
	) {
		$this->model = $model;
		$this->inputValidator = $inputValidator;
		$this->user = new EloquentServiceUserAdapter( Auth::user() ); // TODO: extract out
	}

	/**
	 * Get all items of this resource
	 * @return Collection       Collection of items
	 */
	public function all()
	{
		$this->runChecks( 'read' );

		$this->order();

		return $this->get();
	}

	/**
	 * Get a single resource item by its ID
	 * @param  integer   $id   Item ID
	 * @return BaseModel       Model of the item
	 */
	public function single( $id )
	{
		$this->runChecks( 'read' );

		return $this->get( $id );
	}

	/**
	 * Run query including eager load
	 * @param  integer                 $id   Item ID
	 * @return BaseModel|Collection    Query results
	 */
	protected function get( $id = null )
	{
		$this->filter();

		if ( $this->eagerLoad )
			$this->model = $this->model->with( $this->eagerLoad );

		if ( $id )
			return $this->model->where( 'id', $id )->firstOrFail();
		
		return $this->model->get();
	}

	/**
	 * Apply order by property to query
	 */
	protected function order()
	{
		foreach ( $this->orderBy as $orderBy )
		{
			// only field name specified
			if ( count( $orderBy ) == 1 )
				$this->model = $this->model->orderBy( $orderBy );
			
			// field name and direction specified
			if ( count( $orderBy ) == 2 )
				$this->model = $this->model->orderBy( $orderBy[0], $orderBy[1] );
		}
	}

	/**
	 * Store a new resource item
	 * @param  array $input Raw user input
	 * @return boolean
	 */
	public function store( $input )
	{
		$this->model = $this->model->fill( $input );

		$this->runChecks( 'store', $this->model->toArray() );

		return $this->model->save();
	}

	/**
	 * Update an existing resource item by ID
	 * @param  integer $id    Item's ID
	 * @param  array $input   Raw user input
	 * @return boolean
	 */
	public function update( $id, $input )
	{
		$this->model = $this->get( $id );
		
		$this->model = $this->model->fill( $input );

		$this->runChecks( 'update', $this->model->toArray(), $this->model->getOriginal() );

		return $this->model->save();
	}

	/**
	 * Destroy an existing resource item by ID
	 * @param  integer $id    Item's ID
	 * @return boolean
	 */
	public function destroy( $id )
	{
		$this->model = $this->get( $id );

		$this->runChecks( 'destroy', $this->model->toArray() );

		return $this->model->delete();
	}

	/**
	 * Get id of resource
	 * @return mixed
	 */
	public function id()
	{
		// By default just return this resource's id
		if ( isset($this->model->id) ) return $this->model->id;
	}

	/**
	 * Check if the service permits a given action by calling
	 * authorisation, validation and business rule checking methods
	 * @param  string  $action  Requested action to check
	 * @param  array   $input   Input data relevant to checks
	 * @return boolean          True for operation permitted, otherwise false
	 */
	public function permits( $action, $input = [] )
	{
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

		// If input data is given
		if ( ! empty( $input ) )
		{
			// Run input validation on the input data
			$this->checkInputValidation( $action, $input );

			// Run business rule validation on the input data
			$this->checkRules( $action, $input, $original );
		}
	}

	/**
	 * Check if given action is authorised on resource 
	 * @param  string  $action   Requested action to check
	 * @throws DomainException   If action is unathorised
	 */
	protected function checkAuthorisation( $action )
	{
		$authCheckMethod = $action . 'Authorised'; // e.g. readAuthorised

		if ( ! $this->{ $authCheckMethod }() )
			throw new AuthorisationException( 'You are not authorised to perform this action' );
	}

	/**
	 * Run input validation using service input validator class
	 * @param  string  $action      Requested action to check
	 * @param  array   $input       Input data
	 * @throws ValidationException  If input validation fails
	 */
	protected function checkInputValidation( $action, $input = [] )
	{
		if ( $action == 'read' OR $action == 'destroy' ) return;

		$validation = $this->inputValidator->make( $input );
		$validation->scope( [ $action ] );
		$validation->bind( $input ); // provide all input data for use in rule definitions

		if ( $validation->fails() )
			throw new ValidationException( 'Validation failed', $validation->errors()->all() );
	}

	/**
	 * Check business rules will not be violated
	 * @param  string  $action      Requested action to check
	 * @param  array   $input       Input data
	 */
	protected function checkRules( $action, $input = [], $original = [] )
	{
		$ruleMethod = 'rulesOn' . ucfirst($action);

		// Pass in original model if we are updating
		if ( $action = 'update' )
		{
			$this->{ $ruleMethod }( $input, $original );
		}
		else
		{
			$this->{ $ruleMethod }( $input );
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
	protected function rulesOnRead( $input ) { }

	/**
	 * Run rule checks that apply during resource read
	 * Typically redifined in subclasses
	 * @param  array $input     Input data to be used when evaluating rules
	 * @throws DomainException	when a rule is broken
	 */
	protected function rulesOnStore( $input ) { }

	/**
	 * Run rule checks that apply during resource read
	 * Typically redifined in subclasses
	 * @param  array $input     Input data to be used when evaluating rules
	 * @param  array $original  Original item data to be used when evaluating rules
	 * @throws DomainException	when a rule is broken
	 */
	protected function rulesOnUpdate( $input, $original ) { }

	/**
	 * Run rule checks that apply during resource read
	 * Typically redifined in subclasses
	 * @param  array $input     Input data to be used when evaluating rules
	 * @throws DomainException	when a rule is broken
	 */
	protected function rulesOnDestroy( $input ) { }

	/**
	 * Filters to enforce during resource read
	 */
	protected function filter() {}

}