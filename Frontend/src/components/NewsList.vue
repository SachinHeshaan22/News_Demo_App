<template>
  <div class="news-list">
    <div class="news-list__filters glass-card">
      <div class="news-list__search">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search news..."
          class="news-list__search-input"
        />
      </div>
      <div class="news-list__filter-group">
        <select v-model="filterCategory" class="news-list__filter">
          <option value="">All Categories</option>
          <option value="technology">Technology</option>
          <option value="business">Business</option>
          <option value="sports">Sports</option>
          <option value="entertainment">Entertainment</option>
          <option value="health">Health</option>
          <option value="science">Science</option>
          <option value="politics">Politics</option>
          <option value="world">World</option>
        </select>
        <select v-model="filterStatus" class="news-list__filter">
          <option value="">All Status</option>
          <option value="published">Published</option>
          <option value="unpublished">Unpublished</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="news-list__loading">
      <div class="spinner"></div>
      <p>Loading news...</p>
    </div>

    <div v-else-if="filteredNews.length === 0" class="news-list__empty glass-card">
      <h3>No news found</h3>
      <p>{{ searchQuery || filterCategory || filterStatus ? 'Try adjusting your filters' : 'Create your first news item to get started' }}</p>
    </div>

    <div v-else class="news-list__grid">
      <NewsCard
        v-for="news in filteredNews"
        :key="news.id"
        :news="news"
        @edit="handleEdit"
      />
    </div>
  </div>
</template>

<script>
import NewsCard from './NewsCard.vue';

export default {
  name: 'NewsList',
  components: {
    NewsCard,
  },
  props: {
    news: {
      type: Array,
      default: () => [],
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['edit'],
  data() {
    return {
      searchQuery: '',
      filterCategory: '',
      filterStatus: '',
    };
  },
  computed: {
    filteredNews() {
      let filtered = [...this.news];

    
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(
          (item) =>
            item.title.toLowerCase().includes(query) ||
            item.category.toLowerCase().includes(query)
        );
      }

    
      if (this.filterCategory) {
        filtered = filtered.filter((item) => item.category === this.filterCategory);
      }


      if (this.filterStatus) {
        filtered = filtered.filter((item) => item.status === this.filterStatus);
      }

      filtered.sort((a, b) => new Date(b.date) - new Date(a.date));

      return filtered;
    },
  },
  methods: {
    handleEdit(newsId) {
      this.$emit('edit', newsId);
    },
  },
};
</script>

<style scoped>
.news-list {
  width: 100%;
}

.news-list__filters {
  padding: var(--space-lg);
  margin-bottom: var(--space-xl);
  display: flex;
  gap: var(--space-lg);
  align-items: center;
  flex-wrap: wrap;
}

.news-list__search {
  flex: 1;
  min-width: 250px;
}

.news-list__search-input {
  width: 100%;
  padding: var(--space-md);
  background: var(--color-bg-secondary);
  border: 1px solid var(--glass-border);
  border-radius: var(--radius-md);
  color: var(--color-text-primary);
  font-size: var(--font-size-base);
  transition: all var(--transition-base);
}

.news-list__search-input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.news-list__filter-group {
  display: flex;
  gap: var(--space-md);
  flex-wrap: wrap;
}

.news-list__filter {
  min-width: 180px;
  padding: var(--space-md);
  background: var(--color-bg-secondary);
  border: 1px solid var(--glass-border);
  border-radius: var(--radius-md);
  color: var(--color-text-primary);
  font-size: var(--font-size-base);
  cursor: pointer;
  transition: all var(--transition-base);
}

.news-list__filter:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.news-list__loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: var(--space-3xl);
  gap: var(--space-lg);
}

.spinner {
  width: 48px;
  height: 48px;
  border: 4px solid var(--glass-border);
  border-top-color: var(--color-primary);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.news-list__empty {
  text-align: center;
  padding: var(--space-3xl);
}

.news-list__empty h3 {
  margin: 0 0 var(--space-md) 0;
  font-size: var(--font-size-2xl);
  color: var(--color-text-primary);
}

.news-list__empty p {
  margin: 0;
  color: var(--color-text-secondary);
}

.news-list__grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: var(--space-xl);
}

/* Responsive */
@media (max-width: 768px) {
  .news-list__filters {
    flex-direction: column;
    align-items: stretch;
  }

  .news-list__search {
    width: 100%;
  }

  .news-list__filter-group {
    width: 100%;
    flex-direction: column;
  }

  .news-list__filter {
    width: 100%;
  }

  .news-list__grid {
    grid-template-columns: 1fr;
  }
}
</style>
