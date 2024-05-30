<template>
    <div class="step-content">
        <h4 class="mb-3">Select Action</h4>

        <transition name="slide-fade">
            <div id="avaiable-actions">
                <div class="card">
                    <div class="card-body">
                        <div class="trigger-menu mb-3">
                            <button
                                v-for="type in actionTypes"
                                :key="type"
                                @click="selectType(type)"
                                class="btn btn-outline-primary ms-2 mb-2 d-flex flex-column align-items-center"
                                :class="{ active: selectedType === type.id }"
                            >
                                <i :class="type.icon" style="font-size: 30px"></i>
                                <span>{{ type.label }}</span>
                            </button>
                        </div>

                        <div class="trigger-options">
                            <div
                                v-for="action in filteredActions"
                                :key="action.name"
                                class="trigger-option"
                            >
                                <div class="d-flex align-items-center">
                                    <i :class="action.icon" class="me-2"></i>
                                    <span class="me-2">{{
                                        action.prefix
                                    }}</span>
                                    <div
                                        v-for="option in action.options"
                                        :key="option.id"
                                        class="me-2"
                                    >
                                        <select
                                            class="form-control form-control-sm"
                                            v-model="
                                                option.value
                                            "
                                            :ref="option.id"
                                        >
                                            <option
                                                v-for="item in option.items"
                                                :key="item"
                                                :value="item"
                                            >
                                                {{ item }}
                                            </option>
                                        </select>
                                    </div>
                                    <span>{{ action.suffix }}</span>
                                    <button
                                        class="btn btn-outline-secondary btn-sm ms-auto p-3"
                                        @click="setActionValue(action)"
                                    >
                                        +
                                    </button>
                                </div>
                                <p class="trigger-description">
                                    {{ action.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            addedTrigger: false,
            selectedType: "card_move"
        };
    },
    props: {
        modelValue: Object,
        actions: {
            type: Array,
            required: true,
        },
        actionTypes: {
            type: Array,
            required: true,
        },
        returnedToFirstStep: Boolean
    },
    computed: {
        action: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit("update:modelValue", value);
            },
        },
        filteredActions() {
            return this.actions.filter(
                (action) => action.type === this.selectedType
            );
        },
    },
    methods: {
        selectType(type) {
            this.selectedType = type;
        },
        setActionValue(action) {
            action.sentence = action.prefix + " ";
            action.options.forEach((option) => {
                if (option.value !== null) {
                    action.sentence += option.value + " ";
                }
            });

            action.sentence += action.suffix;
            this.action.push(action);
            this.$emit("next-step");
        },
    },
    created() {
        if(this.returnedToFirstStep && this.modelValue.length > 0) {
            this.$emit("next-step");
        }
    },
};
</script>

<style scoped>
.btn-outline-primary.active,
.btn-outline-primary:hover {
    background-color: #374df1 !important;
    color: #fff;
}
.btn-outline-primary {
    border-color: #374df1;
    color: #374df1;
}
.step-content {
    padding: 20px;
    border-radius: 5px;
    transition: all 0.3s ease;
}
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.4s ease;
}
.slide-fade-enter {
    transform: translateY(100%);
    opacity: 0;
}
.slide-fade-leave-to {
    transform: translateY(-100%);
    opacity: 0;
}
.slide-fade-leave-active {
    transition-delay: 0.1s;
}
.trigger-menu {
    display: flex;
    flex-wrap: wrap;
}
.trigger-option {
    border: 1px solid #d1d1d1;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 0.5rem;
}
.trigger-option .d-flex {
    align-items: center;
}
.trigger-description {
    margin-top: 5px;
    color: #cacaca;
    font-size: 0.875rem;
}
.trigger-option select {
    display: inline-block;
    width: auto;
}
</style>
