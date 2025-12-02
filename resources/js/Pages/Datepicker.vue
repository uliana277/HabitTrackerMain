<template>
  <div class="flex items-center">
    <div class="relative">
      <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg
          class="w-4 h-4 text-[#249CFF] dark:text-[#1A86E0]"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path
            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"
          />
        </svg>
      </div>
      <input
        ref="startDateElement"
        name="start"
        type="text"
        class="bg-white border border-[#249cffa2] text-[#249cffa2] text-sm rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Select date start"
      />
    </div>
    <span class="mx-4 text-[#249CFF]">to</span>
    <div class="relative">
      <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg
          class="w-4 h-4 text-[#249CFF] dark:text-[#1A86E0]"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path
            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"
          />
        </svg>
      </div>
      <input
        ref="endDateElement"
        name="end"
        type="text"
        class="bg-white border border-[#249cffa2] text-[#249cffa2] text-sm rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Select date end"
      />
    </div>
  </div>
</template>

<script setup>
import { Datepicker } from 'flowbite'
import { onMounted, ref } from 'vue'

const endDatePicker = ref(null)
const endDateElement = ref(null)
const startDatePicker = ref(null)
const startDateElement = ref(null)

const getEndDate = () => {
  return endDatePicker.value.getDate().toLocaleDateString()
}

const getStartDate = () => {
  return startDatePicker.value.getDate().toLocaleDateString()
}

const reset = () => {
  endDatePicker.value.setDate('')
  startDatePicker.value.setDate('')
}

onMounted(() => {
  endDatePicker.value = new Datepicker(endDateElement.value)
  startDatePicker.value = new Datepicker(startDateElement.value)
})

defineExpose({
  getEndDate,
  getStartDate,
  reset,
})
</script>
