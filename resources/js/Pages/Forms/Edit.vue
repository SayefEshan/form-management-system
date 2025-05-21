<!-- resources/js/Pages/Forms/Edit.vue -->
<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FormFieldEditor from '@/Components/Forms/FormFieldEditor.vue';
import { Form as FormType, FormField } from '@/types/form';

// Define props
interface Props {
  form: FormType;
}

const props = defineProps<Props>();

// Initialize form with data from props
const form = useForm({
  title: props.form.title,
  method: props.form.method,
  action: props.form.action,
  fields: [...props.form.fields] // Clone to avoid modifying props directly
});

// Drag and drop functionality
const draggedItemIndex = ref<number | null>(null);

const startDrag = (index: number) => {
  draggedItemIndex.value = index;
};

const onDrop = (targetIndex: number) => {
  if (draggedItemIndex.value === null) return;
  
  const sourceIndex = draggedItemIndex.value;
  
  // Don't do anything if dropping onto the same item
  if (sourceIndex === targetIndex) return;
  
  // Get the item being dragged
  const draggedItem = { ...form.fields[sourceIndex] };
  
  // Remove the dragged item from its original position
  form.fields.splice(sourceIndex, 1);
  
  // Add the dragged item at the new position
  form.fields.splice(targetIndex, 0, draggedItem);
  
  // Reset draggedItemIndex
  draggedItemIndex.value = null;
};

const allowDrop = (e: DragEvent) => {
  e.preventDefault();
};

// Field operations
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

const updateField = (index: number, updatedField: FormField) => {
  form.fields[index] = updatedField;
};

// Submit function
const submit = () => {
  form.put(route('forms.update', props.form.id), {
    onSuccess: () => {
      // Redirect or show success message
    }
  });
};
</script>

<template>
  <Head title="Edit Form" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Form: {{ form.title }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
              <h3 class="text-lg font-medium text-gray-900">Form Details</h3>
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
                  <button 
                    @click="addField('number')" 
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                  >
                    Add Number
                  </button>
                </div>
              </div>
              
              <p class="text-sm text-gray-600 mb-4">Drag and drop fields to reorder them.</p>
              
              <!-- Drag and drop field editor -->
              <div v-if="form.fields.length === 0" class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                <p class="text-gray-500">No fields added yet. Add fields using the buttons above.</p>
              </div>
              <div v-else class="space-y-4">
                <div 
                  v-for="(field, index) in form.fields" 
                  :key="index"
                  draggable="true"
                  @dragstart="startDrag(index)"
                  @dragover="allowDrop"
                  @drop="onDrop(index)"
                  class="drag-item"
                >
                  <!-- Visual indicator for draggable items -->
                  <div class="flex items-center mb-2 cursor-move bg-gray-100 p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-gray-600">Drag to reorder</span>
                  </div>
                  
                  <FormFieldEditor 
                    :field="field"
                    :index="index"
                    @remove="removeField(index)"
                    @update:field="updateField(index, $event)"
                  />
                </div>
              </div>
            </div>
            
            <!-- Submit button -->
            <div class="flex justify-end">
              <button 
                @click="submit" 
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Saving...' : 'Update Form' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.drag-item {
  transition: all 0.3s ease;
}

.drag-item:hover {
  opacity: 0.9;
}

.drag-item.dragging {
  opacity: 0.5;
  background-color: #f3f4f6;
}
</style>