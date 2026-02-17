<template>
  <div class="create-news container">
    <NewsForm
      mode="create"
      @save="handleSave"
      @cancel="handleCancel"
    />
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import NewsForm from '../components/NewsForm.vue';
import newsService from '../services/newsService';

export default {
  name: 'CreateNews',
  components: {
    NewsForm,
  },
  setup() {
    const router = useRouter();
    const loading = ref(false);

    const handleSave = async (formData) => {
      loading.value = true;
      try {
        console.log('Sending form data:', formData);
        const response = await newsService.createNews(formData);
        console.log('Response:', response);
        alert('News saved successfully!');
        router.push('/');
      } catch (error) {
        console.error('Error saving news:', error);
        console.error('Error response:', error.response);
        if (error.response && error.response.data) {
          console.error('Error data:', error.response.data);
          alert(`Failed to save news: ${error.response.data.message || error.message}`);
        } else {
          alert('Failed to save news. Please try again.');
        }
      } finally {
        loading.value = false;
      }
    };



    const handleCancel = () => {
      router.push('/');
    };

    return {
      loading,
      handleSave,
      handleCancel,
    };
  },
};
</script>

<style scoped>
.create-news {
  padding: var(--space-2xl) var(--space-lg);
  min-height: 100vh;
}

/* Responsive */
@media (max-width: 768px) {
  .create-news {
    padding: var(--space-lg) var(--space-md);
  }
}
</style>
