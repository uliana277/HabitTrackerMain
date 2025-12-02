<script setup>
import { ref, onMounted, computed } from 'vue'
import { initFlowbite } from 'flowbite'

const datesRef = ref(null)
const prevBtnRef = ref(null)
const nextBtnRef = ref(null)
const monthYearRef = ref(null)
const weekDays = ref(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'])
const normalBg = true

const props = defineProps({
  completed: {
    type: Array,
    default: () => [],
  },
  clicked: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['changeCompleted'])

const isTodayCompleted = computed(() => {
  const date = new Date().toISOString().split('T')[0]
  return props.completed.includes(date)
})

const wasClickedOnce = computed(() => {
  const date = new Date().toISOString().split('T')[0]
  return props.clicked.includes(date)
})

const dmObj = {
  days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
  months: [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  ],
}

const selectedDay = (index) => {
  li.classList.add('selected-day')
  li.classList.remove('unselected-day')
  if (day.clicked) {
    li.classList.add('unselected-day')
    li.classList.remove('selected-day')
  }
}

let dateObj = new Date()
let month = dateObj.getMonth()
let year = dateObj.getFullYear()
const date = dateObj.getDate()
const dayName = dmObj.days[dateObj.getDay()]

const selectDate = (day, completed) => {
  console.log(props.completed)
  emit('changeCompleted', date, completed)
}

const getTodaysDate = computed(() => {
  return `${dayName}, ${dmObj.months[month]} ${date}, ${year}`
})

const fillCalendar = () => {
  if (!datesRef.value || !monthYearRef.value) return

  const daysInMonth = new Date(year, month + 1, 0).getDate()
  const firstDay = new Date(year, month, 1).getDay()

  datesRef.value.innerHTML = ''

  for (let i = 0; i < firstDay; i++) {
    const emptyLi = document.createElement('li')
    emptyLi.textContent = ''
    emptyLi.classList.add('empty-day')
    datesRef.value.appendChild(emptyLi)
  }

  for (let i = 1; i <= daysInMonth; i++) {
    const li = document.createElement('li')
    li.textContent = i
    li.addEventListener('click', () => {
      selectDate(i, true)

      li.classList.add('selected-day')
      li.classList.remove('unselected-day')
    })

    li.addEventListener(
      'contextmenu',
      function (ev) {
        ev.preventDefault()
        selectDate(i, false)

        li.classList.add('unselected-day')
        li.classList.remove('selected-day')

        return false
      },
      false,
    )

    datesRef.value.appendChild(li)
  }
}

onMounted(() => {
  initFlowbite()
  fillCalendar()
})
</script>

<template>
  <div>
    <div
      class="calendar max-h-[1000px] max-w-[500px] text-[#249cff] bg-white shadow-[5px_5px_15px_rgba(0,0,0,0.4)] mx-auto my-0 rounded-[10px] font-poppins-serif"
      ref="pdfExportCalendar"
    >
      <div class="calendar_inner p-5">
        <div class="calendar_controls">
          <div
            class="calendar_heading flex justify-between items-center mb-[15px] text-2xl text-[#249cff]"
          >
            <i
              class="fa-solid fa-arrow-left cursor-pointer text-[1.2rem] text-white transition-[color] duration-[0.2s] text-hover-[#249cff]"
              ref="prevBtnRef"
            ></i>
            <h2 class="month_year" ref="monthYearRef"></h2>
            <i
              class="fa-solid fa-arrow-right cursor-pointer text-[1.2rem] text-white transition-[color] duration-[0.2s] text-hover-[#249cff]"
              ref="nextBtnRef"
            ></i>
          </div>
          <div
            class="current_datetime flex justify-between items-center bg-[#9fd4ff] mb-[15px] px-[15px] py-2.5 rounded-lg"
            :class="{
              'bg-green-400': isTodayCompleted && wasClickedOnce,
              'bg-red-400': !isTodayCompleted && wasClickedOnce,
              'bg-blue-300': !wasClickedOnce,
            }"
          >
            <p
              class="daytxt bg-[#249cff] text-white font-semibold px-2.5 py-[5px] rounded-[5px]"
              :class="{
                'bg-green-500': isTodayCompleted && wasClickedOnce,
                'bg-red-500': !isTodayCompleted && wasClickedOnce,
                'bg-blue-400': !wasClickedOnce,
              }"
            >
              {{ isTodayCompleted ? 'Completed' : 'Uncompleted' }}
            </p>
            <p class="datetxt text-white font-medium">
              {{ getTodaysDate }}
            </p>
          </div>
          <div class="days_date">
            <ul class="days grid grid-cols-7 gap-[5px] mb-1">
              <li
                class="border-b-2 border-[#249cffbd] p-1 font-semibold text-center list-none"
                v-for="weekDay in weekDays"
              >
                {{ weekDay }}
              </li>
            </ul>
            <ul class="dates grid grid-cols-7 gap-4" ref="datesRef"></ul>
            <li
              class="list-none p-[10px] text-center cursor-pointer rounded-md border-[#249cffbd] ease-all select-none"
            ></li>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.dates li:hover {
  background-color: #8edfff;
  color: #ffffff;
}

.dates li:active {
  background-color: #03a9f4;
  color: #fff;
}

.selected-day {
  background-color: #8be28b !important;
  color: #ffffff !important;
}
.unselected-day {
  background-color: #e47e7e !important;
  color: #fff !important;
}

@media (max-width: 500px) {
  .calendar {
    max-width: 100%;
  }
  .calendar_inner {
    padding: 15px;
  }
  .days_date .days li,
  .dates li {
    padding: 8px 0;
  }
}
</style>
