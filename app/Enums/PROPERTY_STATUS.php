<?php

namespace App\Enums;

enum PROPERTY_STATUS: string
{
  case FOR_SALE = 'FOR_SALE';
  case FOR_RENT = 'FOR_RENT';
  case SOLD     = 'SOLD';
  case RENTED   = 'RENTED';
}
