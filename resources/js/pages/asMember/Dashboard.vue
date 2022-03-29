<template>
    <section class="body_content">
        <div class="container">
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
            isStudent:false,
        }
    },
    mounted(){
        this.getWhoIs()
    },
    methods:{
        getWhoIs(){
            this.isTeacher = window.lsDefault.user_is_teacher
            this.isStudent = window.lsDefault.user_is_student
        },
    }
}
</script>
