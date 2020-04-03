<template>
  <div class="d-flex flex-column vote-controls">
    <a :title="title('up')" class="vote-up" :class="classes" @click.prevent="voteUp">
      <i class="fas fa-caret-up fa-3x"></i>
    </a>

    <span class="vote-count">{{ count }}</span>

    <a :title="title('down')" class="vote-down" :class="classes" @click.prevent="voteDown">
      <i class="fas fa-caret-down fa-3x"></i>
    </a>

    <favorite v-if="name =='question'" :question="model"></favorite>

    <accept v-else :answer="model"></accept>
  </div>
</template>

<script>
import Favorite from "./Favorite";
import Accept from "./Accept";

export default {
  props: ["name", "model"],
  components: { Favorite, Accept },
  data() {
    return {
      count: this.model.votes_counts,
      url: "http://localhost/questionbuilder/public",
      id: this.model.id
    };
  },
  methods: {
    title(voteType) {
      let titles = {
        up: `The ${this.name} is useful`,
        down: `The ${this.name} is not useful`
      };
      return titles[voteType];
    },
    voteUp() {
      this._vote(1);
    },
    voteDown() {
      this._vote(-1);
    },
    _vote(vote) {
      if (!this.signedIn) {
        this.$toast.warning(
          `Please login before to vote this ${this.name}`,
          "Warning",
          {
            timeout: 5000,
            position: "bottomLeft"
          }
        );
        return;
      }
      axios
        .post(this.endpoint, { vote: vote })
        .then(res => {
          this.$toast.success(res.data.message, "Success", {
            timeout: 5000,
            position: "bottomLeft"
          });
          this.count = res.data.votesCount;
        })
        .catch(err => console.log(err));
    }
  },
  computed: {
    classes() {
      return [!this.signedIn ? "off" : ""];
    },
    endpoint() {
      return `${this.url}/${this.name}s/${this.id}/vote`;
    }
  }
};
</script>