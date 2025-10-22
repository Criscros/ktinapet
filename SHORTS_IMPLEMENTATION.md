# YouTube Shorts Implementation Guide

## Key Improvements in IndexImproved.vue

### 1. ✅ IntersectionObserver for Visibility-Based Playback
**What it does:** Automatically plays/pauses videos based on visibility
```javascript
new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.intersectionRatio > 0.75) {
      // Video is 75%+ visible - play it
      video.play()
    } else {
      // Video not visible - pause it
      video.pause()
    }
  })
}, { threshold: [0, 0.25, 0.5, 0.75, 1] })
```

**Benefits:**
- Only plays the visible video
- Saves bandwidth and CPU
- Smooth automatic transitions
- Better mobile experience

### 2. ✅ Scroll Snap for Smooth Navigation
**What it does:** Makes scrolling snap to each video
```css
.shorts-container {
  scroll-snap-type: y mandatory;
}
.short {
  scroll-snap-align: start;
  scroll-snap-stop: always;
}
```

**Benefits:**
- Native smooth scrolling
- One video per viewport
- Works great on touch devices
- No custom scroll physics needed

### 3. ✅ Multiple Video Elements (Not Single Switcher)
**Old approach (your current):**
- One `<video>` element
- Change `src` when navigating
- Causes reload/flicker

**New approach:**
```vue
<div v-for="(post, index) in posts" class="short">
  <video :ref="el => videoRefs[index] = el" />
</div>
```

**Benefits:**
- All videos rendered (lazily loaded with `preload="metadata"`)
- Smooth transitions
- IntersectionObserver handles play/pause
- No flickering

### 4. ✅ Better State Management
```javascript
const mutedStates = reactive<Record<number, boolean>>({})
const playingStates = reactive<Record<number, boolean>>({})
const progressValues = reactive<Record<number, number>>({})
```

Each video has its own state, making it easier to track and manage.

### 5. ✅ Native Scroll Instead of Manual Navigation
Users can:
- Swipe naturally (mobile)
- Scroll with mouse wheel
- Use keyboard arrows (scrolls to next video)

## Comparison: Current vs Improved

| Feature | Current (Index.vue) | Improved (IndexImproved.vue) |
|---------|-------------------|------------------------------|
| Video Loading | Single video, switches src | Multiple videos, lazy loaded |
| Playback Control | Manual in `goTo()` | Automatic via IntersectionObserver |
| Navigation | Custom wheel/touch events | Native scroll + snap |
| Performance | Good | Better (only visible plays) |
| Smoothness | Some flicker on switch | Seamless transitions |
| Code Complexity | Medium | Lower (browser does work) |

## How to Use

### Replace your current implementation:
1. Rename `Index.vue` → `IndexOld.vue` (backup)
2. Rename `IndexImproved.vue` → `Index.vue`
3. Test on both mobile and desktop

### Or keep both and test:
Update your route to point to the improved version:
```php
Route::get('/news-improved', [MultimediaController::class, 'newsImproved'])
```

## Best Practices Applied

### ✅ 1. Vertical Full-Viewport Layout
- Each `.short` is `height: 100vh`
- Container uses `scroll-snap-type: y mandatory`

### ✅ 2. Autoplay & Muted
- Videos start `muted: true`
- Play on intersection (>75% visible)
- User can unmute

### ✅ 3. Visibility-Based Playback
- IntersectionObserver with 75% threshold
- Pauses when scrolled away

### ✅ 4. Fast Navigation
- Native scroll (performant)
- Keyboard shortcuts (Space, ↑, ↓, M)
- Smooth scroll behavior

### ✅ 5. Lightweight Loading
- `preload="metadata"` on all videos
- Full video loads only when near viewport
- Browser handles lazy loading

### ✅ 6. Overlay UI
- All controls use `position: absolute`
- Z-index layering
- Non-blocking interactions

## Performance Tips

### 1. Limit Rendered Videos
If you have 100+ videos, don't render all at once:
```vue
<div v-for="(post, index) in visiblePosts" />
```
Use virtual scrolling or render only ±2 videos around current.

### 2. Lazy Load Video Sources
```vue
<video :src="shouldLoad(index) ? post.video_url : ''" />
```

### 3. Cleanup on Unmount
```javascript
onBeforeUnmount(() => {
  observer?.disconnect()
  // Remove all event listeners
})
```

## Mobile Optimizations

### Touch-friendly buttons
```css
.action-btn {
  min-width: 44px;  /* Apple's recommended touch target */
  min-height: 44px;
}
```

### Prevent bounce scrolling
```css
.shorts-container {
  overscroll-behavior: contain;
}
```

### Hide scrollbar
```css
.shorts-container::-webkit-scrollbar {
  display: none;
}
```

## Testing Checklist

- [ ] Videos autoplay when scrolled into view
- [ ] Only one video plays at a time
- [ ] Videos pause when scrolled away
- [ ] Keyboard navigation works (Space, ↑, ↓, M)
- [ ] Touch swipe works on mobile
- [ ] Mute/unmute persists per video
- [ ] Progress bar updates correctly
- [ ] URL updates with `?short=ID`
- [ ] Deep linking works (direct URL to specific video)
- [ ] Performance is smooth (60fps scrolling)

## Next Steps

### Advanced Features to Add:
1. **Prefetch next video** - Start loading next video when current is 50% played
2. **Double-tap to like** - YouTube Shorts style interaction
3. **Comments drawer** - Bottom sheet that slides up
4. **Share sheet** - Native share API
5. **Analytics** - Track views, completion rate, engagement
6. **Infinite scroll** - Load more videos when reaching end

### Backend Integration:
```php
// Track video views
Route::post('/api/shorts/{id}/view', [ShortController::class, 'trackView']);

// Track engagement
Route::post('/api/shorts/{id}/like', [ShortController::class, 'like']);
Route::post('/api/shorts/{id}/comment', [ShortController::class, 'comment']);
```

## Resources

- [IntersectionObserver MDN](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
- [CSS Scroll Snap](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Scroll_Snap)
- [Video Autoplay Policies](https://developer.mozilla.org/en-US/docs/Web/Media/Autoplay_guide)
