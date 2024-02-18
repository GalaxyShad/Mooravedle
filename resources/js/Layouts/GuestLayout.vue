<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div
    class="bg-slate-200 min-h-screen flex flex-col sm:justify-center items-center"
  >
    <div
      class="flex flex-row items-center bg-white mt-6 px-12 py-12 border overflow-hidden sm:rounded-lg gap-16"
    >
      <div>
        <Link href="/">
          <ApplicationLogo class="w-64 fill-current text-gray-500" />
        </Link>
      </div>

      <div class="w-full sm:max-w-md">
        <slot />
      </div>
    </div>
  </div>
</template>
