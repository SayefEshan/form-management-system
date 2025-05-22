<?php

namespace Database\Factories;

use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'method' => $this->faker->randomElement(['POST', 'GET']),
            'action' => $this->faker->url,
            'fields' => [
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Full Name',
                    'placeholder' => 'Enter your full name',
                    'required' => true
                ],
                [
                    'type' => 'email',
                    'name' => 'email',
                    'label' => 'Email Address',
                    'placeholder' => 'Enter your email address',
                    'required' => true
                ],
                [
                    'type' => 'password',
                    'name' => 'password',
                    'label' => 'Password',
                    'placeholder' => 'Enter your password',
                    'required' => true
                ],
                [
                    'type' => 'textarea',
                    'name' => 'message',
                    'label' => 'Message',
                    'placeholder' => 'Enter your message',
                    'required' => false
                ]
            ],
            'is_active' => true
        ];
    }
}
