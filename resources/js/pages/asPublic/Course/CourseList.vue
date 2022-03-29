<template>
    <div>
        <div class="box" v-if="num_course === 0">
            <p class="title">
                There is no free course at the moment!
            </p>
        </div>
        <div class="mt-4 mb-4 pb-4" v-else>
            <div class="columns is-mobile is-multiline">
                <div class="column is-4" 
                    v-for="co in course.data">
                        <!-- card 3 START -->
                        <div class="card">
                          <div class="card-image">
                            <figure class="image is-4by3">
                                <a href="" 
                                    @click.prevent="openCourse(co.id)">
                                  <img 
                                  :src="co.course_cover" 
                                  :alt="co.course_name">
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
                                <p class="title is-4">
                                    {{co.course_name}}
                                </p>
                                <p class="subtitle is-6">
                                    <a href="" 
                                        :title="co.user.email">
                                        @{{co.user.name}}
                                    </a>
                                </p>
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
                                  {{moment(co.created_at)
                                  .format("YY-MM-DD HH:mm:ss")}}
                              </span>
                              <span class="ml-1">
                                  {{moment(co.created_at)
                                  .fromNow()}}
                              </span>

                            </div>
                          </div>
                        </div>
                        <!-- card list END -->
                </div>
            </div>
        </div>


    </div>
</template>



<script>
var moment = require('moment')

export default{
    name:"CourseList",
    props:["course","num_course"],
    data(){
        return{
            moment:moment,
        }
    },
    mounted(){

    },
    methods:{
        /*
        the3Obj(){
            this.theLast3Object = this.theArray
                .slice(Math.max(this.theArray.length - 3,0))
        },
        */
        openCourse(id){
            let url = `/course/${id}`
            location.href=url
        },
    },

}
</script>
