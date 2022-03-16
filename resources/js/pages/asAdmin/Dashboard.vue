<template>
    <section class="body_content">
        <div class="container">
            <!-- div.columns START -->
            <div class="columns is-mobile">
                <div class="column">
                    <!-- show new user START -->
                    <show-new-user></show-new-user>
                    <!-- show new user END -->
                </div>
                <div class="column">
                    <new-comment></new-comment>
                </div>
            </div>
            <!-- div.columns START -->

            <!-- show new course,new teacher START -->
            <div class="columns is-mobile">
                <div class="column">
                    <div class="box">
                        <course-state></course-state>
                    </div>
                </div>

            </div>
            <!-- show new course,new teacher END -->

            <admin-whatup></admin-whatup>
        </div>
    </section>
</template>
<script>
import ShowNewUser from "./Dashboard/NewUser.vue"
import NewComment from "./Dashboard/NewComment.vue"
import AdminWhatup from "./Dashboard/Whatup.vue"
import CourseState from "../asPublic/Course/CourseState.vue"

export default{
    name:"AdminDashboard",
    components:{
        ShowNewUser,
        NewComment,
        AdminWhatup,
        CourseState,
    },
}
</script>
