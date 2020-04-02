<template>
  <div>
    <a
      title="Click to mark as favorite question(Click again to undo)"
      :class="classes"
      @click.prevent="toggle"
    >
      <i class="fas fa-star fa-2x"></i>
      <span class="favorites-count">{{ count }}</span>
    </a>
  </div>
</template>

<script>
export default {
  props: ["question"],
  data() {
    return {
      isFavorited: this.question.is_favorited,
      count: this.question.favorites_count,
      id: this.question.id,
      url: "http://localhost/questionbuilder/public"
    };
  },
  computed: {
    classes() {
      return [
        "favorite",
        "mt-2",
        !this.signedIn ? "off" : this.isFavorited ? "favorited" : ""
      ];
    },
    endpoint() {
      return `${this.url}/questions/${this.id}/favorites`;
    },
    signedIn() {
      return window.Auth.signedIn;
    }
  },
  methods: {
    toggle() {
      if (this.signedIn) {
        this.isFavorited ? this.destroy() : this.create();
      } else {
        this.$toast.warning(
          "Please login before favorite this question.",
          "Warning",
          {
            timeout: 5000,
            position: "bottomLeft"
          }
        );
      }
    },
    destroy() {
      axios
        .delete(this.endpoint)
        .then(res => {
          this.count--;
          this.isFavorited = false;
        })
        .catch(err => console.log(err));
    },
    create() {
      axios
        .post(this.endpoint)
        .then(res => {
          this.count++;
          this.isFavorited = true;
        })
        .catch(err => console.log(err));
    }
  }
};
</script>