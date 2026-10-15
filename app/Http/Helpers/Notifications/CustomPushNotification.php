<?php

namespace App\Http\Helpers\Notifications;

use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class CustomPushNotification
{
    /**
     * Map each model and action to its handler.
     */
    protected array $handlers = [
        User::class => [
            'created' => 'userCreated',
            'updated' => 'userUpdated',
            'deleted' => 'userDeleted',
        ],

        // Register other models here.
        // Product::class => [
        //     'created' => 'productCreated',
        //     'updated' => 'productUpdated',
        //     'deleted' => 'productDeleted',
        // ],
    ];

    /**
     * Generic notification entry point.
     */
    public function send(
        string $action,
        Model $model,
        array $options = []
    ): void {
        $action = strtolower($action);

        // Normalize action names.
        $action = match ($action) {
            'create' => 'created',
            'update' => 'updated',
            'delete' => 'deleted',
            default => $action,
        };

        $modelClass = $model::class;

        // Check whether this model supports the action.
        $handler = $this->handlers[$modelClass][$action] ?? null;

        if (!$handler) {
            throw new InvalidArgumentException(
                "Notification handler not configured for {$modelClass} and {$action}."
            );
        }

        // Dynamically invoke the model-specific handler.
        $this->{$handler}($model, $options);
    }

    /**
     * User created.
     */
    protected function userCreated(
        User $user,
        array $options = []
    ): void {
        $firstName = $user->profile?->first_name ?? $user->username;
        $lastName = $user->profile?->last_name ?? '';

        $data = array_merge([
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'roles' => $user->getRoleNames()->toArray(),
            'created_by' => auth()->id(),
        ], $options['data'] ?? []);

        // Notify the user.
        Notification::create([
            'user_id' => $user->id,
            'audience' => 'user',
            'type' => 'user.created',
            'title' => 'Welcome to the System!',
            'message' => "Hello {$firstName}, your account has been created successfully.",
            'category' => 'success',
            'icon' => 'heroicon-o-user-plus',
            'action_url' => route('users.show', $user->id),
            'action_label' => 'View Your Profile',
            'priority' => 'normal',
            'data' => $data,
        ]);

        // Notify admins.
        NotificationService::toRole(
            roleName: 'admin',
            type: 'user.created',
            title: 'New User Created',
            message: "{$firstName} {$lastName} ({$user->email}) was created.",
            options: [
                'category' => 'success',
                'icon' => 'heroicon-o-user-plus',
                'action_url' => route('users.show', $user->id),
                'action_label' => 'View User',
                'priority' => 'normal',
                'related_type' => User::class,
                'related_id' => $user->id,
                'data' => $data,
            ]
        );
    }

    /**
     * User updated.
     */
    protected function userUpdated(
        User $user,
        array $options = []
    ): void {
        NotificationService::toRole(
            roleName: 'admin',
            type: 'user.updated',
            title: 'User Updated',
            message: "{$user->username} ({$user->email}) was updated.",
            options: [
                'category' => 'info',
                'icon' => 'heroicon-o-pencil-square',
                'action_url' => route('users.show', $user->id),
                'action_label' => 'View User',
                'priority' => 'normal',
                'related_type' => User::class,
                'related_id' => $user->id,
                'data' => [
                    'user_id' => $user->id,
                    'updated_by' => auth()->id(),
                ],
            ]
        );
    }

    /**
     * User deleted.
     */
    protected function userDeleted(
        User $user,
        array $options = []
    ): void {
        NotificationService::toRole(
            roleName: 'admin',
            type: 'user.deleted',
            title: 'User Deleted',
            message: "User {$user->username} ({$user->email}) was deleted.",
            options: [
                'category' => 'warning',
                'icon' => 'heroicon-o-trash',
                'priority' => 'normal',
                'related_type' => User::class,
                'related_id' => $user->id,
                'data' => [
                    'user_id' => $user->id,
                    'deleted_by' => auth()->id(),
                ],
            ]
        );
    }
}