<template>
    <div>
        <member-nav></member-nav>
        <router-view></router-view>
    </div>
</template>
<script>
import MemberNav from "../_include/MemberNav.vue"
export default{
    name:"MemberView",
    components:{
        MemberNav,
    },
}
</script>
