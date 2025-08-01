<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $birthdate
 * @property string $cpf
 * @property string|null $role
 * @property string|null $education
 * @property string $mother_name
 * @property string $email
 */
final class Paciente extends Model
{
    /** @use HasFactory<\Database\Factories\PacienteFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'birthdate',
        'cpf',
        'role',
        'education',
        'mother_name',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
    ];

    /**
     * Get the validation rules for the model.
     *
     * @return array<string, string>
     */
    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'cpf' => 'required|string|max:11|unique:pacientes,cpf',
            'role' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'mother_name' => 'required|string|max:255',
            'email' => 'required|email|unique:pacientes,email',
        ];
    }

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name.' '.$this->surname;
    }
}
