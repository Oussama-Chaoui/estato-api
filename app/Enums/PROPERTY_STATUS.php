<?php

namespace App\Enums;

enum PROPERTY_STATUS: string
{
  case FOR_SALE = 'for_sale';
  case FOR_RENT = 'for_rent';
  case SOLD     = 'sold';
  case RENTED   = 'rented';
}
