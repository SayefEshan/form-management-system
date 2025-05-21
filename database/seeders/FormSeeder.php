<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a contact form
        Form::create([
            'title' => 'Contact Form',
            'method' => 'POST',
            'action' => '/contact',
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
                    'type' => 'textarea',
                    'name' => 'message',
                    'label' => 'Message',
                    'placeholder' => 'Enter your message',
                    'required' => true
                ]
            ],
            'is_active' => true
        ]);

        // Create a registration form
        Form::create([
            'title' => 'Registration Form',
            'method' => 'POST',
            'action' => '/register',
            'fields' => [
                [
                    'type' => 'text',
                    'name' => 'username',
                    'label' => 'Username',
                    'placeholder' => 'Choose a username',
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
                    'type' => 'select',
                    'name' => 'role',
                    'label' => 'Role',
                    'required' => true,
                    'options' => [
                        ['label' => 'User', 'value' => 'user'],
                        ['label' => 'Editor', 'value' => 'editor'],
                        ['label' => 'Contributor', 'value' => 'contributor']
                    ]
                ]
            ],
            'is_active' => true
        ]);

        // Create a survey form
        Form::create([
            'title' => 'Customer Survey',
            'method' => 'POST',
            'action' => '/survey',
            'fields' => [
                [
                    'type' => 'select',
                    'name' => 'satisfaction',
                    'label' => 'How satisfied are you with our service?',
                    'required' => true,
                    'options' => [
                        ['label' => 'Very Satisfied', 'value' => 'very_satisfied'],
                        ['label' => 'Satisfied', 'value' => 'satisfied'],
                        ['label' => 'Neutral', 'value' => 'neutral'],
                        ['label' => 'Dissatisfied', 'value' => 'dissatisfied'],
                        ['label' => 'Very Dissatisfied', 'value' => 'very_dissatisfied']
                    ]
                ],
                [
                    'type' => 'textarea',
                    'name' => 'feedback',
                    'label' => 'Additional Feedback',
                    'placeholder' => 'Please share your thoughts with us',
                    'required' => false
                ]
            ],
            'is_active' => true
        ]);
    }
}
