<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'job_profiles';

    protected $fillable = [
        'employment_type',
        'designation',
        'department_id',
        'calculated_years',
        'is_promoted',
        'last_promotion_date',
        'basic_salary',
        'allowance',
        'currency',
        'bank_account',
        'national_id',
        'passport_number',
        'nationality',
        'marital_status',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'address',
        'postal_code',
        'user_id',
        'branch_id',
        'notes',
        'meta',
    ];

    protected $casts = [
        'is_promoted'         => 'boolean',
        'calculated_years'    => 'integer',
        'basic_salary'        => 'decimal:2',
        'allowance'           => 'decimal:2',
        'last_promotion_date' => 'date',
        'meta'                => 'array',
    ];

    // ═══════════════════════════════════════════════════════════
    // RELATIONS
    // ═══════════════════════════════════════════════════════════

    public function employee()
    {
        return $this->hasOne(Employee::class, 'job_profile_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withDefault();
    }
}