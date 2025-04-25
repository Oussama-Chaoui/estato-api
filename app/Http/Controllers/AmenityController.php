<?php

namespace App\Http\Controllers;

use App\Models\Amenity;

class AmenityController extends CrudController
{
  protected $table = 'amenities';

  protected $modelClass = Amenity::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }
}
