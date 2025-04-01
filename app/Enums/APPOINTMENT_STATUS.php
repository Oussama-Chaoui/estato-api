<?php

namespace App\Enums;

enum APPOINTMENT_STATUS: string
{
  case PENDING = 'pending';       // Appointment request submitted, waiting for confirmation.
  case CONFIRMED = 'confirmed';   // Appointment has been confirmed.
  case RESCHEDULED = 'rescheduled'; // Appointment was rescheduled.
  case ONGOING = 'ongoing';       // Appointment is currently in progress.
  case COMPLETED = 'completed';   // Appointment successfully completed.
  case NO_SHOW = 'no_show';       // Client didn't show up.
  case CANCELED = 'canceled';     // Appointment was canceled.
  case DECLINED = 'declined';     // Appointment request was declined.
}
