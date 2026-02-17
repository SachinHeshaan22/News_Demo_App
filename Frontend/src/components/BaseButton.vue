<template>
  <button
    :class="['base-button', `base-button--${variant}`, { 'base-button--loading': loading, 'base-button--disabled': disabled }]"
    :disabled="disabled || loading"
    @click="handleClick"
  >
    <span v-if="loading" class="base-button__spinner"></span>
    <span v-if="icon && !loading" class="base-button__icon">{{ icon }}</span>
    <span class="base-button__text">
      <slot></slot>
    </span>
  </button>
</template>

<script>
export default {
  name: 'BaseButton',
  props: {
    variant: {
      type: String,
      default: 'primary',
      validator: (value) => ['primary', 'secondary', 'danger', 'success'].includes(value),
    },
    loading: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    icon: {
      type: String,
      default: '',
    },
  },
  emits: ['click'],
  methods: {
    handleClick(event) {
      if (!this.disabled && !this.loading) {
        this.$emit('click', event);
      }
    },
  },
};
</script>

<style scoped>
.base-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-sm);
  padding: var(--space-md) var(--space-xl);
  font-family: var(--font-family);
  font-size: var(--font-size-base);
  font-weight: 600;
  border: none;
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: all var(--transition-base);
  position: relative;
  overflow: hidden;
}

.base-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  transform: translateX(-100%);
  transition: transform var(--transition-slow);
}

.base-button:hover::before {
  transform: translateX(100%);
}

/* Primary Variant */
.base-button--primary {
  background: var(--gradient-primary);
  color: white;
  box-shadow: var(--shadow-md);
}

.base-button--primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg), var(--shadow-glow);
}

.base-button--primary:active:not(:disabled) {
  transform: translateY(0);
}

.base-button--secondary {
  background: var(--color-bg-secondary);
  color: var(--color-text-primary);
  border: 1px solid var(--glass-border);
}

.base-button--secondary:hover:not(:disabled) {
  background: var(--color-surface);
  border-color: var(--color-primary);
}


.base-button--danger {
  background: linear-gradient(135deg, hsl(0, 84%, 60%) 0%, hsl(340, 80%, 55%) 100%);
  color: white;
  box-shadow: var(--shadow-md);
}

.base-button--danger:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg), 0 0 20px rgba(239, 68, 68, 0.4);
}


.base-button--success {
  background: linear-gradient(135deg, hsl(142, 76%, 45%) 0%, hsl(160, 70%, 50%) 100%);
  color: white;
  box-shadow: var(--shadow-md);
}

.base-button--success:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg), 0 0 20px rgba(34, 197, 94, 0.4);
}


.base-button--loading {
  cursor: wait;
  opacity: 0.7;
}

.base-button--disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

.base-button:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}


.base-button__spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Icon */
.base-button__icon {
  font-size: var(--font-size-lg);
}

/* Text */
.base-button__text {
  white-space: nowrap;
}
</style>
