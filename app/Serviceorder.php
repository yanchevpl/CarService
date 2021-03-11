<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $trouble
 * @property int        $status
 * @property string     $schedule
 * @property string     $comment
 */
class Serviceorder extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'serviceorder';

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
        'car_id', 'trouble', 'status', 'schedule', 'note'
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
        'trouble' => 'string', 'status' => 'int', 'schedule' => 'string', 'note' => 'string'
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
    public static function viewClients()
    {
        return self::select(
            'serviceorder.id as orderid',
            'users.name as name',
            'cars.make as make',
            'cars.model as model',
            'cars.make_year as make_year',
            'serviceorder.schedule as schedule',
            'servicestatus.title as title'
        )->join('cars','cars.id','=','car_id')
            ->join('users','users.id','=','cars.user_id')
            ->join('servicestatus','servicestatus.id','=','serviceorder.status');
    }
}
