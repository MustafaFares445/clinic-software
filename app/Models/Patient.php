<?php

namespace App\Models;

use App\Trait\HasThumbnail;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Patient extends Model implements HasMedia
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory , HasThumbnail , SoftDeletes , HasUuids;

    protected $fillable = [
      'firstName',
      'lastName',
      'phone',
      'age',
      'fatherName',
      'motherName',
      'nationalNumber',
      'address',
      'clinic_id',
      'description',
    ];

    protected static function booted(): void
    {
        if (Auth::check() && !Auth::user()->hasRole('super admin') && !request()->has('clinicId'))
            self::query()->where('clinic_id' , Auth::user()->clinic_id);

        if (request()->has('clinicId'))
            self::query()->where('clinic_id' , request()->input('clinicId'));

       if (request()->has('clinicId') && Auth::user()->hasRole('doctor')){
           self::query()
               ->whereHas('records', function (Builder $query){
                   $query->where('doctor_id' , Auth::id());
               })
               ->orWhereHas('reservations', function (Builder $query){
                   $query->where('doctor_id' , Auth::id());
               });
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
        return $this->belongsToMany(Ill::class , 'ill_patient');
    }

    public function permanentMedicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class , 'medicine_patient');
    }
}
