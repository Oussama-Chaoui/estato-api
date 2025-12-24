<?php

namespace App\Enums;

enum APPOINTMENT_STATUS: string
{
  case PENDING = 'PENDING';       // Appointment request submitted, waiting for confirmation.
  case CONFIRMED = 'CONFIRMED';   // Appointment has been confirmed.
  case RESCHEDULED = 'RESCHEDULED'; // Appointment was rescheduled.
  case ONGOING = 'ONGOING';       // Appointment is currently in progress.
  case COMPLETED = 'COMPLETED';   // Appointment successfully completed.
  case NO_SHOW = 'NO_SHOW';       // Client didn't show up.
  case CANCELED = 'CANCELED';     // Appointment was canceled.
  case DECLINED = 'DECLINED';     // Appointment request was declined.
}
