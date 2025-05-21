<!-- resources/js/Components/Forms/JsonImport.vue -->
<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { Form } from '@/types/form';

interface Props {
  onImport: (formData: Form) => void;
}

const props = defineProps<Props>();
const emit = defineEmits(['close']);

const jsonInput = ref('');
const isLoading = ref(false);
const error = ref('');

// Sample JSON to help users
const sampleJson = {
  "title": "Contact Form",
  "method": "POST",
  "action": "/submit",
  "fields": [
    {
      "type": "text",
      "name": "name",
      "label": "Name",
      "placeholder": "Enter your name",
      "required": true
    },
    {
      "type": "email",
      "name": "email",
      "label": "Email",
      "placeholder": "Enter your email",
      "required": true
    },
    {
      "type": "textarea",
      "name": "message",
      "label": "Message",
      "placeholder": "Enter your message",
      "required": true
    }
  ]
};

const showSample = () => {
  jsonInput.value = JSON.stringify(sampleJson, null, 2);
};

const validateAndImport = async () => {
  error.value = '';
  isLoading.value = true;
  
  try {
    // Try to parse JSON to validate format
    const parsedJson = JSON.parse(jsonInput.value);
    
    // Basic validation
    if (!parsedJson.title || !parsedJson.method || !parsedJson.action || !Array.isArray(parsedJson.fields)) {
      error.value = 'Invalid JSON structure. Required fields: title, method, action, fields[]';
      isLoading.value = false;
      return;
    }
    
    // Validate fields
    for (const field of parsedJson.fields) {
      if (!field.type || !field.name || !field.label) {
        error.value = 'Invalid field structure. Each field requires type, name, and label properties.';
        isLoading.value = false;
        return;
      }
    }
    
    // Send to backend for validation (optional)
    try {
      const response = await axios.post(route('forms.import-json'), {
        json_data: jsonInput.value
      });
      
      // If backend validation successful, return the parsed config
      props.onImport(parsedJson);
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Error validating JSON on server';
    }
  } catch (err) {
    error.value = 'Invalid JSON format. Please check your syntax.';
  }
  
  isLoading.value = false;
};
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-900">Import JSON Configuration</h3>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ error }}
      </div>
      
      <div class="mb-4">
        <div class="flex justify-between mb-2">
          <label for="json-input" class="block text-sm font-medium text-gray-700">Paste your JSON configuration:</label>
          <button 
            @click="showSample" 
            class="text-sm text-blue-600 hover:text-blue-800"
          >
            Show Sample
          </button>
        </div>
        <textarea 
          id="json-input"
          v-model="jsonInput" 
          class="w-full h-64 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 font-mono text-sm"
          placeholder='{"title":"Form Title","method":"POST","action":"/submit","fields":[...]}'
        ></textarea>
      </div>
      
      <div class="text-sm text-gray-600 mb-4">
        <p>JSON must include <code class="bg-gray-100 px-1">title</code>, <code class="bg-gray-100 px-1">method</code>, <code class="bg-gray-100 px-1">action</code>, and <code class="bg-gray-100 px-1">fields</code> array.</p>
        <p>Each field requires <code class="bg-gray-100 px-1">type</code>, <code class="bg-gray-100 px-1">name</code>, and <code class="bg-gray-100 px-1">label</code> properties.</p>
      </div>
      
      <div class="flex justify-end space-x-2">
        <button 
          @click="$emit('close')" 
          class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
        >
          Cancel
        </button>
        <button 
          @click="validateAndImport" 
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          :disabled="isLoading"
        >
          <span v-if="isLoading">Validating...</span>
          <span v-else>Import</span>
        </button>
      </div>
    </div>
  </div>
</template>