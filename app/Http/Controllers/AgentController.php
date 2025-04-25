<?php

namespace App\Http\Controllers;

use App\Models\Agent;

class AgentController extends CrudController
{
  protected $table = 'agents';

  protected $modelClass = Agent::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }
}
