<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Example extends Model {

	use SoftDeletes;

	protected $table = 'carriers';

}
