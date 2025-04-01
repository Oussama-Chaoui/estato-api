<?php

namespace App\Enums;

enum INQUIRY_STATUS: string
{
  case NEW = 'new';
  case CONTACTED = 'contacted';
  case CLOSED = 'closed';
}
