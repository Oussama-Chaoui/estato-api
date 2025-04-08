<?php

namespace App\Http\Controllers;

use App\Models\Property;

class PropertyController extends CrudController
{
  protected $table = 'properties';

  protected $modelClass = Property::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }
}
