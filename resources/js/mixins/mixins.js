export default {
    data() {
        return {
            editing: false,
        };
    },
    methods: {
        edit() {
            this.setEditCache();
            this.editing = true;
        },
        cancel() {
            this.restoreFromCache();
            this.editing = false;
        },
        // methods which will be override in the components
        setEditCache() {},
        restoreFromCache() {},
        update() {
            axios
                .put(this.endpoint, this.payload())
                .then(({ data }) => {
                    this.$toast.success(data.message, "Success", {
                        timeout: 5000,
                        position: "bottomLeft",
                    });
                    this.bodyHtml = data.body_html;
                    this.editing = false;
                })
                .catch(({ response }) =>
                    this.$toast.error(response.data.message, "Error", {
                        position: "bottomLeft",
                        timeout: 5000,
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
                            this.delete();
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        true,
                    ],
                    [
                        "<button>NO</button>",
                        function (instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                    ],
                ],
            });
        },
        payload() {},
        delete() {},
    },
};
