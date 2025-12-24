<?php

namespace App\Enums;

enum PROPERTY_TYPE: string
{
  case HOUSE     = 'HOUSE';
  case APARTMENT = 'APARTMENT';
  case VILLA     = 'VILLA';
  case STUDIO    = 'STUDIO';
  case LAND      = 'LAND';
  case COMMERCIAL = 'COMMERCIAL';
  case OFFICE    = 'OFFICE';
  case GARAGE    = 'GARAGE';
  case MANSION   = 'MANSION';
}
