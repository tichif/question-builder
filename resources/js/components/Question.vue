<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <form class="card-body" v-if="editing" @submit.prevent="update">
          <div class="card-title">
            <input type="text" v-model="title" class="form-control form-control-lg" />
          </div>
          <hr />
          <div class="media">
            <div class="media-body">
              <div class="form-group">
                <textarea class="form-control" v-model="body" rows="10" required></textarea>
              </div>
              <button class="btn btn-primary" type="submit" :disabled="isInvalid">Update</button>
              <button class="btn btn-outline-secondary" @click.prevent="cancel">Cancel</button>
            </div>
          </div>
        </form>
        <div class="card-body" v-else>
          <div class="card-title">
            <div class="d-flex align-items-center">
              <div class="ml-auto">
                <a :href="url" class="btn btn-outline-secondary">Go Back</a>
              </div>
            </div>
          </div>
          <hr />
          <div class="media">
            <vote name="question" :model="question"></vote>

            <div class="media-body">
              <div v-html="bodyHtml"></div>
              <div class="row">
                <div class="col-4">
                  <div class="ml-auto">
                    <a
                      v-if="authorize('modify',question)"
                      @click.prevent="edit"
                      class="btn btn-sm btn-outline-info"
                    >Edit</a>

                    <button
                      v-if="authorize('deleteQuestion',question)"
                      @click="destroy"
                      class="btn btn-sm btn-outline-danger"
                    >Delete</button>
                  </div>
                </div>
                <div class="col-4"></div>
                <div class="col-4">
                  <user-info :model="question" label="Asked"></user-info>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vote from "./Vote";
import UserInfo from "./UserInfo";
export default {
  props: ["question"],
  components: { Vote, UserInfo },
  data() {
    return {
      url: "http://localhost/questionbuilder/public/questions",
      title: this.question.title,
      body: this.question.body,
      bodyHtml: this.question.body_html,
      editing: false,
      id: this.question.id,
      beforeEditCache: {}
    };
  },
  computed: {
    isInvalid() {
      return this.body.length < 10 || this.title.length < 10;
    },
    endpoint() {
      return `${this.url}/${this.id}`;
    }
  },
  methods: {
    edit() {
      this.beforeEditCache = {
        body: this.body,
        title: this.title
      };
      this.editing = true;
    },
    cancel() {
      this.body = this.beforeEditCache.body;
      this.title = this.beforeEditCache.title;
      this.editing = false;
    },
    update() {
      axios
        .put(this.endpoint, {
          title: this.title,
          body: this.body
        })
        .then(({ data }) => {
          this.$toast.success(data.message, "Success", {
            timeout: 5000,
            position: "bottomLeft"
          });
          this.bodyHtml = data.body_html;
          this.editing = false;
        })
        .catch(({ response }) =>
          this.$toast.error(response.data.message, "Error", {
            position: "bottomLeft",
            timeout: 5000
          })
        );
    },
    destroy() {
      this.$toast.question("Are you sure about that?", "Confirm", {
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: "once",
        id: "question",
        zindex: 999,
        title: "Hey",
        position: "center",
        buttons: [
          [
            "<button><b>YES</b></button>",
            (instance, toast) => {
              axios.delete(this.endpoint).then(({ data }) => {
                this.$toast.success(data.message, "Success", {
                  timeout: 2000,
                  position: "bottomLeft"
                });
                setTimeout(() => {
                  window.location.href = this.url;
                }, 3000);
              });
              instance.hide({ transitionOut: "fadeOut" }, toast, "button");
            },
            true
          ],
          [
            "<button>NO</button>",
            function(instance, toast) {
              instance.hide({ transitionOut: "fadeOut" }, toast, "button");
            }
          ]
        ]
      });
    }
  }
};
</script>