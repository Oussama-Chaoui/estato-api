<?php

namespace App\Enums;

enum ROLE: string
{
  case ADMIN = 'ADMIN';
  case AGENT = 'AGENT';
  case CLIENT = 'CLIENT';
}
