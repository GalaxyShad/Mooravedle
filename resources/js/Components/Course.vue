<template>
  <div class="rounded-lg border w-96 h-48 flex flex-col bg-white transition duration-300 ease-in-out hover:scale-105">
    <div :style="{backgroundColor: color()}" class=" text-lg rounded-lg text-center justify-center items-center flex h-full">
      <div class="w-full h-full pattern text-lg rounded-lg text-center justify-center items-center flex">
          {{ name }}
      </div>
    </div>
    <div class="px-2 py-2">
      {{ teacher }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'

const props = defineProps<{
  name: string
  teacher: string
}>()

const stringToColour = (str: string) => {
  let hash = 0;
  str.split('').forEach(char => {
    hash = char.charCodeAt(0) + ((hash << 5) - hash)
  })

  let value = 0;
  for (let i = 0; i < 3; i++) {
    value = (hash >> (i * 8)) & 0xff
  }
  return value;
}

function color() {
  const r = () => 360 * stringToColour(props.name) / 255;

  return `hsl(${r()}, 90%, 78%)`;
}
</script>

<style scoped>
.pattern {
  background:  linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,.8)), url('https://img.freepik.com/free-vector/abstract-organic-pattern-design-background_1048-19286.jpg?w=740&t=st=1708878820~exp=1708879420~hmac=f8c2abb38e1aa7b619b322ad2a473db432b6a024d49acb1308f7b86b8fb8ece0');
  mix-blend-mode: multiply;
}
</style>
