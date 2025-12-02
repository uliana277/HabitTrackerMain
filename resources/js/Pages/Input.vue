<script setup>
import { onMounted, defineProps } from 'vue'
import { initFlowbite } from 'flowbite'
const model = defineModel({ default: null })
const props = defineProps({
  placeholder: String,
  label: String,
})
onMounted(() => {
  initFlowbite()
})
</script>
<template>
  <div class="flex-1 min-w-[200px] mb-5">
    <label for="name" class="block mb-2 text-sm font-medium text-[#249CFF] dark:text-white">
      {{ label }}
    </label>
    <input
      v-model="model"
      type="name"
      id="name"
      class="flex-1 bg-white border border-[#249CFF] text-[#75b9f0c4] text-sm rounded-lg focus:ring-blue-300 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
      :placeholder="props.placeholder"
      required
    />
  </div>
</template>
