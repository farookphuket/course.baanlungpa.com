<template>
    <div>
        <div class="box" 
            v-if="isNoCourse === true">
            <p class="title has-text-danger has-text-centered">
                There is no Course yet!
            </p>
        </div>
        <div class="columns is-mobile is-multiline" v-else>

            <div class="column is-4 list-4 " 
                v-for="co in courses.data">

                <!-- show list as card START -->
                <div class="card">
                  <div class="card-image">
                    <figure class="image is-4by3">
                        <a href="" 
                            @click.prevent="openCourse(co.id)">
                            <img 
                            :src="co.course_cover" :alt="co.course_name">
                        </a>

                    </figure>
                  </div>
                  <div class="card-content">
                    <div class="media">
                      <div class="media-left">
                        <figure class="image is-48x48">
                          <img 
                          :src="co.course_cover" 
                          :alt="co.course_name">
                        </figure>
                      </div>
                      <div class="media-content">
                          <p class="title is-4">{{co.user.name}}</p>
                          <p class="subtitle is-6">{{co.user.email}}</p>
                      </div>
                    </div>

                    <div class="content">
                        <div>{{co.course_excerpt}}</div>
                      <br>

                      <span class="mr-2">
                          <font-awesome-icon 
                              icon="calendar-week"></font-awesome-icon>
                      </span>
                      <span class="mr-2">
                          {{moment(co.created_at).format("YY-MM-DD HH:mm:ss")}}
                      </span>
                      <span class="mr-2">
                          {{moment(co.created_at).fromNow()}}
                      </span>

                    </div>

                    <div class="pb-4 mt-2" v-if="isOwner === co.user.id">
                        <div class="field is-pulled-right">
                            <button class="button 
                                is-primary is-rounded is-small is-outlined mr-2" 
                                @click.prevent="$emit('edit',co.id)">
                                <font-awesome-icon 
                                    icon="edit"></font-awesome-icon>
                            </button>
                            <button class="button 
                                is-danger is-rounded is-small is-outlined" 
                                @click.prevent="$emit('del',co.id)">
                                <font-awesome-icon 
                                    icon="trash"></font-awesome-icon>
                            </button>
                        </div>
                    </div>

                  </div>
                </div>
                <!-- show list as card END -->



            </div>

        </div>
    </div>
</template>
<script>
var moment = require('moment')
export default{
    name:"CourseList",
    props:["courses","isNoCourse"],
    data(){
        return{
            moment:moment,
            isOwner:false,
        }
    },
    mounted(){
        this.isOwner = window.lsDefault.user_id
    },
    methods:{
        openCourse(id){
            let url = `/course/${id}`
            location.href=url
        },
    },
}
</script>
