<template>
  <el-select
    v-model="value"
    multiple
    filterable
    remote
    reserve-keyword
    placeholder="Please enter a keyword"
    :remote-method="remoteMethod"
    :loading="loading"
    style="width: 240px"
  >
    <el-option
      v-for="item in options"
      :key="item.id"
      :label="item.name"
      :value="item.id"
    />
  </el-select>
</template>

<script setup lang="ts">
import axios from 'axios'
import { onMounted, ref } from 'vue'

interface ListItem {
  id: string
  name: string
}

const list = ref<ListItem[]>([])
const options = ref<ListItem[]>([])
const value = ref<string[]>([])
const loading = ref(false)

onMounted(async () => {
  const { data } = await axios.post(route('profile.search'))

  console.log(data)
  //   list.value = states.map((item) => {
  //     return { value: `value:${item}`, label: `label:${item}` }
  //   })
})

const remoteMethod = async (search_value: string) => {
  loading.value = true
  const { data } = await axios.post(route('profile.search'), { search_value })
  options.value = data
//   console.log(data)
  loading.value = false
}
</script>
