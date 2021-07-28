<template>
    <div>
        <input :value="value" :name="name" id="trix" type="hidden" />

        <trix-editor ref="trix" input="trix"></trix-editor>
    </div>
</template>


<script>
    import Trix from 'trix';

export default {
    props: ['name', 'value'],

    mounted () {
      this.$refs.trix.addEventListener('trix-change', e => {
         console.log('Handling');
         this.$emit('input', e.target.innerHtml);
      });
     }
  }
</script>
