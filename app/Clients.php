<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    public $table='clients';


    protected $fillable = array('first_name', 'other_names', 'id_number', 'reg_number', 'gender', 'yob','reg_fee',
        'type',
        'phone', 'address','email',
        'keen_type', 'keen_name','keen_contact');
}
