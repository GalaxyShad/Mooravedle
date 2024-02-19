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

    <el-form
      class="bg-white rounded px-4 py-4"
      :model="form"
      label-width="240px"
    >
      <el-form-item label="Название">
        <el-input v-model="form.name" />
      </el-form-item>

      <el-form-item label="Срок сдачи">
        <el-date-picker v-model="form.deadline" type="datetime" placeholder="Select date and time" />
      </el-form-item>

      <quill-editor v-model:content="form.description" contentType="html" theme="snow"></quill-editor>

      <el-form-item class="mt-8">
        <el-button type="primary" @click="onSubmit">Создать</el-button>
        <el-button tag="a" href="/dashboard">Оменить</el-button>
      </el-form-item>
    </el-form>
  </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'


// do not use same name with ref
const form = useForm({
  name: 'Новый курс' as string,
  description: '<p><br></p>' as string,
  deadline: new Date(),
  courseId: usePage().props.courseId
})

const onSubmit = () => {
  form.post(route('task.store', { id: form.courseId }))
}
</script>

<style scoped>
.avatar-uploader .avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>

<style>
.avatar-uploader .el-upload {
  border: 1px dashed var(--el-border-color);
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: var(--el-transition-duration-fast);
}

.avatar-uploader .el-upload:hover {
  border-color: var(--el-color-primary);
}

.el-icon.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  text-align: center;
}
</style>
