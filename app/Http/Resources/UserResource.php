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
 *         type="string",
 *         description="Unique identifier for the user",
 *         example=1232132432
 *     ),
 *     @OA\Property(
 *         property="firstName",
 *         type="string",
 *         description="First name of the user",
 *         example="John"
 *     ),
 *    @OA\Property(
 *         property="lastName",
 *         type="string",
 *         description="Last name of the user",
 *         example="Doe"
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
 *         example="john"
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
            'firstName' => $this->when($this->firstName, $this->firstName),
            'lastName' => $this->when($this->lastName, $this->lastName),
            'email' => $this->when($this->email, $this->email),
            'username' => $this->when($this->username, $this->username),
            'avatar' => MediaResource::make($this->getFirstMedia('users')),
            'roles' => $this->whenLoaded('roles' , $this->getRoleNames()),
        ];
    }
}
