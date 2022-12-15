<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\BookingSubmittedMail as BookingSubmittedEmail;

use Illuminate\Support\Facades\DB;
use Mail;
use App\Event;

class BookingSubmittedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $event;

    public function __construct(Event $event)
    {
      $this->event = $event;
    }


    public function handle()
    {
      $mailSentTo = [];

      $storeMail = $event->store_email;
      $customerMail = $event->store_email;

      $details['booking_date'] = $event->booking_date;
      $details['time_slots'] = $event->time_slots;
      $details['no_of_kids'] = $event->no_of_kids;
      $details['store_name'] = $event->store_name;

      Mail::to($storeMail)->later(now(), new BookingSubmittedMail($details));
      $mailSentTo[] = $storeMail;

      Mail::to($customerMail)->later(now(), new BookingSubmittedMail($details));
      $mailSentTo[] = $customerMail;
    }
}
 