<template>
    <section class="body_content">
        
        <member-nav></member-nav>
        <div class="container">
            <router-view></router-view>
        </div>
    </section>
</template>

<script>
import MemberNav from './MemberNav.vue'
export default{
    name:"StudentView",
    components:{
        MemberNav,
    },
}
</script>
