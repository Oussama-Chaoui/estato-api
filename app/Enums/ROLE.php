<?php

namespace App\Enums;

enum ROLE: string
{
  case USER = 'user';
  case ADMIN = 'admin';
  case AGENT = 'agent';
  case CLIENT = 'client';
}
