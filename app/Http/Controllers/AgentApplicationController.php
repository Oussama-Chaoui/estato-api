<?php

namespace App\Http\Controllers;

use App\Models\AgentAppliance;

class AgentApplicationController extends CrudController
{
    protected $table = 'agents_appliances';
    protected $modelClass = AgentAppliance::class;
    protected $restricted = ['create', 'update', 'delete']; // Only allow read operations for now

    protected function getTable()
    {
        return $this->table;
    }

    protected function getModelClass()
    {
        return $this->modelClass;
    }
}
