<template>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-md-7">
                <span class="h2"
                    >Create a Rule <i class="bi bi-info-circle"></i
                ></span>
            </div>
            <div class="col-md-5">
                <div class="text-end">
                    <button
                        class="btn btn-primary me-3"
                        @click="submitForm"
                        :disabled="currentStep !== 3"
                    >
                        Save
                    </button>
                    <a class="btn btn-secondary" :href="returnUrl">Cancel</a>
                </div>
            </div>
        </div>
        <div class="">
            <div class="mb-4 text-center">
                <div class="steps">
                    <div class="step" :class="{ active: currentStep === 1 }">
                        1 Select trigger
                    </div>
                    <div class="step" :class="{ active: currentStep === 2 }">
                        2 Select action
                    </div>
                    <div class="step" :class="{ active: currentStep === 3 }">
                        3 Review and save
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div v-if="formData.trigger">
            <h4 class="mb-3">Trigger</h4>
            <div class="row mb-3">
                <div class="col-11">
                    <input
                        type="text"
                        disabled
                        class="form-control"
                        :value="formData.trigger.sentence"
                    />
                </div>
                <div class="col-1">
                    <button class="btn btn-primary w-100" @click="firstStep">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <div v-if="this.formData.actions.length">
            <h4 class="mb-3">Actions</h4>
            <div class="row mb-3" v-for="(action, index) in this.formData.actions" :key="action.id + '_' + refreshKey">
                <div class="col-10">
                    <input
                        type="text"
                        disabled
                        class="form-control"
                        :value="action.sentence"
                    />
                </div>
                <div class="col-1">
                    <button class="btn btn-primary w-100" @click="secondStep(index)">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <div v-if="currentStep === 1">
            <step-one
                @next-step="nextStep"
                v-model="formData.trigger"
                :trigger-types="triggerTypes"
                :triggers="triggers"
            />
        </div>
        <div v-if="currentStep === 2">
            <step-two
                @prev-step="prevStep"
                @next-step="nextStep"
                v-model="formData.actions"
                :actions="actions"
                :action-types="actionTypes"
                :returned-to-first-step="returnedToFirstStep"
            />
        </div>
        <div v-if="currentStep === 3">
            <div class="step-content">
                <button class="btn btn-primary w-100" type="button" @click="prevStep">Add Action</button>
            </div>
        </div>
    </div>
</template>

<script>
import StepOne from "./StepOne.vue";
import StepTwo from "./StepTwo.vue";
import axios from "axios";

export default {
    components: {
        StepOne,
        StepTwo
    },
    props: {
        returnUrl: String,
        formUrl: String,
        triggers: Array,
        triggerTypes: Array,
        actions: Array,
        actionTypes: Array,
        formData: {
            type: Object,
            default: () => ({
                trigger: null,
                actions: [],
            }),
        }
    },
    data() {
        return {
            currentStep: this.formData.trigger ? (this.formData.actions ? 3 : 2) : 1,
            returnUrl: this.returnUrl,
            refreshKey: 0,
            returnedToFirstStep: false
        };
    },
    methods: {
        nextStep() {
            this.currentStep++;
        },
        prevStep() {
            this.returnedToFirstStep = false;
            this.currentStep--;
        },
        firstStep() {
            this.formData.trigger = null;
            this.currentStep = 1;
            this.returnedToFirstStep = true;
        },
        secondStep(index) {
            this.returnedToFirstStep = false;
            this.formData.actions.splice(index, 1);
            this.refreshKey++;
            if(this.formData.actions.length === 0) {
                this.currentStep = 2;
            }
        },
        submitForm() {
            if(this.formData.trigger === null || this.formData.actions.length === 0) {
                return;
            }

            axios.defaults.headers.common["X-CSRF-TOKEN"] = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            axios
                .post(this.formUrl, this.formData)
                .then((response) => {
                    window.location.href = this.returnUrl;
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    },
};
</script>

<style scoped>
.btn-outline-primary.active {
    background-color: #374df1 !important;
}
.steps {
    display: flex;
    justify-content: center;
}
.step {
    display: flex;
    align-items: center;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 50px;
    background-color: #f5f5f5;
    color: #777;
    transition: background-color 0.4s ease, color 0.3s ease;
    margin-left: 2rem;
}
.step.active {
    background-color: #0d6efd;
    color: #fff;
}
</style>
