<template lang="">
    <div>
        <div class="dark-mode">
            <div class="wrapper">
                <!-- Preloader -->
                <!-- <div
                    class="preloader flex-column justify-content-center align-items-center"
                >
                    <img
                        class="animation__wobble"
                        :src="'/storage/images/AdminLTELogo.png'"
                        alt="AdminLTELogo"
                        height="60"
                        width="60"
                    />
                </div> -->

                <!-- Navbar -->
                <Navbar />
                <!-- /.navbar -->

                <!-- Main Sidebar Container -->
                <Sidebar />

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <slot name="header"></slot>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->
                    </div>
                    <!-- /.content-header -->

                    <!-- Main content -->
                    <slot></slot>
                    <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->

                <!-- Main Footer -->
                <Footer />
            </div>
        </div>
    </div>
</template>
<script>
import Footer from "@/Components/Footer";
import Navbar from "@/Components/Navbar";
import Sidebar from "@/Components/Sidebar";
export default {
    components: {
        Footer,
        Navbar,
        Sidebar,
    },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            let SELECTOR_LOADER = ".preloader";
            setTimeout(() => {
                let $loader = $(SELECTOR_LOADER);
                if ($loader) {
                    $loader.css("height", 0);
                    setTimeout(() => {
                        $loader.children().hide();
                    }, 500);
                }
            }, 500);
        },
    },
};
</script>
<style lang=""></style>
