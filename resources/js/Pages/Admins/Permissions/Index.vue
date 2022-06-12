<template lang="">
    <div>
        <AdminLayout>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Permissions</h3>
                                    <div class="card-tools">
                                        <button
                                            type="button"
                                            class="btn btn-info text-uppercase"
                                            style="letter-spacing: 0.1em"
                                            @click="openModal"
                                        >
                                            Create
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table
                                        class="table table-hover text-nowrap"
                                    >
                                        <thead>
                                            <tr>
                                                <th class="text-capitalize">
                                                    Name
                                                </th>
                                                <th class="text-capitalize">
                                                    Description
                                                </th>
                                                <th class="text-capitalize">
                                                    Created
                                                </th>
                                                <th
                                                    class="text-capitalize text-right"
                                                >
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    permission, index
                                                ) in permissions.data"
                                                :key="index"
                                            >
                                                <td>{{ permission.name }}</td>
                                                <td>
                                                    {{ permission.description }}
                                                </td>

                                                <td>
                                                    {{ permission.created_at }}
                                                </td>
                                                <td class="text-right">
                                                    <button
                                                        class="btn btn-warning text-uppercase"
                                                        style="
                                                            letter-spacing: 0.1em;
                                                        "
                                                        @click="
                                                            editModal(
                                                                permission
                                                            )
                                                        "
                                                    >
                                                        Edit
                                                    </button>
                                                    <button
                                                        class="btn btn-danger text-uppercase ms-2"
                                                        style="
                                                            letter-spacing: 0.1em;
                                                        "
                                                        @click="
                                                            deletePermission(
                                                                permission
                                                            )
                                                        "
                                                    >
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer clearfix">
                                    <Pagination :links="permissions.links" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="modal-lg">
                <div class="modal-dialog modal-lg modal-dialog centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ formTitle }}</h4>
                            <button
                                type="button"
                                class="close"
                                @click="closeModal"
                                aria-label="close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body overflow-hidden">
                            <p class="h6 text-warning">
                                ** All permission must start with "create: ",
                                "read: ", "update: " or "delete: "
                            </p>
                            <div class="d-flex flex-column h4">
                                <span
                                    >Preview Name:
                                    <span class="">{{ form.name }}</span></span
                                >
                                <span
                                    >Preview Description:
                                    <span class="mt-3">{{
                                        form.description
                                    }}</span></span
                                >
                            </div>
                            <div class="card card-primary">
                                <form @submit.prevent="checkMode">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name" class="h4"
                                                >Permission Name</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="permission"
                                                placeholder="Permission Name"
                                                v-model="form.name"
                                                :class="{
                                                    'is-invalid':
                                                        form.errors.name,
                                                }"
                                                autofocus="autofocus"
                                                autocomplete="off"
                                            />
                                        </div>
                                        <div
                                            class="invalid-feedback"
                                            :class="{
                                                'd-block': form.errors.name,
                                            }"
                                        >
                                            {{ form.errors.name }}
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="h4"
                                                >Permission Description</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="description"
                                                placeholder="Permission Description"
                                                v-model="form.description"
                                                :class="{
                                                    'is-invalid':
                                                        form.errors.description,
                                                }"
                                                autocomplete="off"
                                            />
                                        </div>
                                        <div
                                            class="invalid-feedback mb-3"
                                            :class="{
                                                'd-block':
                                                    form.errors.description,
                                            }"
                                        >
                                            {{ form.errors.description }}
                                        </div>
                                    </div>

                                    <div
                                        class="modal-footer justify-content-between"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-danger text-uppercase"
                                            style="letter-spacing: 0.1em"
                                            @click="closeModal"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            class="btn btn-info text-uppercase"
                                            style="letter-spacing: 0.1em"
                                            :disable="form.processing"
                                        >
                                            {{ buttonTxt }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    </div>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout";
import Pagination from "@/Components/Pagination";
import { computed } from "@vue/reactivity";
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3"; // use inertia form helper
export default {
    props: ["permissions"],
    components: {
        AdminLayout,
        Pagination,
    },
    setup(props) {
        // variabels

        let editedIndex = ref(-1);
        let editMode = ref(false);
        const form = useForm({
            id: "",
            name: "",
            description: "",
        });

        // computed
        const formTitle = computed(() => {
            return editedIndex.value === -1
                ? "Create New Permission"
                : "Edit Current Permission";
        });

        const buttonTxt = computed(() => {
            return editedIndex.value === -1 ? "Create" : "Edit";
        });

        const checkMode = computed(() => {
            return editMode.value === false
                ? createPermission()
                : editPermission();
        });

        // method
        const openModal = () => {
            form.clearErrors();
            editMode.value = false;
            form.reset();
            editedIndex.value = -1;
            // use jquery to open modal
            $("#modal-lg").modal("show");
        };

        const editModal = (permission) => {
            editMode.value = true;
            editedIndex.value = permission.id;
            $("#modal-lg").modal("show");
            form.id = permission.id;
            form.name = permission.name;
            form.description = permission.description;
        };

        const closeModal = () => {
            form.clearErrors();
            editMode.value = false;
            form.reset();
            // use jquery to open modal
            $("#modal-lg").modal("hide");
        };

        const createPermission = () => {
            // console.log("create");
            form.post(route("admin.permissions.store"), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    closeModal();
                    Toast.fire({
                        icon: "success",
                        title: "New Permission Created!",
                    });
                },
            });
        };

        const editPermission = () => {
            console.log("edit");
            form.patch(route("admin.permissions.update", form.id, form), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    closeModal();
                    Toast.fire({
                        icon: "success",
                        title: "Permission has been updated!",
                    });
                },
            });
        };

        const deletePermission = (permission) => {
            console.log("edit");
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.delete(
                        route("admin.permissions.destroy", permission),
                        {
                            preserveScroll: true,
                            onSuccess: () => {
                                closeModal();
                                Toast.fire({
                                    icon: "success",
                                    title: "Permissions successfuly deleted",
                                });
                            },
                        }
                    );
                }
            });
        };

        return {
            form,
            openModal,
            closeModal,
            editModal,
            deletePermission,
            formTitle,
            buttonTxt,
            checkMode,
        };
    },
};
</script>

<style></style>
