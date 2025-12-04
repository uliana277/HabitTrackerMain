<script setup>
import { ref, onMounted } from "vue";
import Datepicker from "./Datepicker.vue";
import jsPDF from "jspdf";
import Input from "./Input.vue";
import Calendar from "./Calendar.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    habits: Array,
});

onMounted(() => {
    console.log(props.text);
});

const habits = ref([]);
const datepicker = ref(null);
const pdfExportHabit = ref(null);
const calendar = ref([]);
const currentDateColor = ref(null);
const actualStreak = ref(null);

const form = useForm({
    name: "",
    start: null,
    end: null,
    description: "",
    completed: null,
});

const showForm = ref(true);

onMounted(() => {
    const stored = localStorage.getItem("habits");
    if (stored) {
        habits.value = JSON.parse(stored);
    }
});

const addHabit = (e) => {
    e.preventDefault();
    if (!form.name.trim()) return;

    const startDate = datepicker.value.getStartDate();
    const endDate = datepicker.value.getEndDate();

    form.start = startDate;
    form.end = endDate;

    form.post(route("habit.create"), {
        onSuccess: (res) => {

            habits.value.push({
                id: res.props.id,
                name: form.name,
                date: `${startDate} to ${endDate}`,
                description: form.description,
                isEditing: false,
                completed: [],
                clicked: [],
                progress: 0,
                tempName: form.name,
                tempStartDate: startDate,
                tempEndDate: endDate,
                tempDescription: form.description,
            });
        },
    });

    const habit = habits.value[habits.value.length - 1];

    let lastDate = null;
    let streak = 1;
    let longestStreak = 1;

    habit.completed
        .sort((a, b) => new Date(a) - new Date(b))
        .forEach((date) => {
            if (lastDate) {
                let currentDateObject = new Date(lastDate);
                currentDateObject.setDate(currentDateObject.getDate() + 1);

                if (currentDateObject.getTime() === new Date(date).getTime()) {
                    streak++;
                } else {
                    if (streak > longestStreak) longestStreak = streak;
                    streak = 1;
                }
            }
            lastDate = date;
        });

    let completed = [
        "2025-11-05",
        "2025-11-04",
        "2025-11-03",
        "2025-11-25",
        "2025-11-11",
    ].sort((a, b) => new Date(a) - new Date(b));

    const currentDate = "2025-11-25";
    if (completed.includes(currentDate)) {
        let index = completed.findIndex((date) => date === currentDate);
        let completedBeforeCurrentDate = completed.slice(0, index + 1);
    }

    if (completed.includes(currentDate)) {
        let index = completed.findIndex(current);
        let completedBeforeCurrentDate = [...completed].splice(0, index);
    }

    form.reset();
    datepicker.value.reset();
    showForm.value = false;
};

const deleteHabit = (index) => {
    habits.value.splice(index, 1);
};

const toggleEdit = (index) => {
    const habit = habits.value[index];
    if (!habit.isEditing) {
        habit.tempName = habit.name;
        const [start, end] = habit.date.split(" to ");
        habit.tempStartDate = start;
        habit.tempEndDate = end;
        habit.tempDescription = habit.description;
    }
    habit.isEditing = !habit.isEditing;
};

const completedChanged = (date, completed, index) => {};

const completeHabit = (index) => {
    const habit = habits.value[index];
    const date = new Date().toISOString().split("T")[0];

    habit.completed.push(date);
    if (!habit.clicked.includes(date)) {
        habit.clicked.push(date);
    }
    habit.progress = 100;
    currentDateColor.background = "green";

    const today = new Date().getDate();

    const dateElements = calendar.value[0].$el.querySelectorAll("li");
    dateElements.forEach((li) => {
        if (+li.textContent === today) {
            li.classList.add("selected-day");
            li.classList.remove("unselected-day");
        }
    });
};

const uncompleteHabit = (index) => {
    const habit = habits.value[index];
    const date = new Date().toISOString().split("T")[0];

    habit.completed = habit.completed.filter((d) => d !== date);
    if (!habit.clicked.includes(date)) {
        habit.clicked.push(date);
    }
    habit.progress = 0;

    const today = new Date().getDate();

    const dateElements = calendar.value[0].$el.querySelectorAll("li");
    dateElements.forEach((li) => {
        if (+li.textContent === today) {
            li.classList.add("unselected-day");
            li.classList.remove("selected-day");
        }
    });
};

const pdfHabit = () => {
    if (!pdfExportHabit.value) return;

    let pdfElement = document.createElement("div");

    const newElement = pdfExportHabit.value.cloneNode(true);
    const list = newElement.querySelectorAll(".options");
    list.forEach((el) => el.remove());
    newElement.style.width = "600px";

    const newElementCalendar = calendar.value[0].$el.cloneNode(true);
    newElementCalendar.style.width = "600px";

    pdfElement.appendChild(newElement);
    pdfElement.appendChild(newElementCalendar);

    const doc = new jsPDF({
        orientation: "p",
        format: "a4",
        hotfixes: ["px_scaling"],
        x: 4,
    });
    doc.setFillColor(210, 227, 252);
    doc.setTextColor(210, 227, 252);
    doc.html(pdfElement, {
        x: 45,
        y: 30,
        html2canvas: { scale: 0.2 },
        callback: (doc) => doc.save("habit-tracker.pdf"),
    });
};
localStorage.clear();
</script>

<template>
    <main class="flex-1 p-8">
        <h2 class="text-[40px] text-[#249CFF] font-semibold mb-6">
            Your Habits
        </h2>

        <form
            v-if="showForm"
            @submit.prevent="addHabit"
            class="max-w-sm mx-auto"
        >
            <Input
                v-model="form.name"
                label="Name for a habit"
                placeholder="Drinking a water"
            />
            <Datepicker ref="datepicker" />

            <Input
                v-model="form.description"
                label="Description for a habit"
                placeholder="Drink a liter of water"
            />
            <button
                class="text-white bg-[#90CAFA] px-5 py-2.5 rounded-lg w-full hover:bg-blue-400"
            >
                Add
            </button>
        </form>

        <button
            v-else
            @click="showForm = true"
            class="mb-8 px-4 py-2 border border-[#249CFF] text-[#249CFF] rounded-full hover:bg-[#EAF6FF]"
        >
            +
        </button>

        <div
            v-if="habits.length === 0"
            class="text-center text-[#249CFF] mt-10 text-xl"
        >
            No habits yet.
        </div>
        <div class="w-full flex justify-center">
            <div
                v-if="habits.length === 1"
                class="bg-white w-2xl rounded-3xl shadow-xl p-8 mt-8 mb-8 border border-indigo-100"
            >
                <div class="flex flex-row gap-6 items-center justify-center">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">
                            Your Streak Journey
                        </h2>
                        <p class="text-gray-500">Keep going! 🚀</p>
                    </div>

                    <div
                        class="bg-[#51A7EE] relative rounded-2xl p-8 text-center text-white"
                    >
                        <h1>{{ actualStreak }}⚡︎</h1>
                        <p>Actual Streak</p>
                    </div>
                    <div
                        class="bg-[#F8833D] relative rounded-2xl p-8 text-center text-white"
                    >
                        <h1>{{ longestStreak }}⚡︎</h1>
                        <p>Longest Streak</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6" ref="pdfExportHabit">
            <div
                v-for="(habit, index) in habits"
                :key="index"
                class="habit-card"
            >
                <div
                    class="bg-[#90CAFA] relative rounded-2xl p-8 text-center text-white max-w-2xl mx-auto"
                >
                    <div v-if="habit.isEditing">
                        <input
                            v-model="habit.tempName"
                            class="bg-white text-[#249CFF] px-4 py-2 rounded-md w-[220px]"
                        />
                    </div>
                    <div v-else class="text-[32px] font-bold mb-6">
                        {{ habit.name }}
                    </div>

                    <div
                        class="flex flex-col sm:flex-row justify-center items-center gap-10 mb-8"
                    >
                        <div class="text-center">
                            <p class="font-semibold mb-2">Due date</p>
                            <div
                                v-if="habit.isEditing"
                                class="flex items-center gap-2 justify-center"
                            >
                                <input
                                    v-model="habit.tempStartDate"
                                    type="date"
                                    class="bg-white text-[#249CFF] px-3 py-1 rounded-md"
                                />
                                <span class="text-white font-semibold">to</span>
                                <input
                                    v-model="habit.tempEndDate"
                                    type="date"
                                    class="bg-white text-[#249CFF] px-3 py-1 rounded-md"
                                />
                            </div>
                            <div v-else class="text-[18px] font-semibold">
                                {{ habit.date }}
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="font-semibold mb-2">Description</p>
                            <div v-if="habit.isEditing">
                                <input
                                    v-model="habit.tempDescription"
                                    class="bg-white text-[#249CFF] px-4 py-2 rounded-md w-[220px]"
                                />
                            </div>
                            <div v-else class="text-[18px] font-semibold">
                                {{ habit.description }}
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-6">
                        <p class="font-semibold mb-2">Progress</p>
                        <div
                            class="w-3/4 mx-auto bg-white/40 rounded-full h-3 mb-1 overflow-hidden"
                        >
                            <div
                                class="bg-white h-3 rounded-full transition-all duration-500"
                                :style="{ width: habit.progress + '%' }"
                            ></div>
                        </div>
                        <p class="text-sm">{{ habit.progress }}%</p>
                    </div>

                    <div class="flex justify-center gap-4 mt-4">
                        <button
                            @click="completeHabit(index)"
                            class="bg-green-500 hover:bg-[#69b881] text-white px-6 py-2 rounded-lg"
                        >
                            Completed
                        </button>
                        <button
                            @click="uncompleteHabit(index)"
                            class="bg-red-400 hover:bg-[#d46e6e] text-white px-6 py-2 rounded-lg"
                        >
                            Uncompleted
                        </button>
                    </div>

                    <div class="absolute top-4 right-4 flex space-x-3 options">
                        <button
                            @click="
                                habit.isEditing
                                    ? saveHabit(index)
                                    : toggleEdit(index)
                            "
                            class="text-white hover:text-gray-200 text-xl"
                        >
                            {{ habit.isEditing ? "💾" : "✏️" }}
                        </button>

                        <button
                            @click="deleteHabit(index)"
                            class="text-white hover:text-gray-200 text-xl"
                        >
                            🗑️
                        </button>
                    </div>
                </div>
                <div
                    class="h-[10px] my-[30px] relative rounded-2xl p-8 text-center"
                >
                    <div>
                        <Calendar
                            ref="calendar"
                            :completed="habit.completed"
                            :clicked="habit.clicked"
                            @completedChanged="
                                (date, completed) =>
                                    completedChanged(date, completed, index)
                            "
                        />
                    </div>

                    <div class="mt-20 flex justify-center">
                        <button
                            @click="pdfHabit"
                            class="border p-4 border-[#249CFF] text-[#249CFF] bg-white/80 px-6 py-3 rounded-xl shadow-lg hover:bg-[#EAF6FF] transition-all duration-300"
                        >
                            Import to PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
