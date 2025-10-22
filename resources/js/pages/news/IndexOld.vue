<template>
  <div
    v-if="current"
    class="short"
    ref="shortEl"
    @click="onShortClick"
    @wheel="onWheel"
    @touchstart="onTouchStart"
    @touchend="onTouchEnd"
  >
    <video
      ref="video"
      playsinline
      webkit-playsinline
      preload="metadata"
      :poster="poster"
      :src="src"
    ></video>

    <div class="progress-wrap">
      <div class="progress"><i :style="`width:${progress}%`" /></div>
    </div>

    <div class="muted-chip" v-show="videoRef?.muted">Muted</div>

    <div class="actions">
      <div class="action-btn" @click.stop="like" title="Like">
        <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s-7.5-4.5-9.5-8.5C.9 7.9 4.5 3 8.5 3 10.6 3 12 4.1 12 4.1S13.4 3 15.5 3C19.5 3 23.1 7.9 21.5 12.5 19.5 16.5 12 21 12 21z" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <div class="action-label">{{ likes }}</div>
      </div>

      <div class="action-btn" title="Comment">
        <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <div class="action-label">{{ comments }}</div>
      </div>

      <div class="action-btn" :title="videoRef?.muted ? 'Unmute' : 'Mute'" @click.stop="toggleMute">
        <svg v-if="videoRef?.muted" class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M5 9v6h4l5 4V5l-5 4H5z" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M16 8l5 5M21 8l-5 5" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
        </svg>
        <svg v-else class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M5 9v6h4l5 4V5l-5 4H5z" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M19 12c0-2.8-2.2-5-5-5" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
          <path d="M21 12c0-4-3-7-7-7" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
        </svg>
        <div class="action-label">{{ videoRef?.muted ? 'Unmute' : 'Mute' }}</div>
      </div>

      <div class="action-btn" title="Share">
        <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8 7l4-4 4 4" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <div class="action-label">Share</div>
      </div>

      <div class="action-btn avatar" title="Channel">
        <img :src="avatar" alt="avatar" />
      </div>
    </div>

    <div class="center-play" v-show="showCenter">
      <div class="pulse" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 6v12l10-6z" fill="#fff"></path></svg>
      </div>
    </div>

    <div class="meta">
      <div class="channel">
        <div class="avatar" style="width:36px;height:36px"><img :src="avatar" alt="channel" /></div>
        <div>
          <div class="name">{{ channelName }} <span class="subscribe">SUBSCRIBE</span></div>
          <div style="font-size:13px;color:#ccc">@{{ channelHandle }} · {{ views }}</div>
        </div>
      </div>

      <div class="title">{{ title }}</div>
      <div class="song" style="margin-top:8px" @click.stop="toggleMute">
        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='48' height='48'><rect width='100%' height='100%' fill='%2300aaff'/></svg>" alt="song">
        <div>{{ song }}</div>
      </div>
    </div>
  </div>
  <div v-else class="short" style="align-items:center;justify-content:center;">
    <div style="color:#fff;opacity:.9;font-size:14px;">No posts yet.</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed, watch, nextTick } from 'vue'
import PublicLayout from '@/layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

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

const currentIndex = ref(0)
const total = computed(() => props.posts?.length ?? 0)
const current = computed(() => props.posts?.[currentIndex.value] ?? null)

const src = computed(() => current.value?.video_url || '')
const poster = computed(() => (current.value?.images?.[0] as string | undefined) || '')
const title = computed(() => current.value?.title || '')
const channelName = computed(() => 'News')
const channelHandle = computed(() => 'ktinapet')
const views = computed(() => '')
const song = computed(() => (current.value?.tags?.length ? current.value.tags.join(' • ') : 'Original audio'))
const avatar = computed(() => '/logo.jpeg')

const likes = ref('')
const comments = ref(0)

const video = ref<HTMLVideoElement | null>(null)
const videoRef = ref<HTMLVideoElement | null>(null)
const shortEl = ref<HTMLDivElement | null>(null)
const progress = ref(0)
const showCenter = ref(true)

const getIndexById = (id: string | number) => {
  const list = props.posts || []
  return list.findIndex(p => String(p.id) === String(id))
}

const updateUrl = (postId: number) => {
  const url = new URL(window.location.href)
  url.searchParams.set('short', String(postId))
  window.history.replaceState({}, '', url.toString())
}

const goTo = async (i: number) => {
  if (i < 0 || i >= total.value) return
  currentIndex.value = i
  await nextTick()
  if (!videoRef.value) return
  try {
    videoRef.value.muted = true
    // force reload when src changed by computed
    videoRef.value.load()
    await videoRef.value.play()
    showCenter.value = false
    if (current.value) updateUrl(current.value.id)
  } catch (_) {}
}

const handleNext = () => goTo(currentIndex.value + 1)
const handlePrev = () => goTo(currentIndex.value - 1)

function onShortClick(e: MouseEvent) {
  // ignore clicks on action buttons
  const target = e.target as HTMLElement | null
  if (target && target.closest('.action-btn')) return
  if (!videoRef.value) return

  if (videoRef.value.paused) {
    videoRef.value.play().catch(() => {})
    showCenter.value = false
  } else {
    videoRef.value.pause()
    showCenter.value = true
  }
}

function updateProgress() {
  if (!videoRef.value) return
  const pct = (videoRef.value.currentTime / Math.max(videoRef.value.duration || 1, 1)) * 100
  progress.value = pct
}

function toggleMute() {
  if (!videoRef.value) return
  videoRef.value.muted = !videoRef.value.muted
}

function like() {
  // small animation handled by CSS; increment a number if you want
  // simple visual bump
  // if likes is like '1.2K', you might want to parse and increment realistically
  const anim = document.querySelector('.action-btn')
  // no-op for now
}

onMounted(() => {
  videoRef.value = video.value
  if (!videoRef.value) return
  videoRef.value.addEventListener('timeupdate', updateProgress)
  videoRef.value.addEventListener('play', () => { showCenter.value = false })
  videoRef.value.addEventListener('pause', () => { showCenter.value = true })
  videoRef.value.addEventListener('loadedmetadata', () => { progress.value = 0 })
  videoRef.value.addEventListener('ended', () => { handleNext() })
  window.addEventListener('keydown', onKey)
  // initialize from URL if ?short is present
  const url = new URL(window.location.href)
  const shortId = url.searchParams.get('short')
  if (shortId) {
    const idx = getIndexById(shortId)
    if (idx >= 0) {
      // jump without creating history entry
      goTo(idx)
      return
    }
  }
  // ensure URL reflects initial post
  if (current.value) updateUrl(current.value.id)
})

onBeforeUnmount(() => {
  if (videoRef.value) {
    videoRef.value.removeEventListener('timeupdate', updateProgress)
  }
  window.removeEventListener('keydown', onKey)
})

function onKey(e: KeyboardEvent) {
  if (e.code === 'Space') {
    e.preventDefault()
    if (!videoRef.value) return
    if (videoRef.value.paused) videoRef.value.play(); else videoRef.value.pause()
    return
  }
  if (e.code === 'ArrowDown') { e.preventDefault(); handleNext(); return }
  if (e.code === 'ArrowUp') { e.preventDefault(); handlePrev(); return }
  if (e.code === 'KeyM') { e.preventDefault(); toggleMute(); return }
}

let wheelLocked = false
function onWheel(e: WheelEvent) {
  if (wheelLocked) return
  const dy = e.deltaY
  if (Math.abs(dy) < 20) return
  wheelLocked = true
  if (dy > 0) handleNext(); else handlePrev()
  setTimeout(() => { wheelLocked = false }, 420)
}

let touchStartY = 0
function onTouchStart(e: TouchEvent) {
  if (!e.touches || e.touches.length === 0) return
  touchStartY = e.touches[0].clientY
}
function onTouchEnd(e: TouchEvent) {
  if (!e.changedTouches || e.changedTouches.length === 0) return
  const dy = e.changedTouches[0].clientY - touchStartY
  if (Math.abs(dy) < 40) return
  if (dy < 0) handleNext(); else handlePrev()
}
</script>

<style scoped>
/* Reset */
*{box-sizing:border-box;margin:0;padding:0}
html,body,#app{height:100%}
body{font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,'Helvetica Neue',Arial;background:#000;color:#fff}

/* App container - vertical / full-screen short */
.short {
  position:relative;
  width:100%;
  height:100vh;
  overflow:hidden;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(180deg,#111,#000);
}

video {
  width:100%;
  height:100vh;
  object-fit:cover;
  display:block;
  background:#000;
}

/* top progress bar */
.progress-wrap{
  position:absolute;left:0;right:0;top:12px;padding:0 10px;pointer-events:none
}
.progress{
  height:3px;background:rgba(255,255,255,0.12);border-radius:3px;overflow:hidden
}
.progress > i{
  display:block;height:100%;width:0%;background:linear-gradient(90deg,#ff3b30,#ffcc00);transition:width 0.1s linear
}

/* Right-side vertical actions (like, comment, share, avatar) */
.actions{
  position:absolute;right:12px;bottom:90px;display:flex;flex-direction:column;gap:18px;align-items:center;z-index:5
}
.action-btn{
  width:52px;height:52px;border-radius:12px;background:rgba(0,0,0,0.45);display:flex;align-items:center;justify-content:center;flex-direction:column;backdrop-filter:blur(6px);cursor:pointer;transition:transform .12s ease
}
.action-btn:active{transform:scale(.96)}
.action-btn img{width:26px;height:26px;display:block}
.action-label{font-size:12px;color:#fff;margin-top:6px;opacity:.95}

/* Small profile avatar */
.avatar{width:48px;height:48px;border-radius:50%;overflow:hidden;border:2px solid rgba(255,255,255,0.08)}
.avatar img{width:100%;height:100%;object-fit:cover}

/* Bottom info area */
.meta{
  position:absolute;left:12px;right:100px;bottom:22px;color:#fff;z-index:5
}
.channel{display:flex;align-items:center;gap:10px;margin-bottom:8px}
.channel .name{font-weight:600}
.subscribe{margin-left:8px;padding:6px 10px;border-radius:999px;background:#cc0000;color:#fff;font-weight:700;font-size:13px}
.title{font-size:16px;line-height:1.2;margin-bottom:6px;text-shadow:0 2px 10px rgba(0,0,0,0.6)}
.song{display:flex;align-items:center;gap:8px;font-size:13px;color:#ddd}
.song img{width:22px;height:22px;border-radius:4px}

/* Play/pause center icon */
.center-play{
  position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:4;pointer-events:none
}
.center-play .pulse{width:92px;height:92px;border-radius:50%;background:rgba(0,0,0,0.28);display:flex;align-items:center;justify-content:center}
.center-play svg{width:40px;height:40px;opacity:.95}

/* small helpers */
.muted-chip{position:absolute;left:12px;top:12px;background:rgba(0,0,0,0.45);padding:6px 10px;border-radius:999px;font-size:12px}

/* Responsive tweaks */
@media (min-width:900px){
  .short{max-width:600px;margin:0 auto;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.6)}
  .meta{right:120px}
  .actions{right:22px}
}

/* tiny icon SVG placeholders */
.icon{width:26px;height:26px;display:block}
</style>
