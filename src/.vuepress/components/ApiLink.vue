<template>
  <a
    :href="'https://myparcelnl.github.io/api/' + to"
    rel="noopener noreferrer"
    target="_blank">
    {{ to }}
    <OutboundLink />
  </a>
</template>

<script>
import { defineComponent } from '@vue/runtime-core';

export default defineComponent({
  name: 'ApiLink',
  props: {
    to: {
      type: String,
      required: true,
    },
  },
});
</script>
