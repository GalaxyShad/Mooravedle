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
        <div
          class="bg-white rounded-md px-4 py-2 border flex flex-row justify-between mb-2"
        >
          <h2 class="text-bold text-xl">Задания</h2>
          <el-button tag="a" :href="`${props.course.id}/task`">
            Добавить
          </el-button>
        </div>
        <div v-if="props.taskList.length !== 0" class="flex flex-col gap-1">
            <div v-for="t in props.taskList" class="bg-white rounded-md px-4 py-2 border flex flex-row justify-between gap-2 items-center">
                <div class="flex flex-row  items-center gap-2">
                    <div class="aspect-square bg-blue-400 w-12 rounded-md flex justify-center items-center text-white">
                        <el-icon size="24"><Document /></el-icon>
                    </div>
                    <span>{{ t.name }}</span>
                </div>
                <div class="flex flex-row  items-center gap-2">
                    <span class="text-gray-400"> {{ t.deadline }} </span>
                    <el-button>Удалить</el-button>
                </div>
            </div>
        </div>
        <div v-else class="flex justify-center items-center">
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
import {Document} from '@element-plus/icons-vue'
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

interface Task {
  id: number
  name: string
  deadline: Date
}

interface Props {
  course: Course
  taskList: Task[]
}

const props = computed<Props>(() => usePage().props.props as Props)

console.log(props.value)
</script>
