<template>
  <Head title="Новое задание" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ isCreate ? 'Новое задание' : 'Редактировать задание' }}
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
          <el-button type="primary" @click="onSubmit">{{
            isCreate ? 'Создать' : 'Изменить'
          }}</el-button>
          <el-button @click="goBack">Оменить</el-button>
        </el-form-item>
      </el-form>
    </div>
  </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, InertiaForm, useForm, usePage } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'

function goBack() {
  window.history.back()
}

const editId = usePage().props.editId as number
const isCreate = editId === undefined
const previousForm = usePage().props.previousForm as InertiaForm<{
  name: string
  description: string
  deadline: Date
  courseId: number
}>

// do not use same name with ref
const form = useForm(
  previousForm ?? {
    name: 'Новое задание' as string,
    description: '<p><br></p>' as string,
    deadline: new Date(),
    courseId: usePage().props.courseId,
  }
)

const onSubmit = () => {
  if (isCreate) {
    form.post(route('task.store', { id: form.courseId }))
  } else {
    form.patch(route('task.update', { id: editId }))
  }
}
</script>
