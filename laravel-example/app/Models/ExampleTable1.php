<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExampleTable1 extends Model {

	use SoftDeletes;

	protected $table = 'examples_table_1';

}
