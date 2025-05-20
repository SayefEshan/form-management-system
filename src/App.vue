<template>
  <div>
    <h1>{{ message }}</h1>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const message = ref('Welcome to Vue 3 + TypeScript')
</script>

