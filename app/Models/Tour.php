<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'tours';
    
    protected $fillable = [
        'categoria_id', 'guia_id', 'titulo', 'slug', 'descripcion',
        'precio', 'duracion_horas', 'cupo_maximo', 'fecha_salida', 'imagen', 'activo'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'fecha_salida' => 'datetime',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function guia(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guia_id');
    }

    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, 'servicio_tour');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    // Scopes requeridos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Lógica de cálculo de cupos disponibles
    public function getCuposReservadosAttribute(): int
    {
        return $this->reservas()
                    ->where('estado', '!=', 'cancelada')
                    ->sum('cantidad_personas');
    }

    public function getCuposDisponiblesAttribute(): int
    {
        return max(0, $this->cupo_maximo - $this->cupos_reservados);
    }
}