<template>
    <div>

<section class="hero is-danger is-fullheight">
  <div class="hero-body">
      <!--
    <div class="">
      <p class="title has-text-danger">
        Fullheight hero
      </p>
      <p class="subtitle has-background-dark">
        Fullheight subtitle
      </p>
    </div>
      -->
  </div>
</section>
<section class="body_content">


        <!-- show list of whatup START -->
        
        <div class="container">

            <p class="title" v-if="whatup.data == 0">
                There is no data yet!
            </p>
            <article class="box content mb-4" 
                v-for="wp in whatup.data" v-else>
                <p class="title has-text-centered">
                    {{wp.wp_title}}
                </p>
                <div class="mb-4">
                    <nav class="level is-mobile mb-4 mt-4">
                        <div class="level-item">
                            <span class="ml-2 pr-2">
                                <font-awesome-icon icon="user">
                                </font-awesome-icon>
                            </span>
                            <span>
                                {{wp.user.name}}
                            </span>
                        </div>

                        <div class="level-item">
                            <span class="ml-2 pr-2">
                                <font-awesome-icon icon="calendar-week">
                                </font-awesome-icon>
                            </span>
                            <span class="pr-2">
                                {{moment(wp.created_at).format("DD-MM-YY H:m:s")}}
                            </span>
                            <span>
                                {{moment(wp.created_at).fromNow()}}
                            </span>
                        </div>
                    </nav>
                </div><!-- end of div.no class -->

                <div v-html="wp.wp_body"></div>
            </article>

            <!-- pagination START -->
            <div class="content mt-4 mb-4" v-if="whatup.total != 0">

                <nav class="pagination is-rounded" role="navigation" 
                    aria-label="pagination">
                    <a class="pagination-previous is-current">All post(s) 
                        {{whatup.total}}</a>
                    <a class="pagination-next is-current">
                        page {{whatup.current_page}}</a>
                  <ul class="pagination-list" v-for="ln in whatup.links">
                    <li v-if="ln.url != null && ln.active == false">
                      <a class="pagination-link" 
                      aria-label="Page 1" aria-current="page" v-html="ln.label" 
                      @click.prevent="$emit('getWhatup',ln.url)"></a>
                    </li>
                    <li v-else>
                      <a class="pagination-link is-current"  v-if="ln.active == true" 
                      aria-label="" aria-current="page" v-html="ln.label" 
                      ></a>

                      <a class="pagination-link"  v-else 
                      aria-label="" aria-current="page" v-html="ln.label" 
                      ></a>
                    </li>

                  </ul>
                </nav>
            </div>
            <!-- pagination END -->
        </div>
        <!-- show list of whatup END -->

</section>



        

        <!-- modal START -->
        <div class="modal" :class="{'is-active':isModalShow}">
            <div class="modal-background"></div>
            <div class="modal-content">
                <!-- Any other Bulma elements you want -->
                <div class="box">
                    <p>this is the modal content</p>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" 
                @click.prevent="isModalShow = ''"></button>
        </div>
        <!-- modal END -->

    </div>
</template>

<script>
var moment = require('moment')
export default {
    name: "Home",
    data(){return{
        isModalShow:false,
        whatup:'',
        moment:moment,
    }},
    mounted(){
        this.getWhatup()
    },
    methods:{
        getWhatup(page){
            let url = ''
            if(page){
                url = page
                this.$cookies.set('pOld_page',url)
            }
            url = this.$cookies.get('pOld_page')
            if(!url) url = `/api/getwhatup`
            axios.get(url)
                .then(res=>{
                    this.whatup = res.data.whatup
                })
        },
    },
}
</script>
