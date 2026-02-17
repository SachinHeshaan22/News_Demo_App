<template>
  <form class="news-form glass-card" @submit.prevent="handleSubmit">
    <h2 class="news-form__title">{{ mode === 'create' ? 'Create News' : 'Edit News' }}</h2>

    <div class="news-form__grid">
      <!-- Date -->
      <div class="news-form__field">
        <label for="date">Date *</label>
        <input
          id="date"
          v-model="formData.date"
          type="date"
          required
          :max="today"
        />
        <span v-if="errors.date" class="news-form__error">{{ errors.date }}</span>
      </div>

      <!-- Category -->
      <div class="news-form__field">
        <label for="category">Category *</label>
        <select id="category" v-model="formData.category" required>
          <option value="" disabled>Select a category</option>
          <option value="technology">Technology</option>
          <option value="business">Business</option>
          <option value="sports">Sports</option>
          <option value="entertainment">Entertainment</option>
          <option value="health">Health</option>
          <option value="science">Science</option>
          <option value="politics">Politics</option>
          <option value="world">World</option>
        </select>
        <span v-if="errors.category" class="news-form__error">{{ errors.category }}</span>
      </div>

      <!-- Status -->
      <div class="news-form__field">
        <label for="status">Status *</label>
        <select id="status" v-model="formData.status" required>
          <option value="published">Public</option>
          <option value="unpublished">Unpublic</option>
        </select>
        <span v-if="errors.status" class="news-form__error">{{ errors.status }}</span>
      </div>

      <!-- Title -->
      <div class="news-form__field news-form__field--full">
        <label for="title">Title *</label>
        <input
          id="title"
          v-model="formData.title"
          type="text"
          placeholder="Enter news title"
          required
          maxlength="200"
        />
        <span v-if="errors.title" class="news-form__error">{{ errors.title }}</span>
      </div>

      <!-- URL -->
      <div class="news-form__field news-form__field--full">
        <label for="url">URL *</label>
        <input
          id="url"
          v-model="formData.url"
          type="url"
          placeholder="https://example.com/article"
          required
        />
        <span v-if="errors.url" class="news-form__error">{{ errors.url }}</span>
      </div>

      <!-- Image Upload -->
      <div class="news-form__field news-form__field--full">
        <label for="image">Image {{ mode === 'create' ? '*' : '(Optional)' }}</label>
        <div class="news-form__image-upload">
          <input
            id="image"
            ref="imageInput"
            type="file"
            accept="image/*"
            :required="mode === 'create'"
            @change="handleImageChange"
          />
          <div v-if="imagePreview" class="news-form__image-preview">
            <img :src="imagePreview" alt="Preview" />
            <button type="button" class="news-form__image-remove" @click="removeImage">
              ✕
            </button>
          </div>
        </div>
        <span v-if="errors.image" class="news-form__error">{{ errors.image }}</span>
        <span class="news-form__hint">Maximum file size: 5MB. Supported formats: JPG, PNG, GIF, WebP</span>
      </div>
    </div>

    <!-- Actions -->
    <div class="news-form__actions">
      <BaseButton variant="secondary" type="button" @click="handleCancel">
        Cancel
      </BaseButton>
      
      <div class="news-form__actions-right">
        <BaseButton
          v-if="mode === 'edit'"
          variant="danger"
          type="button"
          @click="handleDelete"
        >
          Delete
        </BaseButton>
        
        <BaseButton
          variant="success"
          type="submit"
          @click="handleSave"
          :loading="loading"
        >
          {{ mode === 'create' ? 'Add Article' : 'Save Changes' }}
        </BaseButton>
      </div>
    </div>
  </form>
</template>

<script>
import BaseButton from './BaseButton.vue';

export default {
  name: 'NewsForm',
  components: {
    BaseButton,
  },
  props: {
    mode: {
      type: String,
      required: true,
      validator: (value) => ['create', 'edit'].includes(value),
    },
    initialData: {
      type: Object,
      default: () => ({}),
    },
  },
  emits: ['save', 'delete', 'cancel'],
  data() {
    return {
      formData: {
        date: '',
        title: '',
        category: '',
        url: '',
        image: null,
        status: 'unpublished',
      },
      imagePreview: null,
      currentStatus: 'unpublished',
      errors: {},
      loading: false,
      today: new Date().toISOString().split('T')[0],
    };
  },
  mounted() {
    if (this.mode === 'edit' && this.initialData) {
      this.formData = {
        date: this.initialData.date || '',
        title: this.initialData.title || '',
        category: this.initialData.category || '',
        url: this.initialData.url || '',
        image: null,
        status: this.initialData.status || 'unpublished',
      };
      this.currentStatus = this.initialData.status || 'unpublished';
      if (this.initialData.image_url) {
        this.imagePreview = this.initialData.image_url;
      }
    }
  },
  methods: {
    handleImageChange(event) {
      const file = event.target.files[0];
      if (file) {
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
          this.errors.image = 'File size must be less than 5MB';
          this.$refs.imageInput.value = '';
          return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
          this.errors.image = 'Invalid file type. Please use JPG, PNG, GIF, or WebP';
          this.$refs.imageInput.value = '';
          return;
        }

        this.errors.image = '';
        this.formData.image = file;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    removeImage() {
      this.formData.image = null;
      this.imagePreview = null;
      if (this.$refs.imageInput) {
        this.$refs.imageInput.value = '';
      }
    },
    validateForm() {
      this.errors = {};
      let isValid = true;

      if (!this.formData.date) {
        this.errors.date = 'Date is required';
        isValid = false;
      }

      if (!this.formData.title || this.formData.title.trim() === '') {
        this.errors.title = 'Title is required';
        isValid = false;
      }

      if (!this.formData.category) {
        this.errors.category = 'Category is required';
        isValid = false;
      }

      if (!this.formData.url || this.formData.url.trim() === '') {
        this.errors.url = 'URL is required';
        isValid = false;
      } else {
        // Basic URL validation
        try {
          new URL(this.formData.url);
        } catch {
          this.errors.url = 'Please enter a valid URL';
          isValid = false;
        }
      }

      if (this.mode === 'create' && !this.formData.image) {
        this.errors.image = 'Image is required';
        isValid = false;
      }

      if (!this.formData.status) {
        this.errors.status = 'Status is required';
        isValid = false;
      }

      return isValid;
    },
    getFormData() {
      const formData = new FormData();
      formData.append('date', this.formData.date);
      formData.append('title', this.formData.title);
      formData.append('category', this.formData.category);
      formData.append('url', this.formData.url);
      formData.append('status', this.formData.status);
      if (this.formData.image) {
        formData.append('image', this.formData.image);
      }
      return formData;
    },
    handleSave() {
      if (!this.validateForm()) return;
      const formData = this.getFormData();
      this.$emit('save', formData);
    },

    handleDelete() {
      this.$emit('delete');
    },
    handleCancel() {
      this.$emit('cancel');
    },
    handleSubmit() {
      // Prevent default form submission
      // Actual submission is handled by specific button clicks
    },
  },
};
</script>

<style scoped>
.news-form {
  max-width: 900px;
  margin: 0 auto;
  padding: var(--space-2xl);
}

.news-form__title {
  margin: 0 0 var(--space-xl) 0;
  font-size: var(--font-size-3xl);
  color: white;
}

.news-form__grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--space-lg);
  margin-bottom: var(--space-2xl);
}

.news-form__field {
  display: flex;
  flex-direction: column;
}

.news-form__field--full {
  grid-column: 1 / -1;
}

.news-form__error {
  color: var(--color-error);
  font-size: var(--font-size-sm);
  margin-top: var(--space-xs);
}

.news-form__hint {
  color: var(--color-text-tertiary);
  font-size: var(--font-size-sm);
  margin-top: var(--space-xs);
}

.news-form__image-upload input[type="file"] {
  padding: var(--space-sm);
  cursor: pointer;
}

.news-form__image-preview {
  position: relative;
  margin-top: var(--space-md);
  border-radius: var(--radius-md);
  overflow: hidden;
  max-width: 400px;
}

.news-form__image-preview img {
  width: 100%;
  height: auto;
  display: block;
}

.news-form__image-remove {
  position: absolute;
  top: var(--space-sm);
  right: var(--space-sm);
  width: 32px;
  height: 32px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: var(--font-size-lg);
  transition: background var(--transition-base);
}

.news-form__image-remove:hover {
  background: var(--color-error);
}

.news-form__actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: var(--space-xl);
  border-top: 1px solid var(--glass-border);
  gap: var(--space-md);
}

.news-form__actions-right {
  display: flex;
  gap: var(--space-md);
}

/* Responsive */
@media (max-width: 768px) {
  .news-form {
    padding: var(--space-lg);
  }

  .news-form__grid {
    grid-template-columns: 1fr;
  }

  .news-form__actions {
    flex-direction: column;
  }

  .news-form__actions-right {
    width: 100%;
    flex-direction: column;
  }

  .news-form__actions button {
    width: 100%;
  }
}
</style>
