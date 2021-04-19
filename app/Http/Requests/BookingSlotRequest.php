<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class BookingSlotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vehiclenumber' => 'required',
            'driver_type'   => ['required',Rule::in('male','female','pregnant','differently-abled')],
            'parking_number'=> 'exists:bookinglog,parkingnumber'
        ];
    }
}
