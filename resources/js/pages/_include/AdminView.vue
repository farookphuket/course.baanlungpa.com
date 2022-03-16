<template>
    <div>
        <admin-nav></admin-nav>
        <router-view></router-view>
    </div>
</template>
<script>
import AdminNav from './AdminNav.vue'

export default{
    name:"AdminView",
    components:{
        AdminNav,
    },
    mounted(){

    },
}
</script>
