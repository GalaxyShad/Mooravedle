<template>
  <Head title="Список курсов" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-row justify-between items-center">
        <div class="flex flex-row gap-2 justify-center items-center">
          <el-button tag="a" href="/dashboard"> &lt </el-button>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ props.course.name }}
          </h2>
        </div>

        <div v-if="isCreator">
          <el-button tag="a" :href="`/course/${props.course.id}/edit`">
            Изменить название
          </el-button>
          <el-button @click="deleteCourse" type="danger">
            Удалить курс
          </el-button>
        </div>
      </div>
    </template>

    <div class="flex flex-row gap-4 w-full">
      <div class="flex flex-col w-full">
        <div
          class="bg-white rounded-md px-4 py-2 border flex flex-row justify-between mb-2"
        >
          <h2 class="text-bold text-xl">Лекции и задания</h2>
          <el-button v-if="isCreator" tag="a" :href="`${props.course.id}/task`">
            Добавить
          </el-button>
        </div>
        <div v-if="props.isAvailableForCurrentUser">
          <div v-if="props.taskList.length !== 0" class="flex flex-col gap-1">
            <div
              v-for="t in props.taskList"
              class="bg-white rounded-md px-4 py-2 border flex flex-row justify-between gap-2 items-center"
            >
              <div class="flex flex-row items-center gap-2">
                <div
                  class="aspect-square bg-blue-400 w-12 rounded-md flex justify-center items-center text-white"
                >
                  <el-icon size="24"><Document /></el-icon>
                </div>
                <a class="max-w-96 hover:text-blue-500 transition-colors ease-in-out" :href="`/task/${t.id}`">{{ t.name }}</a>
              </div>
              <div class="flex flex-row items-center gap-1">
                <span class="text-gray-400"> {{ t.deadline }} </span>
                <div>
                  <el-button v-if="isCreator" tag="a" :href="`${props.course.id}/task/${t.id}/edit`">
                    Изменить
                  </el-button>
                  <el-button  plain type="danger" v-if="isCreator" @click="() => onTaskDestroy(t.id)">
                    Удалить
                  </el-button>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="flex justify-center items-center">
            <el-empty />
          </div>
        </div>
        <div v-else>
          <el-result
            icon="error"
            title="Нет доступа"
            :sub-title="`Для предоставления доступа к заданиям курса, свяжитесь с автором курса: ${props.course.creator.email}`"
          >
          </el-result>
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
            <el-button
              v-if="isCreator"
              @click="() => (isSearchDialogVisible = true)"
            >
              +
            </el-button>
          </div>
          <div v-if="props.participants.length === 0">
            <el-empty />
          </div>
          <div v-else class="flex flex-col gap-1">
            <div
              v-for="u in props.participants"
              class="flex flex-row justify-between text-sm items-center gap-4 pb-1 border-b"
            >
              <div class="flex flex-col">
                <div>{{ u.name }}</div>
                <div class="text-gray-400">{{ u.email }}</div>
              </div>
              <el-button
                v-if="isCreator"
                plain
                @click="() => removeParticipant(u.id)"
                type="danger"
                size="small"
                >-</el-button
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>

  <el-dialog v-model="isSearchDialogVisible" title="Добавление участников">
    <el-select
      v-model="usersToInvite"
      value-key="id"
      multiple
      filterable
      remote
      reserve-keyword
      placeholder="Введите ФИО людей которых хотите добавить"
      :remote-method="searchUser"
      :loading="isUsersToInviteLoading"
    >
      <el-option
        v-for="item in searchUserList"
        :key="item.id"
        :label="item.name"
        :value="item.id"
        :disabled="item.disabled"
      />
    </el-select>
    <template #footer>
      <div class="dialog-footer">
        <el-button @click="isSearchDialogVisible = false">Отмена</el-button>
        <el-button type="primary" @click="addUsersToCourse">
          Добавить
        </el-button>
      </div>
    </template>
  </el-dialog>
</template>

<script setup lang="ts">
import { Document } from '@element-plus/icons-vue'
import Course from '@/Components/Course.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage, useForm, router  } from '@inertiajs/vue3'
import { computed, ref, onMounted, getCurrentInstance } from 'vue'
import axios from 'axios'
import { currentUser } from '@/Helpers/User'
import { User } from 'vendor/laravel/breeze/stubs/inertia-react-ts/resources/js/types'

interface Course {
  id: number
  name: string
  creator: {
    id: number
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
  participants: User[]
  isAvailableForCurrentUser: boolean
}

interface SearchUserItem {
  id: number
  name: string
  disabled: boolean
}

const isSearchDialogVisible = ref<boolean>(false)

const props = usePage().props.props as Props

const isCreator = computed<boolean>(
  () => props.course.creator.id === currentUser().id
)

const searchUserList = ref<SearchUserItem[]>([])
const usersToInvite = ref<number[]>([])
const isUsersToInviteLoading = ref<boolean>(false)

onMounted(async () => {
  const { data } = await axios.post(route('profile.search'))
  searchUserList.value = data
})

function onTaskDestroy(id: number) {
  useForm({}).delete(route('task.destroy', { id, courseId: props.course.id }))
}

function addUsersToCourse() {
  useForm({ users: usersToInvite.value }).post(
    route('course.add_participant', { id: props.course.id })
  )
}

function removeParticipant(id: number) {
  useForm({}).delete(
    route('course.remove_participant', {
      id: props.course.id,
      participant_id: id,
    })
  )
}

function deleteCourse() {
  useForm({}).delete(route('course.delete', { id: props.course.id }))
}

async function searchUser(search_value: string) {
  isUsersToInviteLoading.value = true

  const { data } = await axios.post<SearchUserItem[]>(route('profile.search'), {
    search_value,
  })
  searchUserList.value = data.map((x) => ({
    ...x,
    disabled:
      props.participants.find((y) => y.id === x.id) !== undefined ||
      x.id === props.course.creator.id,
  }))

  isUsersToInviteLoading.value = false
}
</script>
