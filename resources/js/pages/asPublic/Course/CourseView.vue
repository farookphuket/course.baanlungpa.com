<template>
    <section class="body_content">
        <div class="container">
            <div v-for="co in course">
                <p class="title">{{co.course_name}}</p>
                <p class="subtitle">
                    <font-awesome-icon 
                        icon="at"></font-awesome-icon> 
                    <span v-for="cl in co.classroom" 
                          class="ml-1">{{cl.class_title}} </span>
                </p>
                <!-- show owner,date START -->
                <div class="columns is-mobile">
                    <div class="column">
                        <div class="field">
                            <p>
                                <span class="mr-2">
                                    <font-awesome-icon 
                                        icon="user"></font-awesome-icon>
                                </span>
                                <span>
                                    {{co.user.name}}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="column">
                        <p>
                            <span class="ml-2">
                                <font-awesome-icon 
                                    icon="calendar-week"></font-awesome-icon>
                            </span>
                            <span class="ml-1">
                                {{moment(co.created_at)
                                .format("YY-MM-DD HH:mm:ss")}}
                            </span>
                            <span class="ml-2">
                                {{moment(co.created_at)
                                .fromNow()}}
                            </span>
                        </p>
                    </div>
                </div>
                <!-- show owner,date END -->
                <div class="has-text-centered">
                    <figure class="justify-content-center">
                        <img :src="co.course_cover" 
                        :alt="co.course_name">
                    </figure>
                    <p class="pt-2">{{co.course_name}}</p>
                </div>
                <div class="box">
                    <p>{{co.course_excerpt}}</p>
                </div>
                <div class="mt-2 mb-4">
                    <div v-html="co.course_body"></div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
var moment = require("moment")

export default{
    name:"CourseView",
    data(){
        return{
            course_id:0,
            course:'',
            moment:moment,
            user_is_teacher:false,
        }
    },
    mounted(){
        this.getTheValidUrl()
    },
    methods:{
        getTheValidUrl(){
            //console.log(this.$route.params.id)
            this.course_id = this.$route.params.id
            this.showCourse()
            this.user_is_teacher = window.lsDefault.user_is_teacher
        },
        showCourse(){
            let url = `/api/course/${this.course_id}`
            axios.get(url)
                .then(res=>{
                    let rData = res.data.course
                    this.course = rData

                    rData.map((co)=>{
                        document.title = co.course_name
                    })
                    //console.log(this.course["course_name"])
                })
        },
    },

}
</script>
