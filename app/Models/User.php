<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Clinic;
use App\Trait\HasThumbnail;
use App\Models\Specification;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Models\Role;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read string $id
 * @property-read string $uuid
 * @property-read string $firstName
 * @property-read string $lastName
 * @property-read string $email
 * @property-read string $username
 * @property-read string $clinic_id
 * @property-read bool $is_banned
 * @property-read CarbonImmutable|null $email_verified_at
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable|null $updated_at
 * @property-read Clinic $clinic
 * @property-read Collection<Clinic> $doctorClinics
 * @property-read Collection<Specification> $doctorSpecifications
 * @property-read Collection<Role> $roles
 * @property-read Collection<Permission> $permissions
 * @property-read Collection<DatabaseNotification> $notifications
 * @property-read Collection<PersonalAccessToken> $tokens
 */
final class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory , HasRoles , HasThumbnail , HasUuids , Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'username',
        'clinic_id',
        'is_banned',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot function from Laravel.
     * Adds a global scope to filter users by clinic_id for non-super admin users.
     */
    protected static function booted(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (Auth::check() && ! $user->hasRole('super admin')) {
            self::query()->where('clinic_id', Auth::user()->clinic_id);
        }
    }

    /**
     * Get the clinic that the user belongs to.
     *
     * @return BelongsTo<Clinic, User>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    /**
     * Get the clinics where the user works as a doctor.
     *
     * @return BelongsToMany<Clinic>
     */
    public function doctorClinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class, 'clinic_doctor', 'doctor_id', 'clinic_id');
    }

    /**
     * Get the medical specifications/specialties of the doctor.
     *
     * @return BelongsToMany<Specification>
     */
    public function doctorSpecifications(): BelongsToMany
    {
        return $this->belongsToMany(Specification::class, 'doctor_specification', 'doctor_id', 'specification_id');
    }
}
