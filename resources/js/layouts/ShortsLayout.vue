<script setup lang="ts">
// Minimal layout for Shorts - no header, no footer, fullscreen
</script>

<template>
  <div class="shorts-layout">
    <slot />
  </div>
</template>

<style scoped>
.shorts-layout {
  width: 100%;
  height: 100vh;
  height: 100dvh;
  overflow: hidden;
  background: #000;
  margin: 0;
  padding: 0;
}
</style>
