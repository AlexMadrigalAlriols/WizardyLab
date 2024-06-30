<template>
    <div>
        <form action="">
            <table>
                <thead>
                    <tr>
                        <th style="width: 8rem"></th>
                        <th style="width: 14rem"></th>
                        <th style="width: 12rem"></th>
                        <th style="width: 3rem"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(filter, index) in filters" :key="index">
                        <td>
                            <select
                                v-model="filter.condition"
                                class="form-control form-select"
                                :name="'advanced_filter_condition_' + index"
                                :id="'advanced_filter_condition_' + index"
                                v-show="index != 0"
                            >
                                <option value="and">{{ $t("AND") }}</option>
                                <option value="or">{{ $t("OR") }}</option>
                            </select>
                            <div v-if="index == 0">
                                <span>Donde</span>
                            </div>
                        </td>
                        <td>
                            <select
                                v-model="filter.field"
                                @change="handleFieldChange(index)"
                                class="form-control form-select"
                                :name="'advanced_filter_field_' + index"
                                :id="'advanced_filter_field_' + index"
                            >
                                <option value="-Select one-" disabled>{{ $t("-Select one-") }}</option>
                                <option :value="field" v-for="field in fields['self']">
                                        {{ $t(field) }}
                                </option>

                                <optgroup v-for="(relation, label) in relationFields" :label="$t(label)">
                                    <option :value="label + '.' + field" v-for="field in relation">
                                        {{ $t(field) }}
                                    </option>
                                </optgroup>
                            </select>
                        </td>
                        <td>
                            <select
                                v-model="filter.operator"
                                class="form-control form-select"
                                :name="'advanced_filter_operator_' + index"
                                :id="'advanced_filter_operator_' + index"
                            >
                                <option
                                    :value="label"
                                    v-for="(operator, label) in operators"
                                >
                                    {{ $t(operator) }}
                                </option>
                            </select>
                        </td>
                        <td class="w-25" style="max-width: 200px">
                            <input
                                v-model="filter.value"
                                v-if="filter.valueType == 'none'"
                                type="text"
                                class="form-control"
                                :name="'advanced_filter_value_' + index"
                                :id="'advanced_filter_value_' + index"
                            />

                            <select
                                v-model="filter.value"
                                class="form-control select2"
                                v-if="filter.valueType == 'select'"
                                :name="'advanced_filter_value_' + index"
                                :id="'advanced_filter_select2_value_' + index"
                            >
                                <option
                                    v-if="filter.value"
                                    :value="filter.value"
                                    selected
                                >
                                    {{ filter.value }}
                                </option>
                            </select>
                        </td>
                        <td>
                            <button
                                @click="removeFilter(index)"
                                type="button"
                                class="btn btn-danger"
                            >
                                <i class="bx bx-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <button @click="addFilter" type="button" class="btn btn-primary mt-2">
            <i class="bx bx-plus"></i> {{ $t("Add Filter") }}
        </button>
    </div>
    <div class="card-footer border-top mt-3">
        <div class="text-end mt-3">
            <button
                type="button"
                class="btn btn-secondary resetFilterModal"
                data-modal="#advanced_filtersModal"
                @click="filters = []"
            >
                {{ $t("Reset") }}
            </button>
            <button
                type="button"
                class="btn btn-primary ms-3 submitFilterModal"
                data-modal="#advanced_filtersModal"
            >
                {{ $t("Apply Filters") }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: ["operators", "fields", "newFilters"],
    data() {
        return {
            filters: [],
            allOption: {
                id: "any",
                text: this.$t("Any"),
            },
        };
    },
    methods: {
        addFilter() {
            this.filters.push({
                condition: "and",
                field: "-Select one-",
                operator: "equal",
                value: "",
                valueType: "none",
            });
        },
        removeFilter(index) {
            this.filters.splice(index, 1);

            if (this.filters.length === 0) {
                $('#advanced_filtersModalBtn').removeClass('btn-primary').addClass('btn-secondary');
            }
        },
        loadSelect2(index) {
            $(`#advanced_filter_select2_value_` + index).select2({
                ajax: {
                    url: "product-categories/select2",
                    dataType: "json",
                    data: (params) => {
                        return {
                            search: params.term,
                            page: params.page || 1,
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        let dataset =
                            params.page === 1
                                ? [...parseSelect2Results(data.results)]
                                : parseSelect2Results(data.results);
                        return {
                            results: dataset,
                            pagination: {
                                more: params.page * 10 < data.count,
                            },
                        };
                    },
                },
            });
        },
        parseSelect2Results(results) {
            return Array.isArray(results) ? results : Object.values(results);
        },
        handleFieldChange(index) {
            const filter = this.filters[index];
            if (filter.field === "categories.id") {
                setTimeout(() => {
                    this.loadSelect2(index);
                }, 200);
            } else {
                $(`#advanced_filter_select2_value_` + index).select2("destroy");
            }
        },
    },
    mounted() {
        if(this.newFilters) {
            this.newFilters["values"].forEach((filter, idx) => {
                this.filters.push({
                    condition: this.newFilters["conditions"][idx],
                    field: this.newFilters["fields"][idx],
                    operator: this.newFilters["operators"][idx],
                    value: filter,
                });

                if (this.newFilters["fields"][idx] === "categories.id") {
                    setTimeout(() => {
                        this.loadSelect2(idx);
                    }, 500);
                }
            });
        }
    },
    computed: {
        relationFields() {
            // Filtra los campos para excluir 'self'
            const { self, ...otherFields } = this.fields;
            return otherFields;
        }
    }
};
</script>

<style scoped>
td {
    padding: 1rem;
}

th {
    padding-left: 1rem;
    padding-right: 1rem;
}
</style>
