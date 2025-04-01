<?php

namespace App\Enums;

enum PROPERTY_TYPE: string
{
  case HOUSE     = 'house';
  case APARTMENT = 'apartment';
  case VILLA     = 'villa';
  case STUDIO    = 'studio';
  case LAND      = 'land';
  case COMMERCIAL = 'commercial';
  case OFFICE    = 'office';
  case GARAGE    = 'garage';
  case MANSION   = 'mansion';
}
