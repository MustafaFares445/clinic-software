<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use Illuminate\Validation\Rule;
use App\Models\MedicalTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateBillingTransactionRequest",
 *     required={"type", "amount"},
 *     @OA\Property(
 *         property="clinicId",
 *         type="string",
 *         description="UUID of the clinic associated with the transaction"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the transaction (e.g., in, out)"
 *     ),
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         format="float",
 *         description="Transaction amount"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Optional description of the transaction",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="modelId",
 *         type="string",
 *         description="ID of the related model (medical transaction or reservation)"
 *     ),
 *     @OA\Property(
 *         property="modelType",
 *         type="string",
 *         enum={"medicalTransaction", "reservation"},
 *         description="Type of the related model"
 *     ),
 * )
 */
class CreateBillingTransactionRequest extends FormRequest
{
    private $types = [
        'medicalTransaction' => MedicalTransactions::class,
        'reservation' => Reservation::class
    ];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clinicId' => ['nullable', 'string' , Rule::exists('clinics' , 'id')],
            'type' => ['required', 'string', 'in:out,in'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'modelId' => ['required', 'string'],
            'modelType' => ['required', 'string', Rule::in(['reservation' , 'medicalTransaction'])],
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'clinic_id' => $this->input('clinicId') ?? Auth::user()->clinic_id,
            'user_id' => Auth::id(),
            'model_id' => $this->safe()->modelId,
            'model_type' => $this->types[$this->safe()->modelType],
        ]);
    }


    public function after() :array
    {
        return [
            function (Validator $validator){
                if($validator->errors()->any()) return;

                if($this->types[$this->safe()->modelType] === Reservation::class && Reservation::query()->where('id' , $this->safe()->modelId)->doesntExist()){
                    $validator->errors()->add(
                        'modelId',
                        'This reservation doe not exists in our records'
                    );
                }

                if($this->types[$this->safe()->modelType] === MedicalTransactions::class && MedicalTransactions::query()->where('id' , $this->safe()->modelId)->doesntExist()){
                    $validator->errors()->add(
                        'modelId',
                        'This medical transaction doe not exists in our records'
                    );
                }
            }
        ];
    }
}