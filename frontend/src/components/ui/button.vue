<template>
    <button
      class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
      v-bind="$attrs"
    >
      <slot />
    </button>
  </template>
  
  <script setup>
  </script>
  