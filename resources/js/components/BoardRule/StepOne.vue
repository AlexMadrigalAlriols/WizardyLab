<template>
    <div class="step-content">
        <h4 class="mb-3">Select Trigger</h4>
        <transition name="slide-fade">
            <button
                class="btn btn-primary w-100"
                @click="addTrigger"
                v-show="!addedTrigger"
            >
                + Add Trigger
            </button>
        </transition>

        <transition name="slide-fade">
            <div id="avaiable-actions" v-show="showDiv">
                <div class="card">
                    <div class="card-body">
                        <div class="trigger-menu mb-3">
                            <button
                                v-for="type in triggerTypes"
                                :key="type.id"
                                @click="selectType(type)"
                                class="btn btn-outline-primary ms-2 mb-2 d-flex flex-column align-items-center"
                                :class="{ active: selectedType === type.id }"
                            >
                                <i
                                    :class="type.icon"
                                    style="font-size: 30px"
                                ></i>
                                <span>{{ type.label }}</span>
                            </button>
                        </div>

                        <div class="trigger-options" style="overflow-x: auto;">
                            <div
                                v-for="trigger in filteredTriggers"
                                :key="trigger.name"
                                class="trigger-option" style="overflow-x: auto;"
                            >
                                <div class="d-flex align-items-center">
                                    <i :class="trigger.icon" class="me-2"></i>
                                    <span class="me-2">{{
                                        trigger.prefix
                                    }}</span>
                                    <div
                                        v-for="option in trigger.options"
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
                                    <span>{{ trigger.suffix }}</span>
                                    <button
                                        class="btn btn-outline-secondary btn-sm ms-auto p-3"
                                        @click="setTriggerValue(trigger)"
                                    >
                                        <i class="bx bx-plus"></i>
                                    </button>
                                </div>
                                <p class="trigger-description">
                                    {{ trigger.description }}
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
            showDiv: false,
            selectedType: "card_move",
        };
    },
    props: {
        modelValue: Object,
        triggers: {
            type: Array,
            required: true,
        },
        triggerTypes: {
            type: Array,
            required: true,
        },
    },
    computed: {
        trigger: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit("update:modelValue", value);
            },
        },
        filteredTriggers() {
            return this.triggers.filter(
                (trigger) => trigger.type === this.selectedType
            );
        },
    },
    methods: {
        selectType(type) {
            this.selectedType = type.id;
        },
        setTriggerValue(trigger) {
            trigger.sentence = trigger.prefix + " ";
            trigger.options.forEach((option) => {
                if (option.value !== null) {
                    trigger.sentence += option.value + " ";
                }
            });

            trigger.sentence += trigger.suffix;

            this.trigger = trigger;
            this.$emit("next-step");
        },
        addTrigger() {
            this.addedTrigger = true;
            setTimeout(() => {
                this.showDiv = true;
            }, 300); // Delay should match the transition duration
        },
    }
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
