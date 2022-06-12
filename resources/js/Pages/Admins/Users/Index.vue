<template lang="">
    <div>
        <AdminLayout>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Users</h3>
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
                                                    Email
                                                </th>
                                                <th class="text-capitalize">
                                                    Joined
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
                                                    user, index
                                                ) in users.data"
                                                :key="index"
                                            >
                                                <td>{{ user.name }}</td>
                                                <td>{{ user.email }}</td>
                                                <td>{{ user.created_at }}</td>
                                                <td class="text-right">
                                                    <button
                                                        class="btn btn-warning text-uppercase"
                                                        style="
                                                            letter-spacing: 0.1em;
                                                        "
                                                        @click="editModal(user)"
                                                    >
                                                        Edit
                                                    </button>
                                                    <button
                                                        class="btn btn-danger text-uppercase ms-2"
                                                        style="
                                                            letter-spacing: 0.1em;
                                                        "
                                                        @click="
                                                            deleteUser(user)
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
                                    <Pagination :links="users.links" />
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
                            <div class="h4 d-flex flex-column">
                                <span class="mb-2"
                                    >Preview Name:
                                    <span class="text-capitalize">{{
                                        form.name
                                    }}</span></span
                                >
                                <span
                                    >Preview Email:
                                    <span class="text-capitalize">{{
                                        form.email
                                    }}</span></span
                                >
                            </div>
                            <!-- <p>{{ form.roles }}</p> -->
                            <div class="card card-primary">
                                <form @submit.prevent="checkMode">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name" class="h4"
                                                >Name</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Name"
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
                                            <label for="email" class="h4"
                                                >Email</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Email"
                                                v-model="form.email"
                                                :class="{
                                                    'is-invalid':
                                                        form.errors.email,
                                                }"
                                                autofocus="autofocus"
                                                autocomplete="off"
                                            />
                                        </div>
                                        <div
                                            class="invalid-feedback mb-3"
                                            :class="{
                                                'd-block': form.errors.email,
                                            }"
                                        >
                                            {{ form.errors.email }}
                                        </div>

                                        <div class="form-group" v-if="editMode">
                                            <label for="roles" class="h4"
                                                >Roles</label
                                            >
                                            <multiselect
                                                v-model="form.roles[0]"
                                                :options="roleOptions"
                                                :multiple="false"
                                                :taggable="true"
                                                @tag="addTag"
                                                placeholder="Choose new role"
                                                label="name"
                                                track-by="id"
                                            ></multiselect>
                                        </div>
                                        <div
                                            class="invalid-feedback"
                                            :class="{
                                                'd-block': form.errors.roles,
                                            }"
                                        >
                                            {{ form.errors.roles }}
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
                                            :disabled="form.processing"
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
export default {
    props: ["roles", "users"],
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
            email: "",
            roles: [],
        });

        const roleOptions = props.roles;

        // computed
        const formTitle = computed(() => {
            return editedIndex.value === -1
                ? "Create New User"
                : "Edit Current User";
        });

        const buttonTxt = computed(() => {
            return editedIndex.value === -1 ? "Create" : "Edit";
        });

        const checkMode = computed(() => {
            return editMode.value === false ? createUser() : editUser();
        });

        // method
        const addTag = (newRole) => {
            let tag = {
                name: newRole,
            };

            roleOptions.push(tag);
            form.roles.push(tag);
        };

        const openModal = () => {
            form.clearErrors();
            editMode.value = false;
            form.reset();
            editedIndex.value = -1;
            // use jquery to open modal
            $("#modal-lg").modal("show");
        };

        const editModal = (user) => {
            editMode.value = true;
            editedIndex.value = user.id;
            $("#modal-lg").modal("show");
            form.name = user.name;
            form.email = user.email;
            form.id = user.id;
            form.roles = user.roles;
        };

        const closeModal = () => {
            form.clearErrors();
            editMode.value = false;
            form.reset();
            // use jquery to open modal
            $("#modal-lg").modal("hide");
        };

        const createUser = () => {
            // console.log("create");
            form.post(route("admin.users.store"), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    closeModal();
                    Toast.fire({
                        icon: "success",
                        title: "New User Created!",
                    });
                },
            });
        };

        const editUser = () => {
            // console.log("edit");
            form.patch(route("admin.users.update", form.id, form), {
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

        const deleteUser = (user) => {
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
                    form.delete(route("admin.users.destroy", user), {
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
            roleOptions,
            addTag,
            editModal,
            deleteUser,
            formTitle,
            buttonTxt,
            checkMode,
            editMode,
        };
    },
};
</script>

<style>
.multiselect_content-wrapper {
    position: relative;
}
</style>
