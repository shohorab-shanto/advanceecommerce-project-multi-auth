<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data) //uprer data eikhne pass korlam and this er maddhome access nilam and access newa data hobe uprer property data
    {
        $this->data = $data; //ei data er vitor stripe controller thke pass howa sob data aca
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() //stripe controller thke data ene $order variable e nilam
    {
        $order = $this->data; //those data are passing from stripe controller
        return $this->from('shohorabshanto@gmail.com')->view('mail.order_mail',compact('order'))->subject('Email From Shanto'); //from this mail email will be send to the user email
    }
}
