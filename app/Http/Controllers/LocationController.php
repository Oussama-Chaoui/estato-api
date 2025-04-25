<?php

namespace App\Http\Controllers;

use App\Models\Location;

class LocationController extends CrudController
{
  protected $table = 'locations';

  protected $modelClass = Location::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }
}
