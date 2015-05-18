<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExampleNoEdit extends Model {

	use SoftDeletes;

	protected $table = 'examples_no_edit';

}
