<!-- resources/js/Pages/Forms/Show.vue -->
<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Form, FormField } from '@/types/form';
import DynamicForm from '@/Components/Forms/DynamicForm.vue';

interface Props {
  form: Form;
}

const props = defineProps<Props>();

// For demonstration purposes - storing form submission data
const formData = ref<Record<string, any>>({});
const formSubmitted = ref(false);

const handleSubmit = (data: Record<string, any>) => {
  formData.value = data;
  formSubmitted.value = true;
};
</script>

<template>
  <Head :title="form.title" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ form.title }}</h2>
        <Link 
          :href="route('forms.edit', form.id)" 
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Edit Form
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Form details -->
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div>
                <h3 class="text-sm font-medium text-gray-700">Method</h3>
                <p>{{ form.method }}</p>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-700">Action</h3>
                <p>{{ form.action }}</p>
              </div>
            </div>
            
            <h3 class="font-medium text-lg mb-4">Form Preview</h3>
            
            <!-- Dynamic form rendering -->
            <div class="bg-gray-50 p-6 rounded-lg">
              <DynamicForm 
                :form="form" 
                @submit="handleSubmit"
              />
            </div>
            
            <!-- Form submission result (for demonstration) -->
            <div v-if="formSubmitted" class="mt-8">
              <h3 class="font-medium text-lg mb-2">Form Submission Result</h3>
              <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h4 class="text-green-800 font-medium mb-2">Form submitted successfully!</h4>
                <p class="text-sm text-gray-600 mb-2">Form data would be sent to: {{ form.action }}</p>
                <pre class="bg-white p-4 rounded border text-sm overflow-auto">{{ JSON.stringify(formData, null, 2) }}</pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>