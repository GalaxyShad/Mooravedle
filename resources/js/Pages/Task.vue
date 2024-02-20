<template>
  <Head title="Список курсов" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <div class="flex flex-row gap-2 justify-center items-center">
          <el-button @click="goBack"> &lt </el-button>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ props.name }}
          </h2>
        </div>
      </div>
    </template>

    <div class="bg-white rounded px-4 py-4" v-html="props.description"/>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, usePage, useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

function goBack() {
  window.history.back()
}

interface Props {
  id: number
  created_at: Date
  name: string
  description: string
}

const props = usePage().props.task as Props;

</script>
