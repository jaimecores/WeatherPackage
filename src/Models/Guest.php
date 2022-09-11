<?php

namespace JaimeCores\WeatherPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'ip',
        'datetime',
        'forecast_id'
    ];

    public function forecast()
    {
        return $this->belongsTo('JaimeCores\WeatherPackage\Models\Forecast');
    }
}