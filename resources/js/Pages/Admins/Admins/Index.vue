<template lang="">
    <div>
        <AdminLayout>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Admins</h3>
                                    <!-- <div class="card-tools">
                                        <button
                                            type="button"
                                            class="btn btn-info text-uppercase"
                                            style="letter-spacing: 0.1em"
                                            @click="openModal"
                                        >
                                            Create
                                        </button>
                                    </div> -->
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
                                                    Role
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
                                                    admin, index
                                                ) in admins.data"
                                                :key="index"
                                            >
                                                <td class="text-capitalize">
                                                    {{ admin.name }}
                                                </td>
                                                <td>
                                                    <template
                                                        v-for="role in admin.roles"
                                                        :key="role.id"
                                                    >
                                                        {{ role.name }}
                                                    </template>
                                                </td>
                                                <td>{{ admin.email }}</td>
                                                <td>{{ admin.created_at }}</td>
                                                <td class="text-right">
                                                    <button
                                                        class="btn btn-warning text-uppercase"
                                                        style="
                                                            letter-spacing: 0.1em;
                                                        "
                                                        @click="
                                                            editModal(admin)
                                                        "
                                                    >
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer clearfix">
                                    <Pagination :links="admins.links" />
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
                            <h4 class="modal-title">Edit Current Admin</h4>
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
                            <div class="card card-primary">
                                <form @submit.prevent="editAdmin">
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
                                                disabled
                                            />
                                        </div>

                                        <div class="form-group">
                                            <label for="roles" class="h4"
                                                >Roles</label
                                            >
                                            <multiselect
                                                v-model="form.roles"
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
                                            :disable="form.processing"
                                        >
                                            Update
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
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3"; // use inertia form helper
export default {
    props: ["roles", "admins"],
    components: {
        AdminLayout,
        Pagination,
    },
    setup(props) {
        // variabels

        let editedIndex = ref(-1);
        const form = useForm({
            id: "",
            name: "",
            email: "",
            roles: [],
        });

        const roleOptions = props.roles;

        // method
        const addTag = (newRole) => {
            let tag = {
                name: newRole,
            };

            roleOptions.push(tag);
            form.roles.push(tag);
        };

        const editModal = (admin) => {
            editedIndex.value = admin.id;
            $("#modal-lg").modal("show");
            form.name = admin.name;
            form.email = admin.email;
            form.id = admin.id;
            form.roles = admin.roles;
        };

        const closeModal = () => {
            form.clearErrors();
            form.reset();
            // use jquery to open modal
            $("#modal-lg").modal("hide");
        };

        const editAdmin = () => {
            // console.log("edit");
            form.patch(route("admin.admins.update", form.id, form), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    closeModal();
                    Toast.fire({
                        icon: "success",
                        title: "Admin has been updated!",
                    });
                },
            });
        };

        return {
            form,
            closeModal,
            roleOptions,
            addTag,
            editModal,
            editAdmin,
        };
    },
};
</script>

<style>
.multiselect_content-wrapper {
    position: relative;
}
</style>
