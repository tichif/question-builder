<template>
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="card-title">
            <h3>Your Answer</h3>
          </div>
          <hr />
          <form @submit.prevent="create">
            <div class="form-group">
              <textarea v-model="body" id="body" required rows="7" class="form-control"></textarea>
            </div>
            <div class="form-group">
              <button
                type="submit"
                :disabled="isDisable"
                class="btn btn-lg btn-outline-primary"
              >Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["questionId"],
  data() {
    return {
      body: "",
      url: "http://localhost/questionbuilder/public"
    };
  },
  methods: {
    create() {
      axios
        .post(`questions/${this.questionId}/answers`, {
          body: this.body
        })
        .then(({ data }) => {
          this.$toast.success(data.message, "Success", {
            timeout: 5000,
            position: "bottomLeft"
          });
          this.body = "";
          this.$emit("createdAnswer", data.answer);
        })
        .catch(err =>
          this.$toast.error(err.response.data.message, "Error", {
            timeout: 5000,
            position: "bottomLeft"
          })
        );
    }
  },
  computed: {
    isDisable() {
      return !this.signedIn || this.body.length < 10;
    }
  }
};
</script>