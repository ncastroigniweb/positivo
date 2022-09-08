<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redes extends Model
{
     public $table = 'socialnetcompanies';
    protected $fillable = [
        'nombre', 'url','Idcompanies'
    ]; 

  
}
