<template>
    <section class="body_content">
        <div class="container">
            <blog-list :blogs="blogs" 
                :isNoBlog="isNoBlog" 
                @openBlog="openBlog($event)"></blog-list>
        </div>
    </section>
</template>
<script>
import BlogList from "./BlogList.vue"
export default{
    name:"PubBlog",
    components:{
        BlogList,
    },
    data(){
        return{
            blogs:'',
            isNoBlog:false,
        }
    },
    mounted(){
        this.getBlog()
    },
    methods:{
        getBlog(page){
            let url = ''
            if(page){
                url = page 
                this.$cookies.set('pubblog_old_page',url)
            }
            url = this.$cookies.get('pubblog_old_page')
            if(!url) url = `/api/blog`
            axios.get(url)
                .then(res=>{
           //         console.log(res.data)
                    let blog_list = res.data.blog
                    if(Object.values(blog_list.data).length === 0){
                        this.isNoBlog = true
                    }
                    this.blogs = blog_list

            })
        },
        openBlog(slug){
            let url = `/${slug}`
            location.href=url
        },
    },
}
</script>
