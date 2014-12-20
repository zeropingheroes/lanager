<?php namespace Zeropingheroes\Lanager;

interface ResourceServiceContract {

	public function all();

	public function single($id);

	public function store($input);

	public function update($id, $input);

	public function destroy($id);
}