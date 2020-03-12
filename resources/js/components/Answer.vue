

<script>
export default {
  props: ["answer"],
  data() {
    return {
      editing: false,
      body: this.answer.body,
      bodyHtml: this.answer.body_html,
      id: this.answer.id,
      questionId: this.answer.question_id,
      url: "http://localhost/questionbuilder/public",
      beforeEditCache: null
    };
  },
  methods: {
    edit() {
      this.beforeEditCache = this.body;
      this.editing = true;
    },
    cancel() {
      this.body = this.beforeEditCache;
      this.editing = false;
    },
    update() {
      axios
        .patch(`${this.url}/questions/${this.questionId}/answers/${this.id}`, {
          body: this.body
        })
        .then(res => {
          this.editing = false;
          this.bodyHtml = res.data.body_html;
          alert(res.data.message);
        })
        .catch(error => {
          alert(error.response.data.message);
        });
    }
  },
  computed: {
    isInvalid() {
      return this.body.length < 10;
    }
  }
};
</script>