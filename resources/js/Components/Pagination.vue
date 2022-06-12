<template lang="">
    <div>
        <nav>
            <ul class="pagination pagination-sm m-0 float-right">
                <li
                    v-for="(link, index) in links"
                    :key="index"
                    class="page-item"
                    :class="[
                        link.url === null ? 'disable' : '',
                        link.active ? 'active' : '',
                    ]"
                >
                    <Link
                        class="page-link"
                        :href="link.url === null ? '#' : link.url"
                        v-html="link.label"
                        preserve-scroll
                    />
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    props: ["links"],
    components: { Link },
};
</script>
<style lang=""></style>
