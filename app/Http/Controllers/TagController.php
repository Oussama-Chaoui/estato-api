<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends CrudController
{
  protected $table = 'tags';
  protected $modelClass = Tag::class;
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
