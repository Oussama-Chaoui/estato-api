<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends CrudController
{
  protected $table = 'categories';
  protected $modelClass = Category::class;
  protected $restricted = ['create', 'update', 'delete'];

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }
}
