<template>
    <div>
        <input :value="value" :name="name" id="trix" type="hidden" />

        <trix-editor ref="trix" input="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>


<script>
    import Trix from 'trix';

export default {
    props: ['name', 'value', 'placeholder', 'shouldClear'],

    mounted () {
      this.$refs.trix.addEventListener('trix-change', e => {
         this.$emit('input', e.target.innerHTML);
      });

      this.$watch('shouldClear', () => {
           this.$refs.trix.value = '';
      });
     }
  }
</script>
