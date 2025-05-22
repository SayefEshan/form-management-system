<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FormTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create(['is_admin' => false]);
    }

    #[Test]
    public function it_can_list_forms()
    {
        $this->actingAs($this->admin);

        $forms = Form::factory()->count(3)->create();

        $response = $this->get(route('forms.index'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn($assert) => $assert
                ->component('Forms/Index')
                ->has('forms', 3)
        );
    }

    #[Test]
    public function non_admin_users_cannot_access_forms()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('forms.index'));

        $response->assertStatus(403);
    }

    #[Test]
    public function it_can_create_a_new_form()
    {
        $this->actingAs($this->admin);

        $formData = [
            'title' => 'Test Form',
            'method' => 'POST',
            'action' => '/submit',
            'fields' => [
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Full Name',
                    'placeholder' => 'Enter your name',
                    'required' => true
                ],
                [
                    'type' => 'email',
                    'name' => 'email',
                    'label' => 'Email Address',
                    'placeholder' => 'Enter your email',
                    'required' => true
                ]
            ]
        ];

        $response = $this->post(route('forms.store'), $formData);

        $response->assertRedirect(route('forms.index'));
        $this->assertDatabaseHas('forms', [
            'title' => 'Test Form',
            'method' => 'POST',
            'action' => '/submit'
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_form()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('forms.store'), []);

        $response->assertSessionHasErrors(['title', 'method', 'action', 'fields']);
    }

    #[Test]
    public function it_can_show_form_details()
    {
        $this->actingAs($this->admin);

        $form = Form::factory()->create();

        $response = $this->get(route('forms.show', $form));

        $response->assertStatus(200);
        $response->assertInertia(
            fn($assert) => $assert
                ->component('Forms/Show')
                ->has('form')
        );
    }

    #[Test]
    public function it_can_edit_form()
    {
        $this->actingAs($this->admin);

        $form = Form::factory()->create();

        $updatedData = [
            'title' => 'Updated Form',
            'method' => 'GET',
            'action' => '/updated',
            'fields' => [
                [
                    'type' => 'text',
                    'name' => 'updated_field',
                    'label' => 'Updated Field',
                    'required' => true
                ]
            ]
        ];

        $response = $this->put(route('forms.update', $form), $updatedData);

        $response->assertRedirect(route('forms.index'));
        $this->assertDatabaseHas('forms', [
            'id' => $form->id,
            'title' => 'Updated Form',
            'method' => 'GET',
            'action' => '/updated'
        ]);
    }

    #[Test]
    public function it_can_import_form_from_json()
    {
        $this->actingAs($this->admin);

        $jsonData = [
            'json_data' => json_encode([
                'title' => 'Imported Form',
                'method' => 'POST',
                'action' => '/imported',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'imported_field',
                        'label' => 'Imported Field',
                        'required' => true
                    ]
                ]
            ])
        ];

        $response = $this->post(route('forms.import-json'), $jsonData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'config',
            'message'
        ]);
    }

    #[Test]
    public function it_validates_json_data_when_importing()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('forms.import-json'), [
            'json_data' => 'invalid-json'
        ]);

        $response->assertSessionHasErrors(['json_data']);
    }

    #[Test]
    public function it_can_update_form_structure()
    {
        $this->actingAs($this->admin);

        $form = Form::factory()->create();

        $newStructure = [
            'configuration' => [
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'reordered_field',
                        'label' => 'Reordered Field',
                        'required' => true
                    ]
                ]
            ]
        ];

        $response = $this->post(route('forms.update-structure', $form), $newStructure);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Form structure updated successfully'
        ]);
    }

    #[Test]
    public function it_validates_form_structure_update()
    {
        $this->actingAs($this->admin);

        $form = Form::factory()->create();

        $response = $this->post(route('forms.update-structure', $form), []);

        $response->assertSessionHasErrors(['configuration']);
    }
}
