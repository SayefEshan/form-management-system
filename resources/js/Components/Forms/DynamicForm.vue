<!-- resources/js/Components/Forms/DynamicForm.vue -->
<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Form, FormField } from '@/types/form';

interface Props {
  form: Form;
}

const props = defineProps<Props>();
const emit = defineEmits(['submit']);

// Create dynamic form data object based on field names
const formData = reactive<Record<string, any>>({});
props.form.fields.forEach((field: FormField) => {
  formData[field.name] = field.type === 'checkbox' ? false : '';
});

// Form validation
const errors = ref<Record<string, string>>({});

const validateField = (field: FormField) => {
  const value = formData[field.name];
  
  // Clear existing error
  delete errors.value[field.name];
  
  // Required validation
  if (field.required && (!value || (typeof value === 'string' && value.trim() === ''))) {
    errors.value[field.name] = `${field.label} is required`;
    return false;
  }
  
  // Email validation
  if (field.type === 'email' && value) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      errors.value[field.name] = 'Please enter a valid email address';
      return false;
    }
  }
  
  return true;
};

const handleSubmit = (e: Event) => {
  e.preventDefault();
  
  // Validate all fields
  let isValid = true;
  props.form.fields.forEach((field: FormField) => {
    if (!validateField(field)) {
      isValid = false;
    }
  });
  
  if (isValid) {
    emit('submit', formData);
  }
};
</script>

<template>
  <form @submit="handleSubmit" class="space-y-6">
    <!-- Dynamically render form fields based on field type -->
    <div v-for="field in form.fields" :key="field.name" class="form-group">
      <label :for="field.name" class="block text-sm font-medium text-gray-700 mb-1">
        {{ field.label }}
        <span v-if="field.required" class="text-red-500">*</span>
      </label>
      
      <!-- Text field -->
      <input 
        v-if="field.type === 'text'" 
        :id="field.name"
        v-model="formData[field.name]"
        type="text"
        :placeholder="field.placeholder"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        @blur="validateField(field)"
      />
      
      <!-- Email field -->
      <input 
        v-else-if="field.type === 'email'" 
        :id="field.name"
        v-model="formData[field.name]"
        type="email"
        :placeholder="field.placeholder"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        @blur="validateField(field)"
      />
      
      <!-- Textarea field -->
      <textarea 
        v-else-if="field.type === 'textarea'" 
        :id="field.name"
        v-model="formData[field.name]"
        :placeholder="field.placeholder"
        rows="4"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        @blur="validateField(field)"
      ></textarea>
      
      <!-- Select field -->
      <select 
        v-else-if="field.type === 'select'"
        :id="field.name"
        v-model="formData[field.name]"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        @blur="validateField(field)"
      >
        <option value="" disabled>{{ field.placeholder || 'Select an option' }}</option>
        <option 
          v-for="option in field.options" 
          :key="option.value" 
          :value="option.value"
        >
          {{ option.label }}
        </option>
      </select>
      
      <!-- Checkbox field -->
      <div v-else-if="field.type === 'checkbox'" class="flex items-center">
        <input 
          :id="field.name"
          v-model="formData[field.name]"
          type="checkbox"
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
          @blur="validateField(field)"
        />
        <label :for="field.name" class="ml-2 block text-sm text-gray-900">
          {{ field.placeholder || field.label }}
        </label>
      </div>
      
      <!-- Number field -->
      <input 
        v-else-if="field.type === 'number'" 
        :id="field.name"
        v-model="formData[field.name]"
        type="number"
        :placeholder="field.placeholder"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        @blur="validateField(field)"
      />
      
      <!-- Error message display -->
      <p v-if="errors[field.name]" class="mt-1 text-sm text-red-600">
        {{ errors[field.name] }}
      </p>
    </div>
    
    <!-- Submit button -->
    <div>
      <button 
        type="submit"
        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Submit
      </button>
    </div>
  </form>
</template>