<template>
    <section class="body_content">
        <div class="container">
            <article class="mb-6 pb-6">
                <p class="title mt-4">{{blog.bl_title}}</p>
                <p class="subtitle">
                    <span class="ml-2">
                        {{blog.bl_slug}} 
                    </span>
                    <span class="ml-4 mr-2">
                        <font-awesome-icon 
                            icon="at"></font-awesome-icon>
                    </span>
                    <span 
                        v-for="c in blog.category">
                        <a href="" 
                            @click.prevent="openCategory(c.id)">
                            {{c.cat_name}}    
                        </a>
                    </span>
                </p>
                <!-- show slug -->
                <div class="mt-2 mb-4">
                    <div class="columns is-mobile">
                        <div class="column">
                            <p class="tags">
                                <span class="tag is-info is-medium">
                                    <font-awesome-icon 
                                        icon="calendar-week"></font-awesome-icon>
                                </span>
                                <span class="tag is-medium">
                                    {{moment(blog.created_at).format("YYYY-MM-DD HH:mm:ss")}}
                                </span>
                                <span class="tag is-medium">
                                    {{moment(blog.created_at).fromNow()}}
                                </span>
                            </p>
                        </div>
                        <div class="column">
                            <div class="pb-4 mb-4">
                                <div class="field is-pulled-left">
                                    <span class="mr-2">
                                        <font-awesome-icon 
                                            icon="eye"></font-awesome-icon>
                                    </span>
                                    <span>
                                        {{hasRead}}
                                    </span>
                                </div>
                                <div class="field is-pulled-right">
                                    <p class="tags">
                                        <span class="mr-2">
                                            <font-awesome-icon 
                                                icon="user"></font-awesome-icon>
                                        </span>
                                        <span class="tag is-medium" 
                                            >
                                            {{bUName}}
                                        </span>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- show slug end -->

                <div class="mt-2 mb-4 pb-4">
                    <div v-html="blog.bl_excerpt"></div>
                </div>
                <div class="mt-2 mb-4 pb-4">
                    <div v-html="blog.bl_body"></div>
                </div>

                <!-- show comment list Start -->
                <div class="pt-6 pb-4 mt-6 mb-4">
                    <p class="title has-text-centered">
                        Comment(s). 
                        <span>{{cm}}</span>
                    </p>
                    <pub-comment :blog_id="blog_id" 
                        :isHasToken="isHasToken"></pub-comment>
                </div>
                <!-- show comment list Start -->

            </article>
        </div>
    </section>
</template>
<script>
var moment = require('moment')
import PubComment from "./Comment/Comment.vue"
export default{
    name:"BlogView",
    components:{
        PubComment,
    },
    data(){return{
        theSlug:'',
        blog:'',
        bUName:'',
        cm:0,
        moment:moment,
        hasRead:0,
        blog_id:0,
        isHasToken:null,
    }},
    mounted(){
        this.getSlug()
    },
    methods:{
        getBlog(){
            let url = `/api/blog/${this.theSlug}`
            axios.get(url)
                .then(res=>{
                    this.blog = res.data.blog
                    this.bUName = this.blog.user.name
                    //console.log(Object.values(this.blog.read).length)
                    this.hasRead = Object.values(this.blog.read).length
                    this.cm = Object.values(this.blog.comment).length
                    this.blog_id = this.blog.id
                    document.title = this.blog.bl_title
                    //console.log(this.blog.user)
                })
        },
        getSlug(){
            this.theSlug = this.$route.params.slug
            this.getBlog()
            if(this.$cookies.get("token") && 
                this.$cookies.get("token") !== ''){
                this.isHasToken = this.$cookies.get("token")
            }
            // just for manage with the cookies later
        },
        openCategory(x){
            let url = `/category/${x}`
            location.href=url
        },
    },
}
</script>
