<template>
  <Head title="Список курсов" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ !isEdit ? "Создание нового курса" : "Редактирование курса" }}
        </h2>
      </div>
    </template>

    <el-form
      class="bg-white rounded px-4 py-4"
      :model="form"
      label-width="120px"
    >
      <el-form-item label="Название">
        <el-input v-model="form.name" />
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="onSubmit">{{ !isEdit ? "Создать" : "Изменить" }}</el-button>
        <el-button tag="a" :href="rollBackUrl">Оменить</el-button>
      </el-form-item>
    </el-form>
  </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const editId: number = usePage().props.editId as number;
const isEdit = computed(() => editId !== undefined);

const rollBackUrl = computed(() => isEdit.value ? `/course/${editId}` : '/dashboard'); 

console.log({editId, isEdit});

const form = useForm({
  name: '' as string,
})

const onSubmit = () => {
  if (isEdit.value) {
    form.patch(route('course.update', { id: editId }))
  } else {
    form.post(route('course.store'))
  }
}
</script>
