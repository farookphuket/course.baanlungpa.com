<template>
    <section class="body_content">
        <div class="container">
            <p>this is teacher {{isTeacher}}</p>
            <whatup></whatup>
        </div>
    </section>
</template>
<script>
import Whatup from "./Whatup/Whatup.vue"
export default{
    name:"MemberDashboard",
    components:{
        Whatup,
    },
    data(){
        return{
            isTeacher:false,
        }
    },
    mounted(){
        this.getWhoIs()
    },
    methods:{
        getWhoIs(){
            this.isTeacher = window.lsDefault.user_is_teacher
        },
    }
}
</script>
