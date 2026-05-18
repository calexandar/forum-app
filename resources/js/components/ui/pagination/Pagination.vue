<template>
  <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 dark:border-white/10 dark:bg-transparent">
    <div class="flex flex-1 justify-between sm:hidden">
      <Link :href="previousPageUrl" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:bg-white/10">Previous</Link>
      <Link :href="nextPageUrl" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:bg-white/10">Next</Link>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700 dark:text-gray-300">
          Showing
          {{ ' ' }}
          <span class="font-medium">{{ meta.from }}</span>
          {{ ' ' }}
          to
          {{ ' ' }}
          <span class="font-medium">{{ meta.to }}</span>
          {{ ' ' }}
          of
          {{ ' ' }}
          <span class="font-medium">{{ meta.total }}</span>
          {{ ' ' }}
          results
        </p>
      </div>
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs dark:shadow-none" aria-label="Pagination">
            <template v-for="link in meta.links" :key="link.label">
            <a
                v-if="link.url"
                :href="link.url"
                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 dark:text-gray-300 dark:hover:bg-white/10"
                :class="{
                'z-10 bg-indigo-600 border-indigo-600 text-white': link.active,
                'rounded-l-md': link.label === 'Previous',
                'rounded-r-md': link.label === 'Next',
                }"    :aria-current="link.active ? 'page' : undefined"
            >
                <span v-if="link.label === 'Previous'">
                <span class="sr-only">Previous</span>
                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                </span>
                <span v-else-if="link.label === 'Next'">
                <span class="sr-only">Next</span>
                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                </span>
                <span v-else>{{ link.label }}</span>
            </a>
            </template>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  meta: Object
})

const previousPageUrl = computed(() => props.meta.links.find(link => link.label === 'Previous')?.url)
const nextPageUrl = computed(() => props.meta.links.find(link => link.label === 'Next')?.url)
</script>