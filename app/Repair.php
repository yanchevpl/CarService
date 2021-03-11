<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $operation
 * @property string     $part_name
 * @property float      $price
 * @property float      $labor
 * @property string     $comment
 */
class Repair extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'repair';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serviceorder_id', 'operation', 'part_name', 'price', 'labor', 'comment'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'operation' => 'string', 'part_name' => 'string', 'price' => 'float', 'labor' => 'float', 'comment' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
