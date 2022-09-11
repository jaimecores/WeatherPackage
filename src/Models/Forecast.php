<?php

namespace JaimeCores\WeatherPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'location',
        'date',
        'forecast'
    ];

    public function guests()
    {
        return $this->hasMany('JaimeCores\WeatherPackage\Models\Guest');
    }
}