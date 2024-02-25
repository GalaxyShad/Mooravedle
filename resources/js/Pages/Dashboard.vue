<template>
  <Head title="Список курсов" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ `Список курсов (${courseCount})` }}
        </h2>
        <el-button
          v-if="currentUser().is_teacher"
          type="primary"
          tag="a"
          href="/course"
        >
          Создать новый курс
        </el-button>
      </div>
    </template>

    <div
      v-if="courseList.length !== 0"
      class="flex flex-row flex-wrap gap-4 py-4 justify-center mx-auto"
    >
      <a v-for="c in courseList" :href="`/course/${c.id}`">
        <Course :name="c.name" :teacher="c.creator.name" />
      </a>
    </div>
    <el-empty v-else />
    <el-pagination
      @change="pageChange"
      class="w-full items-center justify-center"
      layout="prev, pager, next"
      :default-current-page="+currentPage"
      :page-count="pagesCount"
    />
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Course from '@/Components/Course.vue'

import { Head, useForm, usePage, router } from '@inertiajs/vue3'
import { currentUser } from '@/Helpers/User'
import { computed } from 'vue'

interface Course {
  id: number
  name: string
  creator: {
    name: string
  }
}

const currentPage = usePage().props.currentPage
const pageName = usePage().props.pageName
const pagesCount = computed<number>(() => usePage().props.pagesTotal as number)
const courseCount = usePage().props.courseCount

const courseList = computed<Course[]>(
  () => usePage().props.courseList as Course[]
)

function pageChange(page: string) {
  router.get('/' + pageName + '/' + page)
}

</script>
