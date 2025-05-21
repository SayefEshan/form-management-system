<!-- resources/js/Components/Forms/FormFieldEditor.vue -->
<script setup lang="ts">
import { ref, computed } from 'vue';
import { FormField } from '@/types/form';

interface Props {
  field: FormField;
  index: number;
}

const props = defineProps<Props>();
const emit = defineEmits(['remove', 'update:field']);

const field = computed({
  get: () => props.field,
  set: (value) => emit('update:field', value)
});

// For managing select options
const newOptionLabel = ref('');
const newOptionValue = ref('');

const addOption = () => {
  if (!field.value.options) {
    field.value.options = [];
  }
  
  if (newOptionLabel.value && newOptionValue.value) {
    field.value.options.push({
      label: newOptionLabel.value,
      value: newOptionValue.value
    });
    
    // Reset the inputs
    newOptionLabel.value = '';
    newOptionValue.value = '';
  }
};

const removeOption = (index: number) => {
  if (field.value.options) {
    field.value.options.splice(index, 1);
  }
};
</script>

<template>
  <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
    <!-- Field header with remove button -->
    <div class="flex justify-between items-center mb-4">
      <h4 class="text-md font-medium">
        {{ field.label }} 
        <span class="text-sm text-gray-500">({{ field.type }})</span>
      </h4>
      <button 
        @click="$emit('remove')" 
        class="text-red-600 hover:text-red-800"
      >
        Remove
      </button>
    </div>
    
    <!-- Field properties -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label :for="`field-${index}-name`" class="block text-sm font-medium text-gray-700">Field Name</label>
        <input 
          :id="`field-${index}-name`" 
          v-model="field.name" 
          type="text" 
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>
      <div>
        <label :for="`field-${index}-label`" class="block text-sm font-medium text-gray-700">Field Label</label>
        <input 
          :id="`field-${index}-label`" 
          v-model="field.label" 
          type="text" 
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>
      <div>
        <label :for="`field-${index}-placeholder`" class="block text-sm font-medium text-gray-700">Placeholder</label>
        <input 
          :id="`field-${index}-placeholder`" 
          v-model="field.placeholder" 
          type="text" 
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>
      <div class="flex items-center">
        <input 
          :id="`field-${index}-required`" 
          v-model="field.required" 
          type="checkbox" 
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
        />
        <label :for="`field-${index}-required`" class="ml-2 block text-sm font-medium text-gray-700">Required</label>
      </div>
    </div>
    
    <!-- Select options section (only for select fields) -->
    <div v-if="field.type === 'select'" class="mt-4 border-t border-gray-200 pt-4">
      <h5 class="text-sm font-medium text-gray-700 mb-2">Select Options</h5>
      
      <!-- Add new option form -->
      <div class="flex items-center space-x-2 mb-4">
        <div>
          <label :for="`field-${index}-option-label`" class="sr-only">Option Label</label>
          <input 
            :id="`field-${index}-option-label`" 
            v-model="newOptionLabel" 
            placeholder="Option Label" 
            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>
        <div>
          <label :for="`field-${index}-option-value`" class="sr-only">Option Value</label>
          <input 
            :id="`field-${index}-option-value`" 
            v-model="newOptionValue" 
            placeholder="Option Value" 
            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>
        <button 
          @click="addOption" 
          class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700"
        >
          Add
        </button>
      </div>
      
      <!-- Options list -->
      <ul v-if="field.options && field.options.length > 0" class="space-y-2">
        <li v-for="(option, optionIndex) in field.options" :key="optionIndex" class="flex justify-between items-center bg-white p-2 rounded">
          <div>
            <span class="font-medium">{{ option.label }}</span>
            <span class="text-gray-500 text-sm ml-2">({{ option.value }})</span>
          </div>
          <button @click="removeOption(optionIndex)" class="text-red-600 hover:text-red-800 text-sm">
            Remove
          </button>
        </li>
      </ul>
      <p v-else class="text-gray-500 text-sm">No options added yet.</p>
    </div>
  </div>
</template>