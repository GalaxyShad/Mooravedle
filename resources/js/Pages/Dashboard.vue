<template>
  <Head title="Список курсов" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ `Список курсов (${courseList.total})` }}
        </h2>
        <el-button
          v-if="currentUser().is_teacher"
          type="primary"
          tag="a"
          :href="route('course.create')"
        >
          Создать новый курс
        </el-button>
      </div>
    </template>

    <div
      v-if="courseList.data.length !== 0"
      class="flex flex-row flex-wrap gap-4 py-4 justify-center mx-auto"
    >
      <a v-for="c in courseList.data" :href="route('course.show', c.id)">
        <Course :name="c.name" :teacher="c.creator.name" />
      </a>
    </div>
    <el-empty v-else />
    <el-pagination
      @current-change="pageChange"
      class="w-full items-center justify-center"
      layout="prev, pager, next"
      :current-page="courseList.current_page"
      :page-count="courseList.last_page"
    />
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Course from '@/Components/Course.vue'

import { Head, usePage, router } from '@inertiajs/vue3'
import { currentUser } from '@/Helpers/User'
import { computed } from 'vue'

interface Course {
  id: number
  name: string
  creator: {
    name: string
  }
}

const courseList = computed<{ data: Course[], total: number, current_page: number, last_page: number }>(
  () => usePage().props.courseList as { data: Course[], total: number, current_page: number, last_page: number }
)

function pageChange(page: number) {
  router.get(route('dashboard'), { page }, { preserveState: true })
}

</script>
