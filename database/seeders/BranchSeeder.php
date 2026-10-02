<?php

namespace Database\Seeders;

use App\Http\Enums\BranchStatus;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::factory()->count(20)->create([
            'status' => BranchStatus::Active->value,
        ]);
    }
}