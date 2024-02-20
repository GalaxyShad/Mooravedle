<template>
  <Head title="Новое задание" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Новое задание
        </h2>
      </div>
    </template>

    <div class="bg-white rounded px-4 py-4">
      <el-form :model="form" class="flex flex-col h-full">
        <el-form-item label="Название">
          <el-input v-model="form.name" />
        </el-form-item>

        <quill-editor
          v-model:content="form.description"
          contentType="html"
          theme="snow"
        ></quill-editor>

        <el-form-item class="mt-8">
          <el-button type="primary" @click="onSubmit">Создать</el-button>
          <el-button @click="goBack">Оменить</el-button>
        </el-form-item>
      </el-form>
    </div>
  </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'

function goBack() {
  window.history.back()
}

// do not use same name with ref
const form = useForm({
  name: 'Новый курс' as string,
  description: '<p><br></p>' as string,
  deadline: new Date(),
  courseId: usePage().props.courseId,
})

const onSubmit = () => {
  form.post(route('task.store', { id: form.courseId }))
}
</script>
