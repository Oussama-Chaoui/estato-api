<?php

namespace App\Enums;

enum INQUIRY_STATUS: string
{
  case NEW = 'NEW';
  case CONTACTED = 'CONTACTED';
  case CLOSED = 'CLOSED';
}
