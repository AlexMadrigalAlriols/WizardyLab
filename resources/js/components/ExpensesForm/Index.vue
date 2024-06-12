<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Item</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in items" :key="item.id">
                            <td>
                                <img width="100px" v-show="item.item.img" :src="item.item.img" class="img-thumbnail"lt="">
                            </td>
                            <td>
                                <input :id="'items_id_' + index"
                                    :name="'items['+index+'][id]'"
                                    readonly
                                    class="d-none"
                                    :value="item.item.id"
                                >
                                <input :id="'items_name_' + index"
                                    :name="'items['+index+'][name]'"
                                    readonly
                                    class="form-control"
                                    :value="item.item.name"
                                >
                            </td>
                            <td>
                                <input
                                    :id="'items_qty_' + index"
                                    type="number"
                                    :name="'items['+index+'][qty]'"
                                    class="form-control"
                                    :value="item.quantity"
                                    :max="item.item.maxQty"
                                    min="1"
                                    @change="handleItemChange($event, index)"
                                >
                            </td>
                            <td>
                                <input type="hidden" :name="'items['+index+'][amount]'" :value="item.item.price">
                                <b>{{ item.quantity * item.item.price }} €</b>
                            </td>
                            <td>
                                <button
                                    @click="removeItem(index)"
                                    type="button"
                                    class="btn btn-danger m-1"
                                >
                                    <i class="bx bx-trash align-middle"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">
                                <input type="hidden" :value="items.reduce((acc, item) => acc + item.quantity * item.item.price, 0)" name="amount">
                                <b>Total:</b>
                            </td>
                            <td>
                                <b>{{ items.reduce((acc, item) => acc + item.quantity * item.item.price, 0).toFixed(2) }} €</b>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                Custom Charges
                <div class="row">
                    <div class="col-md-7">
                        <input
                            v-model="newCustomCharge.name"
                            type="text"
                            class="form-control"
                            placeholder="Custom Text"
                        />
                    </div>
                    <div class="col-md-3">
                        <input
                            v-model="newCustomCharge.amount"
                            type="number"
                            class="form-control"
                            placeholder="Amount"
                            aria-label="Recipient's username" aria-describedby="basic-addon2"
                        />
                    </div>
                    <div class="col-md-2">
                        <button
                            @click="addCustomCharge"
                            type="button"
                            class="btn btn-outline-primary"
                        >
                            <i class='bx bx-plus-medical' ></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                Materials
                <div class="row">
                    <div class="col-md-6">
                        <v-select
                            v-model="newItem.item_id"
                            :options="inventoryItems"
                            label="name"
                            :reduce="item => item.id"
                            :on-change="handleItemSelected(newItem.item_id)"
                        >
                        </v-select>
                    </div>
                    <div class="col-md-4">
                        <input
                            v-model="newItem.quantity"
                            type="number"
                            class="form-control"
                            :max="newItem.maxQty"
                            min="1"
                            placeholder="Quantity"
                        />
                    </div>
                    <div class="col-md-2">
                        <button
                            @click="addItem"
                            type="button"
                            class="btn btn-outline-primary"
                        >
                            <i class='bx bx-plus-medical' ></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: { vSelect },
    props: {
        inventoryItems: {
            type: Array,
            required: true,
        },
        assignments: {
            type: Array
        }
    },
    setup(props) {
        const items = ref([]);
        let barCode = "";
        const newItem = ref({
            item_id: "",
            quantity: 1,
            maxQty: null
        });
        const newCustomCharge = ref({
            item_id: "",
            name: "",
            quantity: 1,
            amount: 0,
            maxQty: null
        });

        const addItem = () => {
            const newId = items.value.length + 1;

            if(!newItem.value.item_id || newItem.value.quantity <= 0) {
                return;
            }

            if(items.value.find((item) => item.item.id === newItem.value.item_id)) {
                if(parseInt(items.value.find((item) => item.item.id === newItem.value.item_id).quantity) + parseInt(newItem.value.quantity) > items.value.find((item) => item.item.id === newItem.value.item_id).item.maxQty) {
                    items.value.find((item) => item.item.id === newItem.value.item_id).quantity = items.value.find((item) => item.item.id === newItem.value.item_id).item.maxQty;

                    Swal.fire({
                        toast: true,
                        title: 'You have reached the maximum quantity for this item!',
                        icon: 'error',
                        showConfirmButton: false,
                        position: 'top-end',
                        timer: 3000
                    });
                } else {
                    items.value.find((item) => item.item.id === newItem.value.item_id).quantity =
                        (parseInt(items.value.find((item) => item.item.id === newItem.value.item_id).quantity)) + parseInt(newItem.value.quantity);
                }
            } else {
                items.value.push({
                    id: newId,
                    item: {
                        id: newItem.value.item_id,
                        name: props.inventoryItems.find((item) => item.id === newItem.value.item_id).name,
                        maxQty: props.inventoryItems.find((item) => item.id === newItem.value.item_id).remaining_stock,
                        price: props.inventoryItems.find((item) => item.id === newItem.value.item_id).price,
                        img: props.inventoryItems.find((item) => item.id === newItem.value.item_id).cover,
                    },
                    quantity: newItem.value.quantity,
                });
            }

            newItem.value = {
                item_id: "",
                quantity: 1,
                maxQty: null
            };
            barCode = "";
        };

        const addCustomCharge = () => {
            const newId = items.value.length + 1;

            if(newCustomCharge.value.name == "") {
                return;
            }

            items.value.push({
                id: newId,
                item: {
                    id: newCustomCharge.value.item_id,
                    name: newCustomCharge.value.name,
                    maxQty: null,
                    price: newCustomCharge.value.amount,
                    img: null,
                },
                quantity: newCustomCharge.value.quantity,
            });

            newCustomCharge.value = {
                item_id: "",
                name: "",
                quantity: 1,
                amount: 0,
                maxQty: null
            };
            barCode = "";
        };

        const handleItemChange = (event, index) => {
            const newValue = event.target.value;

            items.value[index].quantity = newValue;
        };

        const handleItemSelected = (event) => {
            if(event) {
                newItem.value.maxQty = props.inventoryItems.find((item) => item.id === event).remaining_stock;

                if(newItem.value.quantity > newItem.value.maxQty) {
                    newItem.value.quantity = newItem.value.maxQty;
                }
            } else {
                newItem.value.maxQty = null;
            }
        };

        const removeItem = (index) => {
            items.value.splice(index, 1);
        };

        const fetchItems = async () => {
            props.assignments.forEach(assig => {
                items.value.push({
                    id: assig.id,
                    item: {
                        id: assig.item_id,
                        name: assig.item.name,
                        maxQty: assig.item.remaining_stock,
                    },
                    quantity: assig.quantity,
                });
            });
        };

        onMounted(() => {
            window.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    const item = props.inventoryItems.find(i => i.reference === barCode);
                    if (item) {
                        newItem.value.item_id = item.id;
                        barCode = "";
                        addItem();
                    }
                } else {
                    barCode += e.key;
                }
            });

            fetchItems();
        });

        return {
            items,
            newItem,
            newCustomCharge,
            addItem,
            removeItem,
            handleItemChange,
            handleItemSelected,
            fetchItems,
            addCustomCharge
        };
    },
};
</script>

<style scoped>
    .select2-selection {
        border-color: #dee2e6 !important;
    }

    td {
        vertical-align: middle;
    }

    .form-floating .select2-container .select2-selection {
        height: calc(4rem + 2px);
        padding: 1rem;
        border-radius: 0.375rem !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #bdbcbc !important;
    }

    .form-floating .select2-container .select2-selection>.select2-selection__rendered {
        margin-top: 0.6rem;
    }

    .select2-container *:focus {
        outline: none;
    }

    .v-select {
        background-color: white;
    }
</style>
