<template>
    <div>
        <div class="box" 
            v-if="isNoCourse === true">
            <p class="title has-text-danger has-text-centered">
                There is no Course yet!
            </p>
        </div>
        <div class="columns is-mobile" v-else>

            <div class="column is-4 list-4 " 
                v-for="co in courses.data">

                <!-- show list as card START -->
                <div class="card">
                  <div class="card-image">
                    <figure class="image is-4by3">
                      <img :src="co.course_cover" alt="Placeholder image">
                    </figure>
                  </div>
                  <div class="card-content">
                    <div class="media">
                      <div class="media-left">
                        <figure class="image is-48x48">
                          <img 
                          src="https://bulma.io/images/placeholders/96x96.png" 
                          alt="Placeholder image">
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
        }
    },
}
</script>
