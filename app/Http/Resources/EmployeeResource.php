<?php

// namespace App\Http\Resources;

// use Illuminate\Http\Resources\Json\JsonResource;

// class EmployeeResource extends JsonResource
// {
//     /**
//      * Transform the resource into an array.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
//      */
//     public function toArray($request)
//     {
//         return parent::toArray($request);
//     }
// }




namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request): array
    {
        $profile = $this->user?->profile;
        $job     = $this->jobProfile;

        return [
            'id'      => $this->id,
            'user_id' => $this->user_id,

            // ── User-derived identity
            'first_name'  => $profile?->first_name ?? '',
            'middle_name' => $profile?->middle_name ?? '',
            'last_name'   => $profile?->last_name ?? '',
            'full_name'   => $this->full_name,
            'initials'    => $this->initials,
            'email'       => $this->user?->email,

            // ── Employee core
            'status'      => $this->status?->label(),
            'status_value'=> $this->status?->value,
            'birthdate'   => optional($this->birthdate)->format('Y-m-d'),
            'date_hired'  => optional($this->date_hired)->format('Y-m-d'),
            'address'     => $this->address,
            'zip_code'    => $this->zip_code,

            // ── Job profile
            'designation'     => $job?->designation,
            'employment_type' => $job?->employment_type,
            'basic_salary'    => $job?->basic_salary,
            'allowance'       => $job?->allowance,
            'currency'        => $job?->currency,
            'phone'           => $job?->phone,
            'is_promoted'     => (bool) ($job?->is_promoted ?? false),

            // ── Relations (nested)
            'department' => $this->department?->id ? [
                'id'   => $this->department->id,
                'name' => $this->department->name,
            ] : null,

            'branch' => $job?->branch?->id ? [
                'id'   => $job->branch->id,
                'name' => $job->branch->name,
            ] : null,

            'country' => $this->country?->id ? ['id' => $this->country->id, 'name' => $this->country->name] : null,
            'state'   => $this->state?->id   ? ['id' => $this->state->id,   'name' => $this->state->name]   : null,
            'city'    => $this->city?->id    ? ['id' => $this->city->id,    'name' => $this->city->name]    : null,

            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}