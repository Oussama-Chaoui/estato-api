<?php

namespace App\Enums;

enum AGENT_APPLICATION_STATUS: string
{
  case PENDING = 'PENDING';
  case APPROVED = 'APPROVED';
  case REJECTED = 'REJECTED';
}
