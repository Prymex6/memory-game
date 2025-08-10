<script setup>
import { ref, onMounted } from 'vue'

const symbols = ['🍎','🍌','🍓','🍒','🥝','🍍','🍇','🥥']

const cards = ref([])
const flipped = ref([])
const matched = ref([])
const moves = ref(0)
const time = ref(0)
const gameOver = ref(false)
let timerInterval = null

const shuffleCards = () => {
  const deck = [...symbols, ...symbols]
    .sort(() => Math.random() - 0.5)
    .map((symbol, idx) => ({ id: idx, symbol }))
  cards.value = deck
}

const startGame = () => {
  shuffleCards()
  flipped.value = []
  matched.value = []
  moves.value = 0
  time.value = 0
  gameOver.value = false
  clearInterval(timerInterval)
  timerInterval = setInterval(() => time.value++, 1000)
}

const flipCard = (idx) => {
  if (flipped.value.includes(idx) || matched.value.includes(idx) || flipped.value.length === 2 || gameOver.value) return

  flipped.value.push(idx)

  if (flipped.value.length === 2) {
    moves.value++
    const [i1, i2] = flipped.value
    if (cards.value[i1].symbol === cards.value[i2].symbol) {
      matched.value.push(i1, i2)
      flipped.value = []
      if (matched.value.length === cards.value.length) {
        clearInterval(timerInterval)
        gameOver.value = true
      }
    } else {
      setTimeout(() => flipped.value = [], 900)
    }
  }
}

onMounted(() => startGame())
</script>

<template>
  <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center bg-light py-2">
    <h1 class="mb-3 text-primary fw-bold user-select-none">🧠 Gra Pamięć</h1>

    <div class="d-flex flex-wrap justify-content-center gap-3 w-100 max-w-600px">
      <div v-for="(card, idx) in cards" :key="card.id"
        class="shadow-sm rounded-3 cursor-pointer d-flex justify-content-center align-items-center transition-colors"
        :class="{
          'bg-white': flipped.includes(idx),
          'bg-success text-white': matched.includes(idx),
          'bg-secondary': !(flipped.includes(idx) || matched.includes(idx))
        }"
        style="width: 110px; height: 110px; font-size: 3.5rem; user-select: none; transition: background-color 0.5s ease;"
        @click="flipCard(idx)"
      >
        <span v-if="flipped.includes(idx) || matched.includes(idx)">{{ card.symbol }}</span>
        <span v-else class="text-white">❓</span>
      </div>
    </div>

    <div class="mt-3 d-flex flex-column flex-sm-row gap-4 text-primary fs-5 fw-semibold user-select-none">
      <div>🎯 Ruchy: <span class="fw-bold">{{ moves }}</span></div>
      <div>⏱️ Czas: <span class="fw-bold">{{ time }} s</span></div>
    </div>

    <div v-if="gameOver" class="mt-4 p-4 bg-light border border-success rounded text-center w-100 max-w-600px">
      <h2 class="text-success mb-3">🎉 Gratulacje! Ukończyłeś grę!</h2>
      <button @click="startGame" class="btn btn-success btn-lg">🔄 Zagraj ponownie</button>
    </div>

    <button v-else @click="startGame" class="btn btn-primary btn-lg mt-3 user-select-none">
      🔄 Nowa gra
    </button>
  </div>
</template>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
.max-w-600px {
  max-width: 600px;
}
</style>
