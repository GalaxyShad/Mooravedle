<template>
  <Head title="Список курсов" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ props.course.name }}
        </h2>

        <div>
          <el-button> Редактировать курс </el-button>
          <el-button type="danger"> Удалить курс </el-button>
        </div>
      </div>
    </template>

    <div class="flex flex-row gap-4 w-full">
      <div class="flex flex-col w-full">
        <div class="bg-white rounded-md px-4 py-2 border flex flex-row justify-between">
          <h2 class="text-bold text-xl">Задания</h2>
          <el-button> Добавить </el-button>
        </div>
        <div class="flex justify-center items-center">
          <el-empty />
        </div>
      </div>
      <div class="flex flex-col w-96 gap-4">
        <div class="flex flex-col bg-white rounded-md px-4 py-2 border">
          <h2 class="text-bold text-xl mb-2">Автор курса</h2>
          <el-avatar shape="square"></el-avatar>
          <div class="mt-2">
            <div>
              {{ props.course.creator.name }}
            </div>
            <div>
              {{ props.course.creator.email }}
            </div>
          </div>
        </div>
        <div class="flex flex-col bg-white rounded-md px-4 py-2 border">
          <div class="flex flex-row justify-between">
            <h2 class="text-bold text-xl mb-2">Участники</h2>
            <el-button>+</el-button>
          </div>
          <div>
            <el-empty />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import Course from '@/Components/Course.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

interface Course {
  id: number
  name: string
  creator: {
    email: string
    name: string
  }
}

interface Task {}

interface Props {
  course: Course
  taskList: Task[]
}

const props = computed<Props>(() => usePage().props.props as Props)

console.log(props.value)
</script>
