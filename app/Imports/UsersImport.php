<?php

namespace App\Imports;

use App\Http\Enums\ActiveStatus;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class UsersImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public array $created = [];
    public array $updated = [];
    public array $skipped = [];
    public array $errors = [];

    public function __construct(
        protected ?string $defaultRole = null,
        protected bool $updateExisting = false,
        protected bool $skipHeader = true,
    ) {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // header row + 1-based index

            try {
                $this->processRow($row->toArray(), $rowNumber);
            } catch (\Throwable $e) {
                $this->errors[] = ['row' => $rowNumber, 'message' => $e->getMessage()];
                Log::warning('[users-import] Row failed', [
                    'row' => $rowNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function processRow(array $row, int $rowNumber): void
    {
        $row = collect($row)
            ->mapWithKeys(fn($v, $k) => [Str::snake(trim((string) $k)) => is_string($v) ? trim($v) : $v])
            ->toArray();

        $username = $row['username'] ?? null;
        $email = $row['email'] ?? null;

        if (!$email && !$username) {
            $this->skipped[] = ['row' => $rowNumber, 'reason' => 'Missing username and email'];
            return;
        }

        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException("Invalid email: {$email}");
        }

        $user = User::query()
            ->when($email, fn($q) => $q->orWhere('email', $email))
            ->when($username, fn($q) => $q->orWhere('username', $username))
            ->first();

        if ($user && !$this->updateExisting) {
            $this->skipped[] = ['row' => $rowNumber, 'reason' => "User already exists: {$email}"];
            return;
        }

        DB::transaction(function () use ($row, $user, $rowNumber, $username, $email) {
            $status = $this->resolveStatus($row['status'] ?? null);
            $payload = [
                'username' => $username ?? Str::before((string) $email, '@'),
                'email' => $email,
                'status' => $status,
            ];

            if (!empty($row['password'])) {
                $payload['password'] = Hash::make($row['password']);
            }

            if ($user) {
                $user->update($payload);
                $this->updated[] = ['row' => $rowNumber, 'id' => $user->id];
            } else {
                $payload['password'] = $payload['password'] ?? Hash::make(Str::random(16));
                $user = User::create($payload);
                $this->created[] = ['row' => $rowNumber, 'id' => $user->id];
            }

            // Role assignment
            $roleName = $row['role'] ?? $this->defaultRole;
            if ($roleName) {
                if (Role::where('name', $roleName)->exists()) {
                    $user->syncRoles([$roleName]);
                } else {
                    $this->errors[] = ['row' => $rowNumber, 'message' => "Role not found: {$roleName}"];
                }
            }

            // Profile updates
            if ($user->profile) {
                $user->profile->update(array_filter([
                    'first_name' => $row['first_name'] ?? null,
                    'last_name' => $row['last_name'] ?? null,
                    'phone' => $row['phone'] ?? null,
                ], fn($v) => $v !== null));
            }
        });
    }

    protected function resolveStatus(mixed $raw): ActiveStatus
    {
        if ($raw === null || $raw === '')
            return ActiveStatus::Active;

        if (is_string($raw)) {
            $byName = collect(ActiveStatus::cases())
                ->first(fn($s) => strcasecmp($s->name, $raw) === 0);
            if ($byName)
                return $byName;
        }

        return ActiveStatus::tryFrom((int) $raw) ?? ActiveStatus::Active;
    }

    public function summary(): array
    {
        return [
            'created' => count($this->created),
            'updated' => count($this->updated),
            'skipped' => count($this->skipped),
            'errors' => count($this->errors),
            'details' => [
                'created' => $this->created,
                'updated' => $this->updated,
                'skipped' => $this->skipped,
                'errors' => $this->errors,
            ],
        ];
    }
}