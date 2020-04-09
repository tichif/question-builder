<template>
    <div>
        <a
            v-if="canAccept"
            title="Mark this answer as best answer"
            :class="classes"
            @click.prevent="create"
        >
            <i class="fas fa-check fa-2x"></i>
        </a>

        <a
            v-if="isBest"
            title="The question owner accepted this answer as best answer"
            :class="classes"
        >
            <i class="fas fa-check fa-2x"></i>
        </a>
    </div>
</template>

<script>
import eventBus from "../event-bus";
export default {
    props: ["answer"],
    data() {
        return {
            status: this.answer.is_best,
            url: "http://localhost/questionbuilder/public",
            id: this.answer.id
        };
    },
    created() {
        eventBus.$on("accepted", id => {
            this.status = id == this.id;
        });
    },
    computed: {
        canAccept() {
            return this.authorize("accept", this.answer);
        },
        isBest() {
            return !this.canAccept && this.status;
        },
        classes() {
            return ["mt-2", this.status ? "vote-accepted" : ""];
        },
        endpoint() {
            return `answers/${this.id}/accept`;
        }
    },
    methods: {
        create() {
            axios
                .post(this.endpoint)
                .then(res => {
                    this.$toast.success(res.data.message, "Success", {
                        timeout: 5000,
                        position: "bottomLeft"
                    });
                    this.status = true;
                    eventBus.$emit("accepted", this.id);
                })
                .catch(err => console.log(err));
        }
    }
};
</script>
