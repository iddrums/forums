<template>
   <div>
        <div class="level">
                <img :src="avatar" width="70" height="70" class="mr-2" alt="">

                <h1 v-text="user.name"></h1>
        </div>

            <form v-if="canUpdate" method="POST"  enctype="multipart/form-data">
                <image-upload name="avatar" class="mr-2" @loaded="onLoad"></image-upload>
            </form>

     </div>
</template>

<script>
  import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],

        components: { ImageUpload },

        data() {
            return {
                // avatar: this.user.avatar_path
                avatar: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8ZG9nfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" width="70" height="70'
            };
        },

        computed:  {
            canUpdate() {
            return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoad(avatar) {
               this.avatar =  avatar.src;

                this.persist(avatar.file);

            },

            persist(avatar) {
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                        .then(() => flash('Avatar uploaded!'));
            }
        }
    }
</script>
