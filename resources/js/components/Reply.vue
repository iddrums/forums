<template>
      <div class="col-md-8">
        <div id="'reply-'+id" class="card" :class="isBest ? 'card-success' : 'card-default'">
            <div class="card-header">
                <div class="level">
                    <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name"
                        v-text="reply.owner.name">
                    </a> said <span v-text="ago"></span>
                    </h5>

                    <div v-if="signedIn">
                        <favorite :reply="reply"></favorite>
                    </div>
               </div>
            </div>


            <div class="card-body">
                <div v-if="editing">
                    <form @submit="update">
                        <div class="form-group">
                            <wysiwyg v-model="body"></wysiwyg>
                            <!-- <textarea class="form-control" v-model="body" required></textarea> -->
                        </div>

                            <button class="btn btn-xs btn-primary">Update</button>
                            <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
                    </form>
                </div>

                <div v-else v-html="body"></div>
            </div>

              <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
                  <div v-if="authorize('owns', reply)">
                    <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                    <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>

                  </div>
                    <button class="btn btn-xs btn-success ml-a" @click="markBestReply" v-if="authorize('owns', reply.Thread)">Best Reply?</button>
              </div>
        </div>
      </div>
</template>


<script>
import Favorite from './Favorite.vue';
import moment from 'moment';
import Wysiwyg from './Wysiwyg.vue';

    export default {
        props: ['reply'],

        components: { Favorite, Wysiwyg },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest
            };
        },

        computed: {
            ago() {
              return moment(this.reply.created_at).fromNow() +'...';
            }

        },

        methods: {
            update() {
               axios.patch(
                    '/replies/' + this.id, {
                        body: this.body
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

               this.editing = false;

               flash('Updated!');
            },

            destroy() {
                 axios.delete('/replies/' + this.id);

                  this.$emit('deleted', this.id);

            },

            markBestReply() {
              axios.post('/replies/' + this.id + '/best');

              window.events.$emit('best-reply-selected', this.id);
            }
        },
    }
</script>
