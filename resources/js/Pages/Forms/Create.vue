<!-- resources/js/Pages/Forms/Create.vue -->
<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FormFieldEditor from '@/Components/Forms/FormFieldEditor.vue';
import { Form, FormField } from '@/types/form';

const showJsonImport = ref(false);
const jsonInput = ref('');
const errorMessage = ref('');

// Initialize empty form structure
const form = useForm<Form>({
  title: '',
  method: 'POST',
  action: '',
  fields: []
});

const addField = (type: string) => {
  const newField: FormField = {
    type,
    name: `field_${form.fields.length + 1}`,
    label: `New ${type.charAt(0).toUpperCase() + type.slice(1)} Field`,
    placeholder: `Enter ${type} here`,
    required: false
  };
  
  // For select fields, add default options
  if (type === 'select') {
    newField.options = [
      { label: 'Option 1', value: 'option1' },
      { label: 'Option 2', value: 'option2' }
    ];
  }
  
  form.fields.push(newField);
};

const removeField = (index: number) => {
  form.fields.splice(index, 1);
};

const importJson = () => {
  try {
    // Validate and parse JSON
    const parsedJson = JSON.parse(jsonInput.value);
    
    // Basic validation
    if (!parsedJson.title || !parsedJson.method || !parsedJson.action || !Array.isArray(parsedJson.fields)) {
      errorMessage.value = 'Invalid JSON structure. Required fields: title, method, action, fields[]';
      return;
    }
    
    // Update form with parsed values
    form.title = parsedJson.title;
    form.method = parsedJson.method;
    form.action = parsedJson.action;
    form.fields = parsedJson.fields;
    
    // Close the import modal
    showJsonImport.value = false;
    errorMessage.value = '';
  } catch (error) {
    errorMessage.value = 'Invalid JSON format';
  }
};

const submit = () => {
  // Convert fields to JSON string for backend
  const formData = {
    ...form,
    fields: JSON.stringify(form.fields)
  };
  
  form.post(route('forms.store'), {
    onSuccess: () => {
      // Reset form after successful submission
      form.reset();
    }
  });
};
</script>

<template>
  <Head title="Create Form" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Form</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6 flex justify-between">
              <h3 class="text-lg font-medium text-gray-900">Form Details</h3>
              <button 
                @click="showJsonImport = true" 
                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
              >
                Import JSON
              </button>
            </div>
            
            <!-- Basic form details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
              <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Form Title</label>
                <input 
                  id="title" 
                  v-model="form.title" 
                  type="text" 
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  placeholder="Enter form title"
                />
              </div>
              <div>
                <label for="method" class="block text-sm font-medium text-gray-700">Form Method</label>
                <select 
                  id="method" 
                  v-model="form.method" 
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                  <option value="POST">POST</option>
                  <option value="GET">GET</option>
                </select>
              </div>
              <div>
                <label for="action" class="block text-sm font-medium text-gray-700">Form Action</label>
                <input 
                  id="action" 
                  v-model="form.action" 
                  type="text" 
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  placeholder="/submit"
                />
              </div>
            </div>
            
            <!-- Form fields section -->
            <div class="mb-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Form Fields</h3>
                <div class="flex space-x-2">
                  <button 
                    @click="addField('text')" 
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                  >
                    Add Text Field
                  </button>
                  <button 
                    @click="addField('email')" 
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                  >
                    Add Email Field
                  </button>
                  <button 
                    @click="addField('textarea')" 
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                  >
                    Add Textarea
                  </button>
                  <button 
                    @click="addField('select')" 
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                  >
                    Add Select
                  </button>
                </div>
              </div>
              
              <!-- Field editor component -->
              <div v-if="form.fields.length === 0" class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                <p class="text-gray-500">No fields added yet. Add fields using the buttons above or import a JSON configuration.</p>
              </div>
              <div v-else class="space-y-4">
                <FormFieldEditor 
                  v-for="(field, index) in form.fields" 
                  :key="index"
                  :field="field"
                  :index="index"
                  @remove="removeField(index)"
                />
              </div>
            </div>
            
            <!-- Submit button -->
            <div class="flex justify-end">
              <button 
                @click="submit" 
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Saving...' : 'Save Form' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- JSON Import Modal -->
    <div v-if="showJsonImport" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Import JSON Configuration</h3>
        <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {{ errorMessage }}
        </div>
        <textarea 
          v-model="jsonInput" 
          class="w-full h-64 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          placeholder='{"title":"Contact Form","method":"POST","action":"/submit","fields":[...]}'
        ></textarea>
        <div class="flex justify-end mt-4 space-x-2">
          <button 
            @click="showJsonImport = false" 
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
          >
            Cancel
          </button>
          <button 
            @click="importJson" 
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Import
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>