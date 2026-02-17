<template>
  <Transition name="modal">
    <div v-if="show" class="confirm-dialog-overlay" @click.self="handleCancel">
      <div class="confirm-dialog glass-card">
        <div class="confirm-dialog__header">
          <h3>{{ title }}</h3>
        </div>
        <div class="confirm-dialog__body">
          <p>{{ message }}</p>
        </div>
        <div class="confirm-dialog__footer">
          <BaseButton variant="secondary" @click="handleCancel">
            {{ cancelText }}
          </BaseButton>
          <BaseButton :variant="confirmVariant" @click="handleConfirm">
            {{ confirmText }}
          </BaseButton>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
import BaseButton from './BaseButton.vue';

export default {
  name: 'ConfirmDialog',
  components: {
    BaseButton,
  },
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: 'Confirm Action',
    },
    message: {
      type: String,
      required: true,
    },
    confirmText: {
      type: String,
      default: 'Yes',
    },
    cancelText: {
      type: String,
      default: 'No',
    },
    confirmVariant: {
      type: String,
      default: 'danger',
    },
  },
  emits: ['confirm', 'cancel'],
  methods: {
    handleConfirm() {
      this.$emit('confirm');
    },
    handleCancel() {
      this.$emit('cancel');
    },
  },
};
</script>

<style scoped>
.confirm-dialog-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: var(--space-lg);
}

.confirm-dialog {
  max-width: 500px;
  width: 100%;
  padding: var(--space-xl);
  animation: fadeIn var(--transition-base) ease-out;
}

.confirm-dialog__header {
  margin-bottom: var(--space-lg);
}

.confirm-dialog__header h3 {
  margin: 0;
  font-size: var(--font-size-2xl);
  color: var(--color-text-primary);
}

.confirm-dialog__body {
  margin-bottom: var(--space-xl);
}

.confirm-dialog__body p {
  margin: 0;
  font-size: var(--font-size-base);
  color: var(--color-text-secondary);
  line-height: 1.6;
}

.confirm-dialog__footer {
  display: flex;
  gap: var(--space-md);
  justify-content: flex-end;
}

/* Modal Transition */
.modal-enter-active,
.modal-leave-active {
  transition: opacity var(--transition-base);
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .confirm-dialog,
.modal-leave-active .confirm-dialog {
  transition: transform var(--transition-base);
}

.modal-enter-from .confirm-dialog,
.modal-leave-to .confirm-dialog {
  transform: scale(0.9);
}

/* Responsive */
@media (max-width: 768px) {
  .confirm-dialog {
    padding: var(--space-lg);
  }

  .confirm-dialog__footer {
    flex-direction: column;
  }

  .confirm-dialog__footer button {
    width: 100%;
  }
}
</style>
