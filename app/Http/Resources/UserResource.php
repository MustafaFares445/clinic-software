<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="UserResource",
 *     description="User resource representation",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the user",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="fullName",
 *         type="string",
 *         description="Full name of the user",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Email address of the user",
 *         example="john.doe@example.com"
 *     ),
 *     @OA\Property(
 *         property="username",
 *         type="string",
 *         description="Username of the user",
 *         example="johndoe"
 *     ),
 *     @OA\Property(
 *         property="avatar",
 *         ref="#/components/schemas/MediaResource",
 *         description="Avatar media resource"
 *     )
 * )
 */
final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this->id, $this->id),
            'fullName' => $this->when($this->fullName, $this->fullName),
            'email' => $this->when($this->email, $this->email),
            'username' => $this->when($this->username, $this->username),
            'avatar' => MediaResource::make($this->getFirstMedia('users')),
        ];
    }
}
