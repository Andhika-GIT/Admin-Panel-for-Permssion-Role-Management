<template lang="">
    <div>
        <AdminLayout>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Roles & Permission
                                    </h3>
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
                                                    Role Name
                                                </th>
                                                <th class="text-capitalize">
                                                    Permissions
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
                                                    role, index
                                                ) in roles.data"
                                                :key="index"
                                            >
                                                <td>{{ role.name }}</td>
                                                <td>
                                                    <div
                                                        class="d-flex flex-column"
                                                    >
                                                        <span
                                                            v-for="(
                                                                permission,
                                                                index
                                                            ) in role.permissions"
                                                            :key="index"
                                                        >
                                                            {{
                                                                permission.name
                                                            }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>{{ role.created_at }}</td>
                                                <td class="text-right">
                                                    <button
                                                        class="btn btn-warning text-uppercase"
                                                        style="
                                                            letter-spacing: 0.1em;
                                                        "
                                                        @click="editModal(role)"
                                                    >
                                                        Edit
                                                    </button>
                                                    <button
                                                        class="btn btn-danger text-uppercase ms-2"
                                                        style="
                                                            letter-spacing: 0.1em;
                                                        "
                                                        @click="
                                                            deleteRole(role)
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
                                    <Pagination :links="roles.links" />
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
                            <div class="h4">
                                <span
                                    >Preview:
                                    <span class="text-capitalize">{{
                                        form.name
                                    }}</span></span
                                >
                            </div>
                            <div class="card card-primary">
                                <form @submit.prevent="checkMode">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="role" class="h4"
                                                >Role Name</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="role"
                                                placeholder="Role Name"
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
                                            class="invalid-feedback mb-3"
                                            :class="{
                                                'd-block': form.errors.name,
                                            }"
                                        >
                                            {{ form.errors.name }}
                                        </div>

                                        <div class="form-group">
                                            <label for="permissions" class="h4"
                                                >Permissions</label
                                            >
                                            <multiselect
                                                v-model="form.permissions"
                                                name="permissions"
                                                :options="permissionOption"
                                                :multiple="true"
                                                :taggable="true"
                                                @tag="addPermissions"
                                                placeholder="Choose Permission (s)"
                                                label="name"
                                                track-by="id"
                                            ></multiselect>
                                        </div>
                                        <div
                                            class="invalid-feedback"
                                            :class="{
                                                'd-block':
                                                    form.errors.permissions,
                                            }"
                                        >
                                            {{ form.errors.permissions }}
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
// dont forget to go to Role Model and add protected Casts method to make d-m-y more user friendly
import AdminLayout from "@/Layouts/AdminLayout";
import Pagination from "@/Components/Pagination";
import { computed } from "@vue/reactivity";
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3"; // use inertia form helper
import { SyncWaterfallHook } from "tapable";
export default {
    props: ["roles", "permissions"],
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
            permissions: [],
        });

        const permissionOption = props.permissions;

        // computed
        const formTitle = computed(() => {
            return editedIndex.value === -1
                ? "Create New Role"
                : "Edit Current Role";
        });

        const buttonTxt = computed(() => {
            return editedIndex.value === -1 ? "Create" : "Edit";
        });

        const checkMode = computed(() => {
            return editMode.value === false ? createRole() : editRole();
        });

        // method
        const addPermissions = (newPermission) => {
            let permission = {
                name: newPermission,
            };

            permissionOption.push(permission);
            form.permissions.push(permission);
        };

        const openModal = () => {
            form.clearErrors();
            editMode.value = false;
            form.reset();
            editedIndex.value = -1;
            // use jquery to open modal
            $("#modal-lg").modal("show");
        };

        const editModal = (role) => {
            editMode.value = true;
            editedIndex.value = role.id;
            $("#modal-lg").modal("show");
            form.name = role.name;
            form.id = role.id;
            form.permissions = role.permissions;
        };

        const closeModal = () => {
            form.clearErrors();
            editMode.value = false;
            form.reset();
            // use jquery to open modal
            $("#modal-lg").modal("hide");
        };

        const createRole = () => {
            // console.log("create");
            form.post(route("admin.roles.store"), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    closeModal();
                    Toast.fire({
                        icon: "success",
                        title: "New Role Created!",
                    });
                },
            });
        };

        const editRole = () => {
            // console.log("edit");
            form.patch(route("admin.roles.update", form.id, form), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    closeModal();
                    Toast.fire({
                        icon: "success",
                        title: "Role has been updated!",
                    });
                },
            });
        };

        const deleteRole = (role) => {
            // console.log("edit");
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.delete(route("admin.roles.destroy", role), {
                        preserveScroll: true,
                        onSuccess: () => {
                            closeModal();
                            Toast.fire({
                                icon: "success",
                                title: "Role successfuly deleted",
                            });
                        },
                    });
                }
            });
        };

        return {
            form,
            openModal,
            closeModal,
            permissionOption,
            addPermissions,
            editModal,
            deleteRole,
            formTitle,
            buttonTxt,
            checkMode,
        };
    },
};
</script>

<style>

</style>
