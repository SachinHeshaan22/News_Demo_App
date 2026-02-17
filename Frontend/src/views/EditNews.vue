<template>
  <div class="edit-news container">
    <div v-if="loading" class="edit-news__loading">
      <div class="spinner"></div>
      <p>Loading news...</p>
    </div>

    <NewsForm
      v-else-if="newsData"
      mode="edit"
      :initial-data="newsData"
      @save="handleSave"
      @publish="handlePublish"
      @unpublish="handleUnpublish"
      @delete="showDeleteConfirm"
      @cancel="handleCancel"
    />

    <ConfirmDialog
      :show="showConfirmDialog"
      title="Delete News"
      message="Are you sure you want to delete this news item? This action cannot be undone."
      confirm-text="Yes"
      cancel-text="No"
      confirm-variant="danger"
      @confirm="handleDelete"
      @cancel="hideDeleteConfirm"
    />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import NewsForm from '../components/NewsForm.vue';
import ConfirmDialog from '../components/ConfirmDialog.vue';
import newsService from '../services/newsService';

export default {
  name: 'EditNews',
  components: {
    NewsForm,
    ConfirmDialog,
  },
  setup() {
    const router = useRouter();
    const route = useRoute();
    const newsData = ref(null);
    const loading = ref(false);
    const showConfirmDialog = ref(false);

    const fetchNews = async () => {
      loading.value = true;
      try {
        const response = await newsService.getNewsById(route.params.id);
        newsData.value = response.data;
      } catch (error) {
        console.error('Error fetching news:', error);
        alert('Failed to load news. Redirecting to dashboard.');
        router.push('/');
      } finally {
        loading.value = false;
      }
    };

    const handleSave = async (formData) => {
      loading.value = true;
      try {
        await newsService.updateNews(route.params.id, formData);
        alert('News updated successfully!');
        router.push('/');
      } catch (error) {
        console.error('Error updating news:', error);
        alert('Failed to update news. Please try again.');
      } finally {
        loading.value = false;
      }
    };

    const handlePublish = async (formData) => {
      loading.value = true;
      try {
        await newsService.updateNews(route.params.id, formData);
        alert('News published successfully!');
        router.push('/');
      } catch (error) {
        console.error('Error publishing news:', error);
        alert('Failed to publish news. Please try again.');
      } finally {
        loading.value = false;
      }
    };

    const handleUnpublish = async () => {
      loading.value = true;
      try {
        await newsService.unpublishNews(route.params.id);
        alert('News unpublished successfully!');
        router.push('/');
      } catch (error) {
        console.error('Error unpublishing news:', error);
        alert('Failed to unpublish news. Please try again.');
      } finally {
        loading.value = false;
      }
    };

    const showDeleteConfirm = () => {
      showConfirmDialog.value = true;
    };

    const hideDeleteConfirm = () => {
      showConfirmDialog.value = false;
    };

    const handleDelete = async () => {
      hideDeleteConfirm();
      loading.value = true;
      try {
        await newsService.deleteNews(route.params.id);
        alert('News deleted successfully!');
        router.push('/');
      } catch (error) {
        console.error('Error deleting news:', error);
        alert('Failed to delete news. Please try again.');
      } finally {
        loading.value = false;
      }
    };

    const handleCancel = () => {
      router.push('/');
    };

    onMounted(() => {
      fetchNews();
    });

    return {
      newsData,
      loading,
      showConfirmDialog,
      handleSave,
      handlePublish,
      handleUnpublish,
      showDeleteConfirm,
      hideDeleteConfirm,
      handleDelete,
      handleCancel,
    };
  },
};
</script>

<style scoped>
.edit-news {
  padding: var(--space-2xl) var(--space-lg);
  min-height: 100vh;
}

.edit-news__loading {
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

/* Responsive */
@media (max-width: 768px) {
  .edit-news {
    padding: var(--space-lg) var(--space-md);
  }
}
</style>
