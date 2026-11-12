<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'user_id', 'tour_id', 'cantidad_personas', 'estado', 'observacion'
    ];

    public function turista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias para que funcione tanto ->user como ->turista
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}