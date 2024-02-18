<template>
  <div class="rounded-lg border w-96 h-48 flex flex-col bg-white">
    <div class="bg-blue-300 text-lg rounded-lg justify-center items-center flex h-full">
      {{ name }}
    </div>
    <div class="px-2 py-2">
      {{ teacher }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'

defineProps<{
  name: string
  teacher: string
}>()
</script>
