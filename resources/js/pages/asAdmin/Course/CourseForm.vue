<template>
    <div>
        <form action="">
            <div class="field">
                <div class="control has-icons-right">
                    <label class="label" for="">
                        course name(รายชื่อวิชา) 
                    </label>
                    <input v-model="cForm.co_name" class="input" type="text" 
                    name="" placeholder="course Title...">
                    <span class="is-right icon">{{cForm.co_name.length}}</span>
                </div>
            </div><!-- end of course-title -->

        </form>
    </div>
</template>
<script>
export default{
    name:"CourseForm",
    props:["editId"],
    data(){
        return{
            cForm:new Form({
                co_name:'',
                co_year:'',
                co_term:'',
                co_id:'',
                co_credit:'',
                co_hours:'',
                co_is_free:false,
                co_is_public:true,
                released_at:'',
            }),
        }
    },
}
</script>
