<?php

namespace App\Enums;

enum POST_STATUS: string
{
  case DRAFT = 'DRAFT';
  case PUBLISHED = 'PUBLISHED';
  case ARCHIVED = 'ARCHIVED';
}
