<?php namespace Zeropingheroes\Lanager\Domain\Events;

use Zeropingheroes\Lanager\Domain\ResourceService;

class EventService extends ResourceService
{

    protected $model = 'Zeropingheroes\Lanager\Domain\Events\Event';

    protected $orderBy = ['start'];

    protected $eagerLoad = ['type', 'eventSignups', 'eventSignups.user.state.application'];

    protected function readAuthorised()
    {
        return true;
    }

    protected function storeAuthorised()
    {
        return $this->user->hasRole('Events Admin');
    }

    protected function updateAuthorised()
    {
        return $this->user->hasRole('Events Admin');
    }

    protected function destroyAuthorised()
    {
        return $this->user->hasRole('Events Admin');
    }

    /**
     * Filter created date between two times
     * @param  DateTime $start
     * @param  DateTime $end
     * @return self
     */
    public function filterByEventType($eventTypeId)
    {
        $this->addFilter('where', 'event_type_id', $eventTypeId);

        return $this;
    }

    protected function validationRulesOnStore($input)
    {
        return [
            'name' => ['required', 'max:255', 'unique:events,name'],
            'start' => ['required', 'date_format:Y-m-d H:i:s', 'before:end'],
            'end' => ['required', 'date_format:Y-m-d H:i:s', 'after:start'],
            'event_type_id' => ['required', 'numeric', 'exists:event_types,id'],
            'signup_opens' => ['date_format:Y-m-d H:i:s', 'before:signup_closes', 'before:end'],
            'signup_closes' => ['date_format:Y-m-d H:i:s', 'after:signup_opens'],
            'published' => ['boolean'],
        ];
    }

    protected function validationRulesOnUpdate($input)
    {
        $rules = $this->validationRulesOnStore($input);

        // Exclude current event from uniqueness test
        $rules['name'] = ['required', 'max:255', 'unique:events,name,'.$input['id']];

        return $rules;
    }

    protected function domainRulesOnRead($input)
    {
        if (!$this->user->hasRole('Events Admin')) {
            $this->addFilter('where', 'published', true);
        }
    }
}