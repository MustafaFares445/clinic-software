<?php

namespace App\Models;

use App\Traits\HasThumbnail;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;

final class Patient extends Model implements HasMedia
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory , HasThumbnail , HasUuids , SoftDeletes;

    protected $fillable = [
        'firstName',
        'lastName',
        'phone',
        'fatherName',
        'motherName',
        'nationalNumber',
        'address',
        'clinic_id',
        'notes',
        'birth',
        'gender',
    ];

    protected static function booted(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (Auth::check() && !$user->hasRole('super Admin')) {
            self::query()->whereRelation('clinic', 'id', request()->input('clinicId') ?? Auth::user()->clinic_id);
        }

        if ($user->hasAllRoles('doctor')) {
            self::query()
                ->whereRelation('records.doctors', 'doctor_id', Auth::id())
                ->orWhereRelation('reservations', 'doctor_id',  Auth::id());
        }
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class)->orderByDesc('start');
    }

    public function permanentIlls(): BelongsToMany
    {
        return $this->belongsToMany(Ill::class, 'ill_patient')->withPivot('notes');
    }

    public function permanentMedicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class, 'medicine_patient')->withPivot('notes');
    }
}
