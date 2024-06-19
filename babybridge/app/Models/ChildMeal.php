<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChildMeal extends Model
{
    use HasFactory;

    protected $table = 'child_meal';

    protected $fillable = [
        'child_id',
        'meal_id',
        'meal_time',
        'quantity',
        'notes',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function translateMealType($type)
    {
        switch ($type) {
            case 'feeding bottle':
                return 'biberon <i class="fas fa-bottle" style="color: blue;"></i>';
            case 'fruit':
                return '<i class="fas fa-apple-alt" style="color: red;"></i>';
            case 'vegetable':
                return '<i class="fas fa-carrot" style="color: orange;"></i>';
            default:
                return $type;
        }
    }

    public function translateQuantity($quantity)
    {
        switch ($quantity) {
            case 'full':
                return 'a tout mangé';
            case 'half':
                return 'a mangé la moitié';
            case 'quarter':
                return 'a mangé le quart';
            case 'refused':
                return 'a refusé de manger';
            default:
                return $quantity;
        }
    }

    public function formatForJournal()
    {
        $mealType = $this->translateMealType($this->meal->type);
        $quantity = $this->translateQuantity($this->quantity);

        $description = ($this->meal->type === 'feeding bottle')
            ? "{$this->child->firstname} a bu {$this->quantity} ml de son {$mealType}"
            : "{$this->child->firstname} {$quantity} de son repas {$mealType}";

        return [
            [
            'type' => 'meal',
            'time' => Carbon::parse($this->meal_time)->format('H:i'),
            'description' => "<i class='fas fa-fw fa-utensils' style='color : green'></i>" . $description,
            'child_name' => $this->child->getFullNameAttribute(),
            ],
        ];
    }
}
