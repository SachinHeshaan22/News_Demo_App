<template>
  <div class="news-dashboard container">
    <header class="news-dashboard__header">
      <h1>News Dashboard</h1>
      <BaseButton variant="success" @click="handleCreateNews">
        + Add Article
      </BaseButton>
    </header>

   
    <div v-if="apiError" class="news-dashboard__error glass-card">
      <h3>Connection Error</h3>
      <BaseButton @click="retryFetch" variant="success">Retry Connection</BaseButton>
    </div>

    <NewsList
      :news="newsList"
      :loading="loading"
      @edit="handleEditNews"
    />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import NewsList from '../components/NewsList.vue';
import BaseButton from '../components/BaseButton.vue';
import newsService from '../services/newsService';


export default {
  name: 'NewsDashboard',
  components: {
    NewsList,
    BaseButton,
  },
  setup() {
    const router = useRouter();
    const newsList = ref([]);
    const loading = ref(false);
    const apiError = ref(false);

    const fetchNews = async () => {
      loading.value = true;
      apiError.value = false;
      console.log('Fetching news from API...');
      try {
        const response = await newsService.getAllNews();
        console.log('API response received:', response);
      
        if (response.data && response.data.success) {
          newsList.value = response.data.data;
          console.log('Successfully loaded', newsList.value.length, 'news items from API');
        } else {
         
          newsList.value = response.data;
          console.log('Loaded news items from API (alternative format)');
        }
      } catch (error) {
        console.error('Error fetching news from API:', error);
        console.error('Error details:', error.response?.data || error.message);
        apiError.value = true;
        
       
        if (newsList.value.length === 0) {
          console.log('API failed and no existing data, showing empty state');
        } else {
          console.log('API failed but keeping existing data');
        }
      } finally {
        loading.value = false;
      }
    };

    const retryFetch = () => {
      fetchNews();
    };

    const handleCreateNews = () => {
      router.push('/create');
    };

    const handleEditNews = (newsId) => {
      router.push(`/edit/${newsId}`);
    };

    onMounted(() => {
      fetchNews();
    });

    return {
      newsList,
      loading,
      apiError,
      handleCreateNews,
      handleEditNews,
      retryFetch,
    };
  },
};
</script>

<style scoped>
.news-dashboard {
  padding: var(--space-2xl) var(--space-lg);
  min-height: 100vh;
}

.news-dashboard__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-2xl);
  gap: var(--space-lg);
}

.news-dashboard__header h1 {
  margin: 0;
  font-size: var(--font-size-4xl);
  color: white;
}

.news-dashboard__error {
  padding: var(--space-xl);
  margin-bottom: var(--space-xl);
  text-align: center;
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.news-dashboard__error h3 {
  margin: 0 0 var(--space-md) 0;
  color: #ef4444;
}

.news-dashboard__error p {
  margin: 0 0 var(--space-lg) 0;
  color: var(--color-text-secondary);
}


@media (max-width: 768px) {
  .news-dashboard {
    padding: var(--space-lg) var(--space-md);
  }

  .news-dashboard__header {
    flex-direction: column;
    align-items: flex-start;
  }

  .news-dashboard__header button {
    width: 100%;
  }
}
</style>