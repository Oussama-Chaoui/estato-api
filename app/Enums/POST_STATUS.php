<?php

namespace App\Enums;

enum POST_STATUS: string
{
  case DRAFT = 'draft';
  case PUBLISHED = 'published';
  case ARCHIVED = 'archived';
}
