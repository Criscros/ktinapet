<template>
  <div class="shorts-container" ref="container">
    <div
      v-for="(post, index) in posts"
      :key="post.id"
      class="short"
      :data-index="index"
    >
      <video
        :ref="el => videoRefs[index] = el as HTMLVideoElement"
        playsinline
        webkit-playsinline
        preload="metadata"
        loop
        :poster="post.images?.[0] || ''"
        :src="post.video_url || ''"
        @click="togglePlay(index)"
      ></video>

      <div class="progress-wrap">
        <div class="progress">
          <i :style="`width:${progressValues[index] || 0}%`" />
        </div>
      </div>

      <div class="muted-chip" v-show="mutedStates[index]">Muted</div>

      <div class="actions">
        <div class="action-btn" @click.stop="like(index)" title="Like">
          <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 21s-7.5-4.5-9.5-8.5C.9 7.9 4.5 3 8.5 3 10.6 3 12 4.1 12 4.1S13.4 3 15.5 3C19.5 3 23.1 7.9 21.5 12.5 19.5 16.5 12 21 12 21z" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
          <div class="action-label">Like</div>
        </div>

        <div class="action-btn" title="Comment">
          <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
          <div class="action-label">0</div>
        </div>

        <div class="action-btn" :title="mutedStates[index] ? 'Unmute' : 'Mute'" @click.stop="toggleMute(index)">
          <svg v-if="mutedStates[index]" class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 9v6h4l5 4V5l-5 4H5z" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16 8l5 5M21 8l-5 5" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
          </svg>
          <svg v-else class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 9v6h4l5 4V5l-5 4H5z" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M19 12c0-2.8-2.2-5-5-5" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
            <path d="M21 12c0-4-3-7-7-7" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
          </svg>
          <div class="action-label">{{ mutedStates[index] ? 'Unmute' : 'Mute' }}</div>
        </div>

        <div class="action-btn" title="Share">
          <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M8 7l4-4 4 4" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
          <div class="action-label">Share</div>
        </div>

        <div class="action-btn avatar" title="Channel">
          <img src="/logo.jpeg" alt="avatar" />
        </div>
      </div>

      <div class="center-play" v-show="!playingStates[index] && visibleIndex === index">
        <div class="pulse" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M7 6v12l10-6z" fill="#fff"></path>
          </svg>
        </div>
      </div>

      <div class="meta">
        <div class="channel">
          <div class="avatar" style="width:36px;height:36px">
            <img src="/logo.jpeg" alt="channel" />
          </div>
          <div>
            <div class="name">News <span class="subscribe">SUBSCRIBE</span></div>
            <div style="font-size:13px;color:#ccc">@ktinapet</div>
          </div>
        </div>

        <div class="title">{{ post.title }}</div>
        <div class="song" style="margin-top:8px" @click.stop="toggleMute(index)">
          <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='48' height='48'><rect width='100%' height='100%' fill='%2300aaff'/></svg>" alt="song">
          <div>{{ post.tags?.length ? post.tags.join(' • ') : 'Original audio' }}</div>
        </div>
      </div>
    </div>

    <div v-if="!posts?.length" class="short empty">
      <div style="color:#fff;opacity:.9;font-size:14px;">No posts yet.</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, reactive } from 'vue'
import ShortsLayout from '@/layouts/ShortsLayout.vue'

defineOptions({ layout: ShortsLayout })

type Post = {
  id: number
  title: string
  description: string
  tags?: string[] | null
  images?: string[] | null
  video_url?: string | null
  created_at: string
}

const props = defineProps<{ posts: Post[] }>()

const container = ref<HTMLDivElement | null>(null)
const videoRefs = reactive<Record<number, HTMLVideoElement>>({})
const progressValues = reactive<Record<number, number>>({})
const mutedStates = reactive<Record<number, boolean>>({})
const playingStates = reactive<Record<number, boolean>>({})
const visibleIndex = ref(0)

let observer: IntersectionObserver | null = null

const setupIntersectionObserver = () => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        const index = parseInt(entry.target.getAttribute('data-index') || '0')
        const video = videoRefs[index]
        
        if (!video) return

        if (entry.isIntersecting && entry.intersectionRatio > 0.75) {
          // This video is mostly visible - play it
          visibleIndex.value = index
          video.muted = mutedStates[index] ?? true
          video.play().catch(() => {})
          playingStates[index] = true
          
          // Update URL
          updateUrl(props.posts[index].id)
        } else {
          // This video is not visible - pause it
          video.pause()
          playingStates[index] = false
        }
      })
    },
    {
      threshold: [0, 0.25, 0.5, 0.75, 1],
      root: null,
    }
  )

  // Observe all video containers
  const shorts = container.value?.querySelectorAll('.short')
  shorts?.forEach((short) => observer?.observe(short))
}

const updateProgress = (index: number) => {
  const video = videoRefs[index]
  if (!video) return
  const pct = (video.currentTime / Math.max(video.duration || 1, 1)) * 100
  progressValues[index] = pct
}

const togglePlay = (index: number) => {
  const video = videoRefs[index]
  if (!video) return

  if (video.paused) {
    video.play().catch(() => {})
    playingStates[index] = true
  } else {
    video.pause()
    playingStates[index] = false
  }
}

const toggleMute = (index: number) => {
  const video = videoRefs[index]
  if (!video) return
  video.muted = !video.muted
  mutedStates[index] = video.muted
}

const like = (index: number) => {
  // Implement like functionality
  console.log('Liked video', index)
}

const updateUrl = (postId: number) => {
  const url = new URL(window.location.href)
  url.searchParams.set('short', String(postId))
  window.history.replaceState({}, '', url.toString())
}

const scrollToIndex = (index: number) => {
  const shorts = container.value?.querySelectorAll('.short')
  if (shorts && shorts[index]) {
    shorts[index].scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const handleKeyboard = (e: KeyboardEvent) => {
  const total = props.posts?.length || 0
  
  if (e.code === 'Space') {
    e.preventDefault()
    togglePlay(visibleIndex.value)
    return
  }
  if (e.code === 'ArrowDown') {
    e.preventDefault()
    if (visibleIndex.value < total - 1) {
      scrollToIndex(visibleIndex.value + 1)
    }
    return
  }
  if (e.code === 'ArrowUp') {
    e.preventDefault()
    if (visibleIndex.value > 0) {
      scrollToIndex(visibleIndex.value - 1)
    }
    return
  }
  if (e.code === 'KeyM') {
    e.preventDefault()
    toggleMute(visibleIndex.value)
    return
  }
}

onMounted(() => {
  // Initialize muted states
  props.posts?.forEach((_, index) => {
    mutedStates[index] = true
    playingStates[index] = false
  })

  // Setup video event listeners
  Object.keys(videoRefs).forEach((key) => {
    const index = parseInt(key)
    const video = videoRefs[index]
    if (video) {
      video.addEventListener('timeupdate', () => updateProgress(index))
      video.addEventListener('play', () => { playingStates[index] = true })
      video.addEventListener('pause', () => { playingStates[index] = false })
    }
  })

  // Setup IntersectionObserver
  setTimeout(() => {
    setupIntersectionObserver()
    
    // Check URL for initial video
    const url = new URL(window.location.href)
    const shortId = url.searchParams.get('short')
    if (shortId) {
      const index = props.posts?.findIndex(p => String(p.id) === shortId)
      if (index >= 0) {
        scrollToIndex(index)
      }
    }
  }, 100)

  // Keyboard navigation
  window.addEventListener('keydown', handleKeyboard)
})

onBeforeUnmount(() => {
  observer?.disconnect()
  window.removeEventListener('keydown', handleKeyboard)
  
  // Cleanup video listeners
  Object.keys(videoRefs).forEach((key) => {
    const index = parseInt(key)
    const video = videoRefs[index]
    if (video) {
      video.removeEventListener('timeupdate', () => updateProgress(index))
    }
  })
})
</script>

<style scoped>
* { 
  box-sizing: border-box; 
  margin: 0; 
  padding: 0; 
}

body, html {
  margin: 0;
  padding: 0;
  overflow: hidden;
}

.shorts-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh; /* Fallback for older browsers */
  height: 100dvh;
  overflow-y: scroll;
  scroll-snap-type: y mandatory;
  scroll-behavior: smooth;
  -webkit-overflow-scrolling: touch;
  margin: 0;
  padding: 0;
}

.shorts-container::-webkit-scrollbar {
  display: none;
}

.short {
  position: relative;
  width: 100%;
  height: 100vh; /* Fallback for older browsers */
  height: 100dvh;
  scroll-snap-align: start;
  scroll-snap-stop: always;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(180deg, #111, #000);
  margin: 0;
  padding: 0;
}

.short.empty {
  align-items: center;
  justify-content: center;
}

video {
  width: 100%;
  height: 100vh; /* Fallback for older browsers */
  height: 100dvh;
  object-fit: cover;
  display: block;
  background: #000;
}

/* top progress bar */
.progress-wrap {
  position: absolute;
  left: 0;
  right: 0;
  top: 12px;
  padding: 0 10px;
  pointer-events: none;
  z-index: 10;
}
.progress {
  height: 3px;
  background: rgba(255, 255, 255, 0.12);
  border-radius: 3px;
  overflow: hidden;
}
.progress > i {
  display: block;
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, #ff3b30, #ffcc00);
  transition: width 0.1s linear;
}

/* Right-side vertical actions */
.actions {
  position: absolute;
  right: 12px;
  bottom: 90px;
  display: flex;
  flex-direction: column;
  gap: 18px;
  align-items: center;
  z-index: 5;
}
.action-btn {
  width: 52px;
  height: 52px;
  border-radius: 12px;
  background: rgba(0, 0, 0, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  backdrop-filter: blur(6px);
  cursor: pointer;
  transition: transform 0.12s ease;
}
.action-btn:active {
  transform: scale(0.96);
}
.action-btn img {
  width: 26px;
  height: 26px;
  display: block;
}
.action-label {
  font-size: 12px;
  color: #fff;
  margin-top: 6px;
  opacity: 0.95;
}

/* Small profile avatar */
.avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid rgba(255, 255, 255, 0.08);
}
.avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Bottom info area */
.meta {
  position: absolute;
  left: 12px;
  right: 100px;
  bottom: 22px;
  color: #fff;
  z-index: 5;
}
.channel {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 8px;
}
.channel .name {
  font-weight: 600;
}
.subscribe {
  margin-left: 8px;
  padding: 6px 10px;
  border-radius: 999px;
  background: #cc0000;
  color: #fff;
  font-weight: 700;
  font-size: 13px;
}
.title {
  font-size: 16px;
  line-height: 1.2;
  margin-bottom: 6px;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
}
.song {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: #ddd;
}
.song img {
  width: 22px;
  height: 22px;
  border-radius: 4px;
}

/* Play/pause center icon */
.center-play {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 4;
  pointer-events: none;
}
.center-play .pulse {
  width: 92px;
  height: 92px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.28);
  display: flex;
  align-items: center;
  justify-content: center;
}
.center-play svg {
  width: 40px;
  height: 40px;
  opacity: 0.95;
}

/* small helpers */
.muted-chip {
  position: absolute;
  left: 12px;
  top: 12px;
  background: rgba(0, 0, 0, 0.45);
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 12px;
  z-index: 10;
}

/* Responsive tweaks */
@media (min-width: 900px) {
  .short {
    max-width: 600px;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
  }
  .meta {
    right: 120px;
  }
  .actions {
    right: 22px;
  }
}

/* tiny icon SVG placeholders */
.icon {
  width: 26px;
  height: 26px;
  display: block;
}
</style>
