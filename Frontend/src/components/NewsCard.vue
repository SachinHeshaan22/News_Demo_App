<template>
  <div class="news-card glass-card hover-lift">
    <div class="news-card__image">
      <img 
        :src="getImageUrl(news.image_url) || placeholderImage" 
        :alt="news.title" 
        @error="handleImageError"
      />
      <div :class="['news-card__status', `news-card__status--${news.status}`]">
        {{ news.status === 'published' ? 'Public' : 'Unpublic' }}
      </div>
    </div>
    <div class="news-card__content">
      <div class="news-card__meta">
        <span class="news-card__category">{{ news.category }}</span>
        <span class="news-card__date">{{ formatDate(news.date) }}</span>
      </div>
      <h3 class="news-card__title">{{ news.title }}</h3>
      <a v-if="news.url" :href="news.url" target="_blank" class="news-card__link">
        View Article →
      </a>
    </div>
    <div class="news-card__actions">
      <BaseButton variant="secondary" @click="handleEdit">
        Edit
      </BaseButton>
    </div>
  </div>
</template>

<script>
import BaseButton from './BaseButton.vue';
import placeholderImg from '../assets/vue.svg'; 

export default {
  name: 'NewsCard',
  components: {
    BaseButton,
  },
  props: {
    news: {
      type: Object,
      required: true,
    },
  },
  emits: ['edit'],
  data() {
    return {
      placeholderImage: placeholderImg,
    };
  },
  methods: {
    handleEdit() {
      this.$emit('edit', this.news.id);
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      });
    },
    handleImageError(event) {
      event.target.src = this.placeholderImage;
    },
    getImageUrl(imagePath) {
      if (!imagePath) return null;
 
      if (imagePath.startsWith('http')) {
        return imagePath;
      }
    
      if (imagePath.startsWith('storage/') || imagePath.startsWith('/storage/')) {
    
        const cleanPath = imagePath.startsWith('/') ? imagePath.substring(1) : imagePath;
        return `http://localhost:8000/${cleanPath}`;
      }
    
      return imagePath;
    },
  },
};
</script>

<style scoped>
.news-card {
  display: flex;
  flex-direction: column;
  overflow: hidden;
  cursor: pointer;
}

.news-card__image {
  position: relative;
  width: 100%;
  height: 200px;
  overflow: hidden;
  background: var(--color-bg-secondary);
}

.news-card__image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform var(--transition-slow);
}

.news-card:hover .news-card__image img {
  transform: scale(1.05);
}

.news-card__status {
  position: absolute;
  top: var(--space-md);
  right: var(--space-md);
  padding: var(--space-xs) var(--space-md);
  border-radius: var(--radius-full);
  font-size: var(--font-size-xs);
  font-weight: 600;
  text-transform: uppercase;
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}

.news-card__status--published {
  background: rgba(34, 197, 94, 0.2);
  color: var(--color-success-light);
  border: 1px solid var(--color-success);
}

.news-card__status--unpublished {
  background: rgba(251, 146, 60, 0.2);
  color: var(--color-warning);
  border: 1px solid var(--color-warning);
}

.news-card__content {
  padding: var(--space-lg);
  flex: 1;
}

.news-card__meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-md);
  gap: var(--space-sm);
}

.news-card__category {
  display: inline-block;
  padding: var(--space-xs) var(--space-sm);
  background: var(--gradient-primary);
  color: white;
  font-size: var(--font-size-xs);
  font-weight: 600;
  border-radius: var(--radius-sm);
  text-transform: uppercase;
}

.news-card__date {
  font-size: var(--font-size-sm);
  color: var(--color-text-tertiary);
}

.news-card__title {
  margin: 0 0 var(--space-md) 0;
  font-size: var(--font-size-lg);
  font-weight: 700;
  color: var(--color-text-primary);
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.news-card__link {
  display: inline-block;
  color: var(--color-primary-light);
  text-decoration: none;
  font-size: var(--font-size-sm);
  font-weight: 500;
  transition: color var(--transition-base);
}

.news-card__link:hover {
  color: var(--color-primary);
}

.news-card__actions {
  padding: var(--space-md) var(--space-lg);
  border-top: 1px solid var(--glass-border);
  display: flex;
  gap: var(--space-sm);
}

/* Responsive */
@media (max-width: 768px) {
  .news-card__image {
    height: 180px;
  }

  .news-card__meta {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>