<template>
  <Head title="Список курсов" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Список курсов
        </h2>

        <el-button type="primary" tag="a" href="/course"
          >Создать новый курс</el-button
        >
      </div>
    </template>

    <div v-if="courseList.length !== 0" class="flex flex-row flex-wrap gap-4 py-4">
      <Course v-for="c in courseList" :name="c.name" :teacher="c.creator.name" />
    </div>
    <el-empty v-else/>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import Course from '@/Components/Course.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue';

interface Course {
  id: number;
  name: string;
  creator: {
    name: string,
  }
};

const courseList = computed<Course[]>(() => usePage().props.courseList as Course[]);

console.log(courseList.value);

</script>
